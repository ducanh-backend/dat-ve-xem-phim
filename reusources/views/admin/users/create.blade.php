@extends('admin.layout.app')

@section('title', 'Thêm Mới Người Dùng - Admin Ticket Box')
@section('page_title', 'THÊM MỚI NGƯỜI DÙNG & PHÂN QUYỀN')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="text-dark mb-1 fw-bold">Thông tin tài khoản mới</h5>
                    <p class="text-secondary small mb-0">Tạo tài khoản thành viên hoặc cấp quyền Quản trị viên mới</p>
                </div>
                <a href="/ASSGD1/admin/users" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>

            <form action="/ASSGD1/admin/users/store" method="POST">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control {{ !empty($error['name']) ? 'is-invalid' : '' }}" 
                               placeholder="Ví dụ: Nguyễn Văn A" value="{{ $posts['name'] ?? '' }}" required>
                        @if(!empty($error['name']))
                            <div class="invalid-feedback">{{ $error['name'] }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Tài Khoản <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control {{ !empty($error['email']) ? 'is-invalid' : '' }}" 
                               placeholder="user@gmail.com" value="{{ $posts['email'] ?? '' }}" required>
                        @if(!empty($error['email']))
                            <div class="invalid-feedback">{{ $error['email'] }}</div>
                        @endif
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Số Điện Thoại</label>
                        <input type="tel" name="phone" class="form-control {{ !empty($error['phone']) ? 'is-invalid' : '' }}" 
                               placeholder="0912345678" value="{{ $posts['phone'] ?? '' }}">
                        @if(!empty($error['phone']))
                            <div class="invalid-feedback">{{ $error['phone'] }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mật Khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control {{ !empty($error['password']) ? 'is-invalid' : '' }}" 
                               placeholder="Tối thiểu 6 ký tự" required>
                        @if(!empty($error['password']))
                            <div class="invalid-feedback">{{ $error['password'] }}</div>
                        @endif
                    </div>
                </div>

                <div class="mb-4 p-3 rounded-3 bg-light border">
                    <label class="form-label fw-bold text-dark mb-2">
                        <i class="bi bi-shield-shaded me-1 text-primary"></i> Phân Quyền Vai Trò (Role) <span class="text-danger">*</span>
                    </label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check p-3 rounded-3 bg-white border">
                                <input class="form-check-input" type="radio" name="role" id="role_user" value="user" 
                                       {{ ($posts['role'] ?? 'user') === 'user' ? 'checked' : '' }}>
                                <label class="form-check-label d-block ms-2 cursor-pointer" for="role_user">
                                    <strong class="text-success"><i class="bi bi-person-check me-1"></i> Khách Hàng (User)</strong>
                                    <div class="text-secondary small mt-1">Được xem phim, đặt vé, quản lý vé và thông tin cá nhân.</div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check p-3 rounded-3 bg-white border">
                                <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin"
                                       {{ ($posts['role'] ?? '') === 'admin' ? 'checked' : '' }}>
                                <label class="form-check-label d-block ms-2 cursor-pointer" for="role_admin">
                                    <strong class="text-danger"><i class="bi bi-shield-lock me-1"></i> Quản Trị Viên (Admin)</strong>
                                    <div class="text-secondary small mt-1">Toàn quyền quản lý phim, thể loại, phân quyền người dùng.</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="/ASSGD1/admin/users" class="btn btn-outline-secondary px-3">Hủy bỏ</a>
                    <button type="submit" class="btn btn-warning fw-bold px-4 text-white shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Lưu Tài Khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
