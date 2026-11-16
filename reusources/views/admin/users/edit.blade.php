@extends('admin.layout.app')

@section('title', 'Chỉnh Sửa & Phân Quyền Người Dùng - Admin Ticket Box')
@section('page_title', 'CHỈNH SỬA & PHÂN QUYỀN NGƯỜI DÙNG')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="text-dark mb-1 fw-bold">Chỉnh sửa tài khoản: {{ $user->name }}</h5>
                    <p class="text-secondary small mb-0">Cập nhật thông tin cá nhân và thay đổi phân quyền hệ thống</p>
                </div>
                <a href="/ASSGD1/admin/users" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>

            <form action="/ASSGD1/admin/users/edit/{{ $user->id }}" method="POST">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control {{ !empty($error['name']) ? 'is-invalid' : '' }}" 
                               value="{{ $posts['name'] ?? $user->name }}" required>
                        @if(!empty($error['name']))
                            <div class="invalid-feedback">{{ $error['name'] }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Tài Khoản <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control {{ !empty($error['email']) ? 'is-invalid' : '' }}" 
                               value="{{ $posts['email'] ?? $user->email }}" required>
                        @if(!empty($error['email']))
                            <div class="invalid-feedback">{{ $error['email'] }}</div>
                        @endif
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Số Điện Thoại</label>
                        <input type="tel" name="phone" class="form-control {{ !empty($error['phone']) ? 'is-invalid' : '' }}" 
                               placeholder="0912345678" value="{{ $posts['phone'] ?? $user->phone }}">
                        @if(!empty($error['phone']))
                            <div class="invalid-feedback">{{ $error['phone'] }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mật Khẩu Mới (Để trống nếu giữ nguyên)</label>
                        <input type="password" name="password" class="form-control {{ !empty($error['password']) ? 'is-invalid' : '' }}" 
                               placeholder="••••••••">
                        @if(!empty($error['password']))
                            <div class="invalid-feedback">{{ $error['password'] }}</div>
                        @endif
                        <div class="form-text text-muted small mt-1">Chỉ điền khi muốn đặt lại mật khẩu cho người dùng này.</div>
                    </div>
                </div>

                <!-- Phân Quyền Vai Trò -->
                <div class="mb-4 p-3 rounded-3 bg-light border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold text-dark mb-0">
                            <i class="bi bi-shield-shaded me-1 text-primary"></i> Phân Quyền Vai Trò (Role) <span class="text-danger">*</span>
                        </label>
                        @if($user->id == $_SESSION['user']['id'])
                            <span class="badge bg-warning text-dark small">Tài khoản của bạn (Không thể tự hạ quyền)</span>
                        @endif
                    </div>

                    @if(!empty($error['role']))
                        <div class="alert alert-danger py-1 px-2 small mb-3">{{ $error['role'] }}</div>
                    @endif

                    @php
                        $currentRole = $_POST['role'] ?? $user->role;
                    @endphp

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check p-3 rounded-3 bg-white border">
                                <input class="form-check-input" type="radio" name="role" id="role_user" value="user" 
                                       {{ $currentRole === 'user' ? 'checked' : '' }}
                                       {{ $user->id == $_SESSION['user']['id'] ? 'disabled' : '' }}>
                                <label class="form-check-label d-block ms-2 cursor-pointer" for="role_user">
                                    <strong class="text-success"><i class="bi bi-person-check me-1"></i> Khách Hàng (User)</strong>
                                    <div class="text-secondary small mt-1">Được xem phim, đặt vé, quản lý vé và thông tin cá nhân.</div>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check p-3 rounded-3 bg-white border">
                                <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin" 
                                       {{ $currentRole === 'admin' ? 'checked' : '' }}
                                       {{ $user->id == $_SESSION['user']['id'] ? 'disabled' : '' }}>
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
                        <i class="bi bi-check2-circle me-1"></i> Cập Nhật Tài Khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
