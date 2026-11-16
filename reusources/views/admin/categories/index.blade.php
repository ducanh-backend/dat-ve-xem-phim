@extends('admin.layout.app')

@section('title', 'Quản Lý Thể Loại - Admin Ticket Box')
@section('page_title', 'DANH SÁCH THỂ LOẠI PHIM')

@section('content')
<div class="card card-custom p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h5 class="text-dark mb-1 fw-bold">Tất Cả Thể Loại Phim</h5>
            <span class="text-secondary small">Tổng cộng: <strong class="text-primary">{{ count($categories) }}</strong> thể loại</span>
        </div>
        @if(($_SESSION['user']['role'] ?? '') === 'admin')
            <a href="/ASSGD1/admin/categories/create" class="btn btn-warning fw-bold text-white shadow-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> Thêm Thể Loại Mới
            </a>
        @else
            <span class="badge bg-secondary p-2"><i class="bi bi-eye-fill me-1"></i> Quyền Xem Dữ Liệu</span>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-dark-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Tên Thể Loại</th>
                    <th>Slug Đường Dẫn</th>
                    <th>Mô Tả Chi Tiết</th>
                    <th style="width: 180px;" class="text-center">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($categories))
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            Chưa có thể loại nào được tạo. Hãy nhấn "Thêm Thể Loại Mới" ở trên!
                        </td>
                    </tr>
                @else
                    @foreach($categories as $cat)
                        <tr>
                            <td class="text-secondary fw-semibold">#{{ $cat->id }}</td>
                            <td class="fw-bold text-dark fs-6">{{ $cat->name }}</td>
                            <td><span class="badge bg-secondary">{{ $cat->slug }}</span></td>
                            <td class="text-secondary small">{{ $cat->description ?? 'Chưa có mô tả' }}</td>
                            <td class="text-center">
                                @if(($_SESSION['user']['role'] ?? '') === 'admin')
                                    <a href="/ASSGD1/admin/categories/edit/{{ $cat->id }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square me-1"></i> Sửa
                                    </a>
                                    <a href="/ASSGD1/admin/categories/delete/{{ $cat->id }}" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa thể loại [{{ $cat->name }}]?')">
                                        <i class="bi bi-trash me-1"></i> Xóa
                                    </a>
                                @else
                                    <span class="text-muted small"><i class="bi bi-lock-fill me-1"></i> Chỉ xem</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
