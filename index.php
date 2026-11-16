<?php
session_start();

use App\Controllers\HomeController;
use App\Controllers\MovieController;
use App\Controllers\BookingController;
use App\Controllers\UserController;
use App\Controllers\Admin\MovieController as AdminMovieController;
use App\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Controllers\Admin\UserController as AdminUserController;

// Require composer autoloader
require_once __DIR__ . '/env.php';
require __DIR__ . '/vendor/autoload.php';


$router = new \Bramus\Router\Router();
$router->setBasePath('/ASSGD1');

// user
$router->get('/', HomeController::class . '@index');
$router->get('/contact', HomeController::class . '@contact');
$router->get('/movies', MovieController::class . '@index');
$router->get('/movie/{id}', MovieController::class . '@detail');
$router->get('/booking/seat', BookingController::class . '@seat');
$router->get('/booking/checkout', BookingController::class . '@checkout');
$router->get('/booking/success', BookingController::class . '@success');
$router->get('/booking/history', BookingController::class . '@history');

// auth
$router->get('/login', UserController::class . '@login');
$router->post('/login', UserController::class . '@postLogin');
$router->get('/register', UserController::class . '@register');
$router->post('/register', UserController::class . '@postRegister');
$router->get('/logout', UserController::class . '@logout');

// user profile & password
$router->get('/profile', UserController::class . '@profile');
$router->post('/profile/update', UserController::class . '@updateProfile');
$router->post('/profile/change-password', UserController::class . '@changePassword');

// protect admin: phải đăng nhập mới được vào trang admin
$router->before('GET|POST', '/admin.*', function() {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = 'Vui lòng đăng nhập để truy cập trang quản trị!';
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
});

// protect write actions: chỉ tài khoản có role = 'admin' mới được thêm, sửa, xóa
$router->before('GET|POST', '/admin/.*(create|store|edit|delete).*', function() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = 'Tài khoản của bạn chỉ có quyền XEM! Chỉ Quản trị viên (Admin) mới có quyền Thêm, Sửa hoặc Xóa dữ liệu.';
        header('Location: ' . BASE_URL . 'admin/movies');
        exit();
    }
});

// admin
$router->mount('/admin', function() use ($router) {
    $router->get('/', function() {
        header('Location: ' . BASE_URL . 'admin/movies');
        exit();
    });

    $router->get('/movies', AdminMovieController::class . '@index');
    $router->get('/movies/create', AdminMovieController::class . '@create');
    $router->post('/movies/store', AdminMovieController::class . '@store');
    $router->get('/movies/edit/{id}', AdminMovieController::class . '@edit');
    $router->post('/movies/edit/{id}', AdminMovieController::class . '@edit');
    $router->get('/movies/delete/{id}', AdminMovieController::class . '@delete');


    $router->get('/categories', AdminCategoryController::class . '@index');
    $router->get('/categories/create', AdminCategoryController::class . '@create');
    $router->post('/categories/store', AdminCategoryController::class . '@store');
    $router->get('/categories/edit/{id}', AdminCategoryController::class . '@edit');
    $router->post('/categories/edit/{id}', AdminCategoryController::class . '@edit');
    $router->get('/categories/delete/{id}', AdminCategoryController::class . '@delete');

    // users & permissions
    $router->get('/users', AdminUserController::class . '@index');
    $router->get('/users/create', AdminUserController::class . '@create');
    $router->post('/users/store', AdminUserController::class . '@store');
    $router->get('/users/edit/{id}', AdminUserController::class . '@edit');
    $router->post('/users/edit/{id}', AdminUserController::class . '@edit');
    $router->get('/users/delete/{id}', AdminUserController::class . '@delete');
});

$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo '404 - Page not found';
});

// Run it!
$router->run();
