<?php

namespace App\Controllers\Admin;

use App\Models\User;

class UserController extends AdminController {
    // 1. Danh sách người dùng & phân quyền
    public function index() {
        $users = User::all();
        $message = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);

        return $this->view('admin.users.index', compact('users', 'message', 'error'));
    }

    // 2. Giao diện thêm người dùng mới
    public function create() {
        $this->checkAdminPermission();
        $error = $_SESSION['error_data'] ?? [];
        unset($_SESSION['error_data']);
        $posts = $_SESSION['old_posts'] ?? [];
        unset($_SESSION['old_posts']);

        return $this->view('admin.users.create', compact('error', 'posts'));
    }

    // 3. Xử lý lưu người dùng mới
    public function store() {
        $this->checkAdminPermission();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'user');

        $error = [];

        if (empty($name)) {
            $error['name'] = 'Họ và tên không được để trống!';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error['email'] = 'Email không hợp lệ!';
        } else {
            $exists = User::findByEmail($email);
            if ($exists) {
                $error['email'] = 'Email này đã tồn tại trên hệ thống!';
            }
        }

        if (empty($password) || strlen($password) < 6) {
            $error['password'] = 'Mật khẩu phải chứa ít nhất 6 ký tự!';
        }

        if (!in_array($role, ['admin', 'user'])) {
            $role = 'user';
        }

        if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
            $error['phone'] = 'Số điện thoại phải từ 10-11 số!';
        }

        if (!empty($error)) {
            $_SESSION['error_data'] = $error;
            $_SESSION['old_posts'] = $_POST;
            header('Location: ' . BASE_URL . 'admin/users/create');
            exit;
        }

        // Băm mật khẩu và tạo người dùng
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        User::create([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $hashedPassword,
            'role'     => $role
        ]);

        $_SESSION['success'] = 'Thêm mới tài khoản [' . $name . '] thành công!';
        header('Location: ' . BASE_URL . 'admin/users');
        exit;
    }

    // 4. Giao diện & Xử lý chỉnh sửa / Phân quyền tài khoản
    public function edit($id) {
        $this->checkAdminPermission();
        $user = User::find($id);

        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy người dùng!';
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = trim($_POST['role'] ?? 'user');

            $error = [];

            if (empty($name)) {
                $error['name'] = 'Họ và tên không được để trống!';
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error['email'] = 'Email không đúng định dạng!';
            } else {
                $existing = User::findByEmail($email);
                if ($existing && $existing->id != $id) {
                    $error['email'] = 'Email này đã được tài khoản khác sử dụng!';
                }
            }

            if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
                $error['phone'] = 'Số điện thoại phải từ 10-11 số!';
            }

            if (!in_array($role, ['admin', 'user'])) {
                $role = 'user';
            }

            // Bảo vệ an toàn: Admin đang đăng nhập không được tự hạ quyền của chính mình
            if ($id == $_SESSION['user']['id'] && $role !== 'admin') {
                $error['role'] = 'Bạn không thể tự hạ quyền Admin của chính mình!';
            }

            if (!empty($error)) {
                return $this->view('admin.users.edit', compact('user', 'error'));
            }

            $updateData = [
                'name'  => $name,
                'email' => $email,
                'phone' => $phone,
                'role'  => $role
            ];

            // Nếu admin nhập mật khẩu mới thì cập nhật mật khẩu, để trống thì giữ nguyên
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $error['password'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
                    return $this->view('admin.users.edit', compact('user', 'error'));
                }
                $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            User::update($id, $updateData);

            // Nếu đang sửa chính mình, cập nhật lại tên trong session
            if ($id == $_SESSION['user']['id']) {
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['email'] = $email;
            }

            $_SESSION['success'] = 'Cập nhật tài khoản và phân quyền cho [' . $name . '] thành công!';
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        return $this->view('admin.users.edit', compact('user'));
    }

    // 5. Xóa tài khoản
    public function delete($id) {
        $this->checkAdminPermission();
        // Bảo vệ an toàn: Không cho phép tự xóa tài khoản của chính mình
        if ($id == $_SESSION['user']['id']) {
            $_SESSION['error'] = 'Bạn không thể tự xóa tài khoản đang đăng nhập của chính mình!';
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        $user = User::find($id);
        if ($user) {
            User::delete($id);
            $_SESSION['success'] = 'Đã xóa tài khoản [' . $user->name . '] thành công!';
        } else {
            $_SESSION['error'] = 'Tài khoản không tồn tại!';
        }

        header('Location: ' . BASE_URL . 'admin/users');
        exit;
    }
}
