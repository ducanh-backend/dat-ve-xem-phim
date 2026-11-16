@extends('admin.layout.app')

@section('title', 'Thêm Mới Thể Loại - Admin Ticket Box')
@section('page_title', 'THÊM MỚI THỂ LOẠI')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="text-dark mb-0 fw-bold">Thông tin thể loại mới</h5>
                <a href="/ASSGD1/admin/categories" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>

            <form action="/ASSGD1/admin/categories/store" method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên Thể Loại <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control {{ !empty($error['name']) ? 'is-invalid' : '' }}" 
                           placeholder="VD: Hoạt Hình, Hài Hước..." value="{{ $posts['name'] ?? '' }}" required>
                    @if(!empty($error['name']))
                        <div class="invalid-feedback">{{ $error['name'] }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug (Đường dẫn tĩnh)</label>
                    <input type="text" name="slug" class="form-control" 
                           placeholder="VD: hoat-hinh (để trống sẽ tự động tạo từ tên)" value="{{ $posts['slug'] ?? '' }}">
                    <div class="form-text text-muted small mt-1">Để trống hệ thống sẽ tự sinh slug chuẩn SEO.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mô Tả Thể Loại</label>
                    <textarea name="description" rows="3" class="form-control" 
                              placeholder="Mô tả ngắn gọn về đặc trưng thể loại...">{{ $posts['description'] ?? '' }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning fw-bold px-4 text-white shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Lưu Thể Loại
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
