@extends('layout.app')

@section('title', 'Đăng Ký Tài Khoản - Ticket Box')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-6 col-lg-5">
        <div class="card card-custom p-4 shadow-sm">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle bg-light border text-primary mb-3 fs-3">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">ĐĂNG KÝ</h3>
                    <p class="text-secondary small">Tạo tài khoản thành viên để đặt vé và nhận ưu đãi độc quyền</p>
                </div>

                @if(!empty($error))
                    <div class="alert alert-danger py-2 small border-0 d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                        <div>{{ $error }}</div>
                    </div>
                @endif

                @if(!empty($message))
                    <div class="alert alert-success py-2 small border-0 d-flex align-items-center mb-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-6"></i>
                        <div>{{ $message }}</div>
                    </div>
                @endif

                <form action="/ASSGD1/register" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1 text-primary"></i> Họ và tên <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="Ví dụ: Nguyễn Văn A" required value="{{ $old['name'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1 text-primary"></i> Email đăng nhập <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="user@gmail.com" required value="{{ $old['email'] ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">
                            <i class="bi bi-telephone me-1 text-primary"></i> Số điện thoại
                        </label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               placeholder="0912345678" value="{{ $old['phone'] ?? '' }}">
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1 text-primary"></i> Mật khẩu <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Tối thiểu 6 ký tự" required>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">
                                <i class="bi bi-shield-check me-1 text-primary"></i> Xác nhận <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                   placeholder="Nhập lại mật khẩu" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold py-2 mb-3 text-white shadow-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> ĐĂNG KÝ NGAY
                    </button>
                </form>

                <div class="text-center mt-3 pt-3 border-top">
                    <span class="text-secondary small">Bạn đã có tài khoản? </span>
                    <a href="/ASSGD1/login" class="text-primary fw-bold text-decoration-none small">
                        Đăng nhập ngay <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
