<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

class AdminController extends Controller {
    public function __construct() {
        // Kiểm tra phải đăng nhập mới được vào trang admin
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để truy cập trang quản trị!';
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    // Kiểm tra quyền hạn khi thực hiện các hành động Thêm, Sửa, Xóa
    public function checkAdminPermission() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = 'Tài khoản của bạn chỉ có quyền XEM! Chỉ Quản trị viên (Admin) mới có quyền Thêm, Sửa hoặc Xóa dữ liệu.';
            header('Location: ' . BASE_URL . 'admin/movies');
            exit;
        }
    }
}
