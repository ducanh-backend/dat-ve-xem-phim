@extends('admin.layout.app')

@section('title', 'Quản Lý Người Dùng & Phân Quyền - Admin Ticket Box')
@section('page_title', 'QUẢN LÝ NGƯỜI DÙNG & PHÂN QUYỀN')

@section('content')
<div class="card card-custom p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h5 class="text-dark mb-1 fw-bold">Danh Sách Thành Viên & Quản Trị</h5>
            <span class="text-secondary small">Tổng cộng: <strong class="text-primary">{{ count($users) }}</strong> tài khoản</span>
        </div>
        @if(($_SESSION['user']['role'] ?? '') === 'admin')
            <a href="/ASSGD1/admin/users/create" class="btn btn-warning fw-bold text-white shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Thêm Người Dùng Mới
            </a>
        @else
            <span class="badge bg-secondary p-2"><i class="bi bi-eye-fill me-1"></i> Quyền Xem Dữ Liệu</span>
        @endif
    </div>

    @if(!empty($error))
        <div class="alert alert-danger alert-dismissible fade show border shadow-sm mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-dark-custom table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 80px;">Mã</th>
                    <th>Họ và Tên</th>
                    <th>Email Tài Khoản</th>
                    <th>Số Điện Thoại</th>
                    <th style="width: 170px;" class="text-center">Vai Trò / Phân Quyền</th>
                    <th style="width: 180px;" class="text-center">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @if(empty($users))
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-5">
                            <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                            Chưa có người dùng nào trên hệ thống.
                        </td>
                    </tr>
                @else
                    @foreach($users as $u)
                        <tr>
                            <td class="text-secondary fw-semibold">#{{ str_pad($u->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2 bg-light border" 
                                         style="width: 38px; height: 38px;">
                                        <i class="bi bi-person-fill {{ $u->role === 'admin' ? 'text-danger' : 'text-primary' }}"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark fs-6">{{ $u->name }}</span>
                                        @if($u->id == $_SESSION['user']['id'])
                                            <span class="badge bg-primary text-white ms-1 py-0 px-1 small" style="font-size: 0.7rem;">Bạn</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary">{{ $u->email }}</span>
                            </td>
                            <td>
                                <span class="text-secondary">{{ !empty($u->phone) ? $u->phone : '—' }}</span>
                            </td>
                            <td class="text-center">
                                @if($u->role === 'admin')
                                    <span class="badge bg-danger py-1 px-2">
                                        <i class="bi bi-shield-lock-fill me-1"></i> Quản Trị Viên
                                    </span>
                                @else
                                    <span class="badge bg-success py-1 px-2">
                                        <i class="bi bi-person-check-fill me-1"></i> Khách Hàng
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($_SESSION['user']['role'] ?? '') === 'admin')
                                    <a href="/ASSGD1/admin/users/edit/{{ $u->id }}" class="btn btn-sm btn-outline-warning me-1" title="Chỉnh sửa & Phân quyền">
                                        <i class="bi bi-shield-check me-1"></i> Sửa / Quyền
                                    </a>
                                    @if($u->id == $_SESSION['user']['id'])
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Không thể xóa tài khoản của chính bạn">
                                            <i class="bi bi-lock-fill"></i>
                                        </button>
                                    @else
                                        <a href="/ASSGD1/admin/users/delete/{{ $u->id }}" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản [{{ $u->name }}] không?')" title="Xóa tài khoản">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    @endif
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
