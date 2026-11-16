@extends('layout.app')

@section('title', 'Vé Của Tôi - Ticket Box')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
        <h2 class="fw-bold mb-1 text-dark d-flex align-items-center">
            <i class="bi bi-ticket-perforated-fill text-primary me-2"></i>
            VÉ CỦA TÔI (LỊCH SỬ ĐẶT VÉ)
        </h2>
        <span class="text-secondary small">Xem và quản lý các vé xem phim đã đặt trực tuyến</span>
    </div>
    <a href="/ASSGD1/movies" class="btn btn-outline-warning btn-sm">
        <i class="bi bi-film me-1"></i> Đặt Vé Mới
    </a>
</div>

<!-- Khu vực render dynamic -->
<div id="orders-container" class="row">
    <!-- Sẽ được điền bằng JavaScript -->
</div>

<!-- Trạng thái trống -->
<div id="no-orders" class="text-center py-5 card card-custom p-5 my-4" style="display: none;">
    <i class="bi bi-ticket-perforated text-primary mb-3" style="font-size: 4rem;"></i>
    <h4 class="text-dark fw-bold">Bạn chưa có đơn đặt vé nào</h4>
    <p class="text-secondary mb-4">Hãy chọn một bộ phim yêu thích và bắt đầu đặt vé ngay hôm nay!</p>
    <div>
        <a href="/ASSGD1/movies" class="btn btn-warning fw-bold px-5 py-2 text-white shadow">
            <i class="bi bi-play-circle-fill me-1"></i> Xem Phim Ngay
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let orders = JSON.parse(localStorage.getItem('ticketbox_orders')) || [];
        const container = document.getElementById('orders-container');
        const noOrders = document.getElementById('no-orders');

        if (orders.length === 0) {
            noOrders.style.display = 'block';
            return;
        }

        orders.forEach(function(order, index) {
            let seatNames = (order.seats || []).map(s => s.id).join(', ');
            let bookedDate = order.date || 'Hôm nay';
            let isLatest = (index === 0);
            let badgeClass = isLatest ? 'bg-success text-white' : 'bg-primary text-white';
            let badgeText = isLatest ? 'Sắp chiếu' : 'Đã xác nhận';

            let html = `
            <div class="col-md-6 mb-4">
                <div class="card card-custom h-100 shadow-sm" style="border: 1px solid ${isLatest ? '#818cf8' : 'var(--border-color)'};">
                    <div class="row g-0 h-100">
                        <div class="col-md-4">
                            <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=500&auto=format&fit=crop"
                                 class="img-fluid rounded-start h-100"
                                 style="object-fit: cover; min-height: 200px;"
                                 alt="Poster">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title text-dark fw-bold mb-0">${order.movie || 'Phim Chiếu Rạp'}</h5>
                                        <span class="badge ${badgeClass}">${badgeText}</span>
                                    </div>
                                    <p class="text-secondary small mb-3">Ngày đặt: <strong class="text-dark">${bookedDate}</strong></p>

                                    <div class="p-3 rounded-3 mb-3 bg-light border" style="font-size: 0.9rem;">
                                        <div class="mb-1 text-secondary">Mã vé: <strong class="text-primary">${order.id || '#TB' + (index + 1001)}</strong></div>
                                        <div class="mb-1 text-secondary">Suất chiếu: <strong class="text-dark">${order.showtime || '19:00'}</strong></div>
                                        <div class="text-secondary">Ghế ngồi: <strong class="text-primary">${seatNames || 'Chưa chọn'}</strong></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <div>
                                        <span class="text-secondary small d-block">Tổng tiền:</span>
                                        <strong class="text-primary fs-5">${(order.total || 75000).toLocaleString('vi-VN')} đ</strong>
                                    </div>
                                    <button class="btn btn-sm btn-outline-warning" onclick="showQR('${order.id || 'TB' + (index + 1001)}')">
                                        <i class="bi bi-qr-code me-1"></i> Mã QR
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

            container.innerHTML += html;
        });
    });

    function showQR(orderId) {
        Swal.fire({
            title: 'Mã QR Vào Rạp',
            html: `<p class="text-dark mb-2">Mã vé: <strong class="text-primary">${orderId}</strong></p>
                   <p class="text-secondary small mb-3">Xuất trình mã này tại quầy soát vé rạp Ticket Box</p>
                   <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${orderId}&bgcolor=ffffff&color=000000"
                        class="rounded p-2 bg-white shadow-sm border" style="width: 200px; height: 200px;" alt="QR Code">`,
            background: '#ffffff',
            color: '#0f172a',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'Đóng'
        });
    }
</script>
@endsection
