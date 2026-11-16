@extends('layout.app')

@section('title', 'Đặt Vé Thành Công - Ticket Box')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-10 text-success mb-3 fs-1 shadow-sm border border-success border-opacity-25">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h2 class="fw-bold mb-1 text-dark">ĐẶT VÉ THÀNH CÔNG!</h2>
            <p class="text-secondary small">Cảm ơn bạn đã lựa chọn dịch vụ của VNBox. Vé điện tử của bạn đã sẵn sàng.</p>
        </div>

        <!-- Chi tiết vé hiển thị ra -->
        <div class="card card-custom shadow-sm overflow-hidden" style="border: 1.5px solid #c7d2fe !important;">
            <div class="card-header bg-primary text-white fw-bold text-center py-3 fs-5" style="background: var(--brand-gradient) !important;">
                MÃ VÉ: TKB-9823471
            </div>
            <div class="card-body p-4 text-center">
                <!-- Mã QR -->
                <div class="d-inline-block p-3 bg-white rounded-3 shadow-sm border mb-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=TKB-9823471&bgcolor=ffffff&color=000000" alt="QR Code" style="width: 160px; height: 160px;">
                </div>
                
                <h4 class="card-title fw-bold text-dark mb-3">SIÊU ANH HÙNG TRỞ LẠI</h4>
                
                <div class="p-3 rounded-3 mb-3 text-start bg-light border" style="font-size: 0.95rem;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Rạp chiếu:</span>
                        <strong class="text-dark">Ticket Box - Quận 1</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Suất chiếu:</span>
                        <strong class="text-primary">19:30 | Hôm nay</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Phòng chiếu:</span>
                        <strong class="text-dark">Cinema VIP 03</strong>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2">
                        <span class="text-secondary">Ghế ngồi:</span>
                        <strong class="text-primary fs-5" id="success-seats">Đang tải...</strong>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light border border-primary border-opacity-25">
                    <span class="text-dark fw-bold">TỔNG ĐÃ THANH TOÁN:</span>
                    <h4 class="text-primary fw-bold mb-0"><span id="success-total">0</span> đ</h4>
                </div>
            </div>
            <div class="card-footer text-secondary text-center py-2 small bg-light border-top">
                <i class="bi bi-info-circle me-1"></i> Vui lòng xuất trình mã QR này tại quầy vé trước giờ chiếu 15 phút.
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="/ASSGD1/" class="btn btn-outline-secondary px-4">
                <i class="bi bi-house-door me-1"></i> Về Trang Chủ
            </a>
            <a href="/ASSGD1/booking/history" class="btn btn-warning fw-bold px-4 shadow text-white">
                <i class="bi bi-ticket-perforated me-1"></i> Xem Vé Của Tôi
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let seatsData = JSON.parse(localStorage.getItem('ticketbox_seats')) || [];

        // Hiển thị thông tin vé
        let seatNames = seatsData.map(s => s.id).join(', ') || 'Không có ghế';
        document.getElementById('success-seats').innerText = seatNames;

        let total = seatsData.reduce((sum, s) => sum + s.price, 0);
        document.getElementById('success-total').innerText = total.toLocaleString('vi-VN');

        // Lưu đơn vào lịch sử nếu có ghế
        if (seatsData.length > 0) {
            let orders = JSON.parse(localStorage.getItem('ticketbox_orders')) || [];

            let newOrder = {
                id: 'TKB-' + Math.floor(Math.random() * 9000000 + 1000000),
                movie: 'SIÊU ANH HÙNG TRỞ LẠI',
                cinema: 'Ticket Box - Quận 1',
                showtime: '19:30',
                date: new Date().toLocaleDateString('vi-VN'),
                room: 'Cinema 03',
                seats: seatsData,
                total: total,
                bookedAt: Date.now()
            };

            orders.unshift(newOrder);
            localStorage.setItem('ticketbox_orders', JSON.stringify(orders));
            localStorage.removeItem('ticketbox_seats');

            document.querySelector('.card-header').innerText = 'MÃ VÉ: ' + newOrder.id;
            let qrImg = document.querySelector('img[alt="QR Code"]');
            if (qrImg) {
                qrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + newOrder.id + '&bgcolor=ffffff&color=000000';
            }
        }
    });
</script>
@endsection
