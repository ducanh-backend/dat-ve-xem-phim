<?php

namespace App\Controllers\Admin;

use App\Models\Movie;
use App\Models\Category;

class MovieController extends AdminController {
    // 1. Danh sách phim
    public function index() {
        $movies = Movie::getAllCategory();
        $message = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);
        return $this->view('admin.movies.index', compact('movies', 'message'));
    }

    // 2. Giao diện thêm mới phim
    public function create() {
        $this->checkAdminPermission();
        $categories = Category::all();
        return $this->view('admin.movies.create', compact('categories'));
    }

    // 3. Xử lý lưu phim mới
    public function store() {
        $this->checkAdminPermission();
        $posts = $_POST;
        $posts['poster'] = '';

        // validate dữ liệu
        $error = []; // lưu thông tin lỗi
        if ($posts['title'] == '' || trim($posts['title']) == '') {
            $error['title'] = 'Tiêu đề không được để trống';
        }

        // Thêm ảnh
        $file = $_FILES['poster'] ?? ($_FILES['image'] ?? []);
        // quy định định dạng ảnh được phép
        $img_ext = ['jpg', 'png', 'jpeg', 'webp', 'gif'];

        if (!empty($file['size']) && $file['size'] > 0) {
            // Lấy định dạng của file
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($file_ext, $img_ext)) {
                $error['poster'] = "Bạn cần chọn ảnh đúng định dạng";
            }
        }

        if ($error) {
            $categories = Category::all();
            return $this->view('admin.movies.create', compact('categories', 'error', 'posts'));
        }

        if (!empty($file['size']) && $file['size'] > 0) {
            $path_image = 'images/' . $file['name'];
            move_uploaded_file($file['tmp_name'], ROOT_DIR . '/' . $path_image);

            $posts['poster'] = $path_image;
        } elseif (!empty(trim($posts['poster_url'] ?? ''))) {
            $posts['poster'] = trim($posts['poster_url']);
        }

        Movie::create($posts);
        $_SESSION['success'] = 'Thêm mới thành công!';

        return header('Location: ' . BASE_URL . 'admin/movies');
    }

    // 4. Giao diện và xử lý cập nhật phim
    public function edit($id) {
        $this->checkAdminPermission();
        $movie = Movie::find($id);
        $categories = Category::all();

        // Nếu chúng ta gửi lên server là post thì sẽ thực hiện cập nhật
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $posts = $_POST;
            $file = $_FILES['poster'] ?? ($_FILES['image'] ?? []);
            $posts['poster'] = $movie->poster; // giữ nguyên ảnh cũ nếu không có ảnh mới được upload

            // Thêm ảnh
            if (!empty($file['size']) && $file['size'] > 0) {
                $path_image = 'images/' . $file['name'];
                move_uploaded_file($file['tmp_name'], ROOT_DIR . '/' . $path_image);

                $posts['poster'] = $path_image;
            } elseif (!empty(trim($posts['poster_url'] ?? ''))) {
                $posts['poster'] = trim($posts['poster_url']);
            }

            Movie::update($id, $posts);
            // Lưu thông báo vào session
            $_SESSION['success'] = 'Cập nhật thành công!';

            return header('Location: ' . BASE_URL . 'admin/movies');
        }

        return $this->view('admin.movies.edit', compact('movie', 'categories'));
    }

    // 5. Xóa phim
    public function delete($id) {
        $this->checkAdminPermission();
        Movie::delete($id);
        $_SESSION['success'] = 'Xóa phim thành công!';
        return header('Location: ' . BASE_URL . 'admin/movies');
    }
}
