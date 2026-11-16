<?php

namespace App\Controllers\Admin;

use App\Models\Category;

class CategoryController extends AdminController {
    // Danh sách thể loại
    public function index() {
        $categories = Category::all();
        $message = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);
        return $this->view('admin.categories.index', compact('categories', 'message'));
    }

    // Giao diện thêm mới
    public function create() {
        $this->checkAdminPermission();
        return $this->view('admin.categories.create');
    }

    // Xử lý lưu thể loại mới
    public function store() {
        $this->checkAdminPermission();
        $posts = $_POST;
        $error = [];

        if (empty(trim($posts['name'] ?? ''))) {
            $error['name'] = 'Tên thể loại không được để trống!';
        }

        if (!empty($error)) {
            return $this->view('admin.categories.create', compact('error', 'posts'));
        }

        // Tự tạo slug nếu chưa có
        if (empty($posts['slug'])) {
            $posts['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $posts['name'])));
        }

        Category::create([
            'name'        => trim($posts['name']),
            'slug'        => $posts['slug'],
            'description' => trim($posts['description'] ?? '')
        ]);

        $_SESSION['success'] = 'Thêm thể loại mới thành công!';
        header('Location: ' . BASE_URL . 'admin/categories');
        exit;
    }

    // Giao diện và xử lý cập nhật
    public function edit($id) {
        $this->checkAdminPermission();
        $category = Category::find($id);

        if (!$category) {
            $_SESSION['error'] = 'Không tìm thấy thể loại!';
            header('Location: ' . BASE_URL . 'admin/categories');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $posts = $_POST;
            $error = [];

            if (empty(trim($posts['name'] ?? ''))) {
                $error['name'] = 'Tên thể loại không được để trống!';
            }

            if (!empty($error)) {
                return $this->view('admin.categories.edit', compact('category', 'error', 'posts'));
            }

            if (empty($posts['slug'])) {
                $posts['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $posts['name'])));
            }

            Category::update($id, [
                'name'        => trim($posts['name']),
                'slug'        => $posts['slug'],
                'description' => trim($posts['description'] ?? '')
            ]);

            $_SESSION['success'] = 'Cập nhật thể loại thành công!';
            header('Location: ' . BASE_URL . 'admin/categories');
            exit;
        }

        return $this->view('admin.categories.edit', compact('category'));
    }

    // Xóa thể loại
    public function delete($id) {
        $this->checkAdminPermission();
        Category::delete($id);
        $_SESSION['success'] = 'Xóa thể loại thành công!';
        header('Location: ' . BASE_URL . 'admin/categories');
        exit;
    }
}
