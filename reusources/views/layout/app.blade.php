<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ticket Box - Đặt Vé Xem Phim Trực Tuyến')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts: Plus Jakarta Sans & Outfit (Bolder, Ultra Crisp Typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Premium Custom CSS (High Contrast, Bold Text) -->
    <link rel="stylesheet" href="/ASSGD1/public/css/style.css?v=1.1">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <!-- Navbar/Header chung -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/ASSGD1/">
                <i class="bi bi-film me-2 text-primary"></i>
                <span class="fw-bold">VNBOX</span>
                <span class="badge bg-primary text-white ms-2 px-2 py-1" style="font-size: 0.65rem; letter-spacing: 1px;">CINEMA</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="/ASSGD1/"><i class="bi bi-house-door me-1"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ASSGD1/movies"><i class="bi bi-collection-play me-1"></i> Lịch chiếu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ASSGD1/contact"><i class="bi bi-chat-dots me-1"></i> Liên hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary fw-semibold" href="/ASSGD1/booking/history">
                            <i class="bi bi-ticket-perforated me-1"></i> Vé của tôi
                        </a>
                    </li>
                    @if(isset($_SESSION['user']))
                        <li class="nav-item">
                            <a class="nav-link text-info fw-bold" href="/ASSGD1/admin/movies">
                                <i class="bi bi-gear-fill me-1"></i> Quản trị
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link fw-semibold d-inline-flex align-items-center bg-light rounded-pill px-3 py-1 border" href="/ASSGD1/profile" title="Xem thông tin tài khoản">
                                <i class="bi bi-person-circle text-primary fs-5 me-1"></i>
                                <span class="text-dark">{{ $_SESSION['user']['name'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-sm btn-outline-danger ms-1" href="/ASSGD1/logout" title="Đăng xuất">
                                <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                            </a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-secondary btn-sm fw-bold px-3 me-1" href="/ASSGD1/register">
                                <i class="bi bi-person-plus me-1"></i> Đăng ký
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm fw-bold px-3 text-white" href="/ASSGD1/login">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Nơi chứa nội dung động của từng trang -->
    <div class="container my-5">
        @yield('content')
    </div>

    <!-- Footer chung -->
    <footer class="text-center py-4 mt-5">
        <div class="container">
            <p class="mb-1 text-secondary">&copy; 2026 Ticket Box - Hệ Thống Đặt Vé Rạp Chiếu Phim Hiện Đại.</p>
            <small class="text-muted">Dự án môn PHP2 - FPT Polytechnic</small>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>