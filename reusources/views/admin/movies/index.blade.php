@extends('admin.layout.app')

@section('title', 'Quản Lý Phim - Admin Ticket Box')
@section('page_title', 'DANH SÁCH TẤT CẢ PHIM')

@section('content')
<div class="card card-custom p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h5 class="text-dark mb-1 fw-bold">Danh Sách Phim Trong Rạp</h5>
            <span class="text-secondary small">Tổng cộng: <strong class="text-primary">{{ count($movies) }}</strong> bộ phim</span>
        </div>
        @if(($_SESSION['user']['role'] ?? '') === 'admin')
            <a href="/ASSGD1/admin/movies/create" class="btn btn-warning fw-bold text-white shadow-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> Thêm Phim Mới
            </a>
        @else
            <span class="badge bg-secondary p-2"><i class="bi bi-eye-fill me-1"></i> Quyền Xem Dữ Liệu</span>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-dark-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th style="width: 100px;">Poster</th>
                    <th>Tên Phim</th>
                    <th>Thể Loại</th>
                    <th>Thời Lượng</th>
                    <th>Trạng Thái</th>
                    <th style="width: 180px;" class="text-center">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($movies))
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            Chưa có bộ phim nào được tạo. Hãy nhấn "Thêm Phim Mới" ở trên!
                        </td>
                    </tr>
                @else
                    @foreach($movies as $movie)
                        @php
                            $posterUrl = poster_url($movie->poster);
                        @endphp
                        <tr>
                            <td class="text-secondary fw-semibold">#{{ $movie->id }}</td>
                            <td>
                                @if(!empty($movie->poster))
                                    <img src="{{ $posterUrl }}" alt="{{ $movie->title }}" class="rounded shadow-sm" style="width: 60px; height: 85px; object-fit: cover; border: 1.5px solid var(--border-color);">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center text-muted small bg-light" style="width: 60px; height: 85px; border: 1px dashed var(--border-color);">
                                        No pic
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong class="text-dark fs-6 d-block">{{ $movie->title }}</strong>
                                <span class="text-muted small">Đạo diễn: {{ $movie->director ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $movie->category_name ?? 'Chưa phân loại' }}</span>
                            </td>
                            <td class="text-dark fw-medium">{{ $movie->duration }} phút</td>
                            <td>
                                @if($movie->status === 'dang_chieu')
                                    <span class="badge bg-success">Đang chiếu</span>
                                @elseif($movie->status === 'sap_chieu')
                                    <span class="badge bg-info">Sắp chiếu</span>
                                @else
                                    <span class="badge bg-danger">Ngừng chiếu</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($_SESSION['user']['role'] ?? '') === 'admin')
                                    <a href="/ASSGD1/admin/movies/edit/{{ $movie->id }}" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil-square me-1"></i> Sửa
                                    </a>
                                    <a href="/ASSGD1/admin/movies/delete/{{ $movie->id }}" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa phim [{{ $movie->title }}]?')">
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
