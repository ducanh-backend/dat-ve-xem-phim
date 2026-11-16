<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Ticket Box')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts: Plus Jakarta Sans & Outfit (Bolder, Crisp Typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom Style High Contrast Light Mode -->
    <link rel="stylesheet" href="/ASSGD1/public/css/style.css?v=1.1">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .admin-sidebar {
            min-height: 100vh;
            background: #ffffff;
            border-right: 1.5px solid var(--border-color);
        }
        .admin-sidebar .nav-link {
            color: #1e293b !important;
            padding: 12px 18px;
            font-size: 0.95rem;
            font-weight: 600 !important;
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.2s ease;
        }
        .admin-sidebar .nav-link:hover {
            color: var(--primary);
            background: #f8fafc;
            transform: translateX(3px);
        }
        .admin-sidebar .nav-link.active {
            background-color: var(--primary-light);
            color: var(--primary) !important;
            font-weight: 700;
            border: 1px solid #c7d2fe;
        }
        .admin-topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 16px 28px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 admin-sidebar d-flex flex-column">
                <div class="p-3 text-center border-bottom">
                    <a href="/ASSGD1/admin/movies" class="text-decoration-none fw-bold fs-5 d-flex align-items-center justify-content-center">
                        <i class="bi bi-film me-2 text-primary fs-4"></i>
                        <span class="text-dark">VNBOX</span>
                        <span class="badge bg-primary text-white ms-2 px-2 py-1" style="font-size: 0.65rem;">ADMIN</span>
                    </a>
                </div>
                <div class="p-3 flex-grow-1">
                    <div class="text-muted small text-uppercase mb-3 px-2 fw-bold" style="letter-spacing: 0.8px;">Danh Mục Quản Trị</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'] ?? '', 'movies') !== false ? 'active' : '' }}" href="/ASSGD1/admin/movies">
                                <i class="bi bi-film me-2"></i> Quản Lý Phim
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'] ?? '', 'categories') !== false ? 'active' : '' }}" href="/ASSGD1/admin/categories">
                                <i class="bi bi-tags me-2"></i> Quản Lý Thể Loại
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ strpos($_SERVER['REQUEST_URI'] ?? '', 'users') !== false ? 'active' : '' }}" href="/ASSGD1/admin/users">
                                <i class="bi bi-people-fill me-2"></i> Quản Lý Người Dùng
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="p-3 border-top">
                    <a href="/ASSGD1/" target="_blank" class="btn btn-outline-secondary btn-sm w-100 mb-2 fw-semibold">
                        <i class="bi bi-globe me-1"></i> Xem Trang Khách
                    </a>
                    <a href="/ASSGD1/logout" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
                        <i class="bi bi-box-arrow-right me-1"></i> Đăng Xuất
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-9 col-lg-10 px-0" style="background-color: var(--bg-main); min-height: 100vh;">
                <div class="admin-topbar d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark fw-bold">@yield('page_title', 'Bảng điều khiển')</h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-secondary small">Tài khoản: <strong class="text-dark">{{ $_SESSION['user']['name'] ?? 'Admin' }}</strong></span>
                        @if(($_SESSION['user']['role'] ?? '') === 'admin')
                            <span class="badge bg-danger ms-2 px-2 py-1"><i class="bi bi-shield-lock-fill me-1"></i> Quản Trị Viên</span>
                        @else
                            <span class="badge bg-info ms-2 px-2 py-1"><i class="bi bi-eye-fill me-1"></i> Khách Hàng (Chỉ Xem)</span>
                        @endif
                    </div>
                </div>

                <div class="p-4">
                    @if(($_SESSION['user']['role'] ?? '') !== 'admin')
                        <div class="alert alert-warning py-2 px-3 small border mb-4 d-flex align-items-center shadow-sm">
                            <i class="bi bi-shield-exclamation fs-5 me-2 text-warning"></i>
                            <div>
                                <strong>Chế độ chỉ xem (Read-only):</strong> Tài khoản của bạn đang có quyền <u>Khách Hàng (User)</u>. Bạn có thể xem dữ liệu hệ thống nhưng không thể Thêm, Sửa hoặc Xóa.
                            </div>
                        </div>
                    @endif
                    @if(!empty($message))
                        <div class="alert alert-success alert-dismissible fade show border shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i> <strong>Thành công:</strong> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(!empty($error))
                        <div class="alert alert-danger alert-dismissible fade show border shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> <strong>Lưu ý:</strong>
                            @if(is_array($error))
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach($error as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $error }}
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
