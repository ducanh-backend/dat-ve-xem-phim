<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends Controller {
    // Hiển thị form đăng nhập
    public function login() {
        // Nếu đã đăng nhập và là admin thì vào thẳng trang quản trị
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            header('Location: ' . BASE_URL . 'admin/movies');
            exit;
        }

        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        $registered_email = $_SESSION['registered_email'] ?? '';
        unset($_SESSION['registered_email']);

        return $this->view('auth.login', compact('error', 'message', 'registered_email'));
    }

    // Hiển thị form đăng ký
    public function register() {
        // Nếu đã đăng nhập thì điều hướng về trang chủ hoặc trang quản trị
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] === 'admin') {
                header('Location: ' . BASE_URL . 'admin/movies');
            } else {
                header('Location: ' . BASE_URL);
            }
            exit;
        }

        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);

        return $this->view('auth.register', compact('error', 'message', 'old'));
    }

    // Xử lý gửi form đăng ký
    public function postRegister() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        // Lưu lại dữ liệu cũ để người dùng không phải nhập lại từ đầu nếu có lỗi
        $_SESSION['old'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ];

        // 1. Kiểm tra để trống
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ các thông tin bắt buộc!';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // 2. Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Địa chỉ Email không đúng định dạng!';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // 3. Kiểm tra số điện thoại (nếu có nhập)
        if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
            $_SESSION['error'] = 'Số điện thoại phải từ 10-11 chữ số hợp lệ!';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // 4. Kiểm tra độ dài mật khẩu
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Mật khẩu phải chứa ít nhất 6 ký tự!';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // 5. Kiểm tra mật khẩu xác nhận
        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // 6. Kiểm tra email đã tồn tại hay chưa
        $existingUser = User::findByEmail($email);
        if ($existingUser) {
            $_SESSION['error'] = 'Email này đã được sử dụng. Vui lòng chọn email khác!';
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        // 7. Băm mật khẩu và thêm user mới với role mặc định là 'user'
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try {
            User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $hashedPassword,
                'role' => 'user'
            ]);

            // Xóa dữ liệu cũ trong session
            unset($_SESSION['old']);

            // Lưu thông báo thành công và email để điền sẵn vào trang đăng nhập
            $_SESSION['message'] = 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.';
            $_SESSION['registered_email'] = $email;

            header('Location: ' . BASE_URL . 'login');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra trong quá trình đăng ký: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'register');
            exit;
        }
    }

    // Xử lý gửi form đăng nhập
    public function postLogin() {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ Email và Mật khẩu!';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Tìm user theo email
        $user = User::findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'Email hoặc mật khẩu không chính xác!';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Kiểm tra mật khẩu (hỗ trợ hash password và text thường cho tài khoản cũ)
        $passwordValid = false;
        if (
            password_verify($password, $user->password) || 
            $password === $user->password
        ) {
            $passwordValid = true;
        }

        if (!$passwordValid) {
            $_SESSION['error'] = 'Email hoặc mật khẩu không chính xác!';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Đăng nhập thành công -> lưu session
        $_SESSION['user'] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        // Phân quyền chuyển hướng
        if ($user->role === 'admin') {
            $_SESSION['success'] = 'Xin chào Quản trị viên ' . $user->name . '!';
            header('Location: ' . BASE_URL . 'admin/movies');
        } else {
            $_SESSION['success'] = 'Đăng nhập thành công!';
            header('Location: ' . BASE_URL);
        }
        exit;
    }

    // Đăng xuất
    public function logout() {
        unset($_SESSION['user']);
        $_SESSION['message'] = 'Bạn đã đăng xuất thành công!';
        header('Location: ' . BASE_URL . 'login');
        exit;
    }

    // Hiển thị thông tin tài khoản (Xem trang Profile)
    public function profile() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem thông tin tài khoản!';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $user = User::find($userId);

        if (!$user) {
            unset($_SESSION['user']);
            $_SESSION['error'] = 'Tài khoản không tồn tại hoặc đã bị khóa!';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);
        $activeTab = $_SESSION['active_tab'] ?? 'profile';
        unset($_SESSION['active_tab']);

        return $this->view('auth.profile', compact('user', 'error', 'success', 'activeTab'));
    }

    // Cập nhật thông tin tài khoản (Họ tên, SĐT)
    public function updateProfile() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $_SESSION['active_tab'] = 'profile';

        if (empty($name)) {
            $_SESSION['error'] = 'Họ và tên không được để trống!';
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }

        if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
            $_SESSION['error'] = 'Số điện thoại phải gồm 10-11 chữ số hợp lệ!';
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }

        try {
            User::update($userId, [
                'name' => $name,
                'phone' => $phone
            ]);

            // Cập nhật lại session user
            $_SESSION['user']['name'] = $name;

            $_SESSION['success'] = 'Cập nhật thông tin tài khoản thành công!';
            header('Location: ' . BASE_URL . 'profile');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Không thể cập nhật thông tin: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }
    }

    // Đổi mật khẩu tài khoản (Tách riêng chức năng)
    public function changePassword() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        $_SESSION['active_tab'] = 'password';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ tất cả các trường mật khẩu!';
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp với mật khẩu mới!';
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }

        $user = User::find($userId);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản người dùng!';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Kiểm tra mật khẩu hiện tại
        $currentValid = false;
        if (
            password_verify($currentPassword, $user->password) ||
            $currentPassword === $user->password
        ) {
            $currentValid = true;
        }

        if (!$currentValid) {
            $_SESSION['error'] = 'Mật khẩu hiện tại không chính xác!';
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }

        // Băm mật khẩu mới và cập nhật
        try {
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            User::update($userId, [
                'password' => $newHashedPassword
            ]);

            $_SESSION['success'] = 'Đổi mật khẩu thành công! Hãy ghi nhớ mật khẩu mới của bạn.';
            header('Location: ' . BASE_URL . 'profile');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Lỗi khi cập nhật mật khẩu: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'profile');
            exit;
        }
    }
}
