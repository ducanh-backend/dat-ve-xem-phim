@extends('layout.app')

@section('title', 'Thông Tin Tài Khoản - Ticket Box')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="/ASSGD1/">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quản lý tài khoản</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-1 text-dark d-flex align-items-center">
            <i class="bi bi-person-badge-fill text-primary me-2"></i>
            QUẢN LÝ TÀI KHOẢN
        </h2>
        <p class="text-secondary small">Xem, cập nhật thông tin cá nhân và quản lý mật khẩu tài khoản của bạn</p>
    </div>
</div>

@if(!empty($error))
    <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
        <div>{{ $error }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(!empty($success))
    <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
        <div>{{ $success }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Cột bên trái: Thẻ tóm tắt thông tin cá nhân -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 shadow-sm text-center h-100">
            <div class="card-body d-flex flex-column align-items-center">
                <!-- Avatar đại diện -->
                <div class="position-relative mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 100px; height: 100px; background: linear-gradient(135deg, #e0e7ff, #ede9fe); border: 3px solid #818cf8; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.15);">
                        <i class="bi bi-person-fill text-primary" style="font-size: 3.5rem;"></i>
                    </div>
                </div>

                <h4 class="text-dark fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-secondary small mb-2">{{ $user->email }}</p>

                <div class="mb-3">
                    @if($user->role === 'admin')
                        <span class="badge bg-danger px-3 py-2">
                            <i class="bi bi-shield-lock-fill me-1"></i> Quản Trị Viên (Admin)
                        </span>
                    @else
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-person-check-fill me-1"></i> Khách Hàng Thân Thiết
                        </span>
                    @endif
                </div>

                <div class="w-100 text-start p-3 rounded-3 my-3 bg-light border">
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small">
                        <span class="text-secondary">Mã tài khoản:</span>
                        <strong class="text-primary">#USER-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small">
                        <span class="text-secondary">Số điện thoại:</span>
                        <span class="text-dark fw-medium">{{ !empty($user->phone) ? $user->phone : 'Chưa cập nhật' }}</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-secondary">Trạng thái:</span>
                        <span class="text-success fw-bold"><i class="bi bi-circle-fill me-1 small"></i> Đang hoạt động</span>
                    </div>
                </div>

                <!-- Phím tắt nhanh -->
                <div class="w-100 mt-auto pt-2">
                    <a href="/ASSGD1/booking/history" class="btn btn-outline-warning w-100 mb-2 py-2 small fw-semibold">
                        <i class="bi bi-ticket-perforated me-1"></i> Xem vé đã đặt của tôi
                    </a>
                    @if($user->role === 'admin')
                        <a href="/ASSGD1/admin/movies" class="btn btn-outline-info w-100 mb-2 py-2 small fw-semibold">
                            <i class="bi bi-gear-fill me-1"></i> Vào trang Quản trị
                        </a>
                    @endif
                    <a href="/ASSGD1/logout" class="btn btn-outline-danger w-100 py-2 small fw-semibold">
                        <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột bên phải: Tách riêng 2 Tab Cập nhật thông tin & Đổi mật khẩu -->
    <div class="col-lg-8">
        <div class="card card-custom shadow-sm">
            <!-- Nav Tabs tách riêng 2 chức năng -->
            <div class="card-header bg-transparent border-bottom p-0">
                <ul class="nav nav-tabs border-0 px-3 pt-3" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ ($activeTab ?? 'profile') === 'profile' ? 'active text-primary fw-bold' : 'text-secondary' }}" 
                                id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab"
                                style="border: none; border-bottom: 3px solid {{ ($activeTab ?? 'profile') === 'profile' ? '#4f46e5' : 'transparent' }}; background: transparent; padding: 12px 20px;">
                            <i class="bi bi-person-lines-fill me-1"></i> 1. Cập nhật thông tin cá nhân
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ ($activeTab ?? 'profile') === 'password' ? 'active text-primary fw-bold' : 'text-secondary' }}" 
                                id="password-tab" data-bs-toggle="tab" data-bs-target="#password-pane" type="button" role="tab"
                                style="border: none; border-bottom: 3px solid {{ ($activeTab ?? 'profile') === 'password' ? '#4f46e5' : 'transparent' }}; background: transparent; padding: 12px 20px;">
                            <i class="bi bi-shield-lock-fill me-1"></i> 2. Đổi mật khẩu tài khoản
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content" id="profileTabsContent">
                    
                    <!-- TAB 1: CẬP NHẬT THÔNG TIN TÀI KHOẢN -->
                    <div class="tab-pane fade {{ ($activeTab ?? 'profile') === 'profile' ? 'show active' : '' }}" id="profile-pane" role="tabpanel">
                        <div class="mb-4">
                            <h5 class="text-dark fw-bold mb-1">CẬP NHẬT THÔNG TIN CÁ NHÂN</h5>
                            <p class="text-secondary small">Thay đổi thông tin liên lạc để nhận vé và thông báo lịch chiếu chính xác</p>
                        </div>

                        <form action="/ASSGD1/profile/update" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label text-secondary">
                                    <i class="bi bi-envelope me-1 text-primary"></i> Địa chỉ Email (Tài khoản)
                                </label>
                                <input type="email" class="form-control bg-light" id="email" value="{{ $user->email }}" disabled 
                                       style="cursor: not-allowed;">
                                <div class="form-text text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Email là định danh tài khoản duy nhất và không thể thay đổi.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person me-1 text-primary"></i> Họ và tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ $user->name }}" required placeholder="Nhập họ và tên của bạn">
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone me-1 text-primary"></i> Số điện thoại
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="{{ $user->phone ?? '' }}" placeholder="Ví dụ: 0912345678">
                                <div class="form-text text-muted small">Số điện thoại dùng để nhận tin nhắn mã vé xem phim khi đặt vé tại quầy.</div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning fw-bold px-4 py-2 text-white shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> LƯU THAY ĐỔI
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: ĐỔI MẬT KHẨU -->
                    <div class="tab-pane fade {{ ($activeTab ?? 'profile') === 'password' ? 'show active' : '' }}" id="password-pane" role="tabpanel">
                        <div class="mb-4">
                            <h5 class="text-dark fw-bold mb-1">ĐỔI MẬT KHẨU TÀI KHOẢN</h5>
                            <p class="text-secondary small">Vui lòng xác thực mật khẩu hiện tại trước khi tạo mới để đảm bảo an toàn</p>
                        </div>

                        <form action="/ASSGD1/profile/change-password" method="POST">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    <i class="bi bi-key me-1 text-primary"></i> Mật khẩu hiện tại <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control" id="current_password" name="current_password" 
                                       placeholder="••••••••" required>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="new_password" class="form-label">
                                        <i class="bi bi-lock me-1 text-primary"></i> Mật khẩu mới <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" 
                                           placeholder="Tối thiểu 6 ký tự" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label">
                                        <i class="bi bi-shield-check me-1 text-primary"></i> Xác nhận mật khẩu mới <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Nhập lại mật khẩu mới" required>
                                </div>
                            </div>

                            <div class="alert alert-secondary py-2 px-3 small rounded-3 mb-4 border bg-light text-secondary">
                                <i class="bi bi-shield-lock text-primary me-1"></i> <strong>Lưu ý bảo mật:</strong> Mật khẩu mới phải có tối thiểu 6 ký tự. Sau khi đổi mật khẩu thành công, bạn vẫn giữ trạng thái đăng nhập trên thiết bị này.
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning fw-bold px-4 py-2 text-white shadow-sm">
                                    <i class="bi bi-shield-lock-fill me-1"></i> CẬP NHẬT MẬT KHẨU
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('#profileTabs button').forEach(button => {
        button.addEventListener('click', function () {
            document.querySelectorAll('#profileTabs button').forEach(btn => {
                btn.classList.remove('text-primary', 'fw-bold');
                btn.classList.add('text-secondary');
                btn.style.borderBottom = '3px solid transparent';
            });
            this.classList.remove('text-secondary');
            this.classList.add('text-primary', 'fw-bold');
            this.style.borderBottom = '3px solid #4f46e5';
        });
    });
</script>
@endsection
