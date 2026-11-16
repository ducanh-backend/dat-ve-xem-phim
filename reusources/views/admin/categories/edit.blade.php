@extends('admin.layout.app')

@section('title', 'Cập Nhật Thể Loại - Admin Ticket Box')
@section('page_title', 'CẬP NHẬT THỂ LOẠI')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-custom p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="text-dark mb-0 fw-bold">Chỉnh sửa thể loại: {{ $category->name }}</h5>
                <a href="/ASSGD1/admin/categories" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>

            <form action="/ASSGD1/admin/categories/edit/{{ $category->id }}" method="POST">
                <div class="mb-3">
                    <label class="form-label">Tên Thể Loại <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control {{ !empty($error['name']) ? 'is-invalid' : '' }}" 
                           value="{{ $posts['name'] ?? $category->name }}" required>
                    @if(!empty($error['name']))
                        <div class="invalid-feedback">{{ $error['name'] }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Slug (Đường dẫn tĩnh)</label>
                    <input type="text" name="slug" class="form-control" 
                           value="{{ $posts['slug'] ?? $category->slug }}">
                </div>

                <div class="mb-4">
                    <label class="form-label">Mô Tả Thể Loại</label>
                    <textarea name="description" rows="3" class="form-control">{{ $posts['description'] ?? $category->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning fw-bold px-4 text-white shadow-sm">
                    <i class="bi bi-save me-1"></i> Cập Nhật Thể Loại
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
