@extends('layout.app')

@section('title', ($movie->title ?? 'Chi Tiết Phim') . ' - Ticket Box')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/ASSGD1/">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="/ASSGD1/movies">Phim</a></li>
    <li class="breadcrumb-item active text-secondary" aria-current="page">{{ $movie->title ?? 'Chi tiết phim' }}</li>
  </ol>
</nav>

@if(empty($movie))
    <div class="alert alert-danger text-center my-5 border-0">
        <h4>Không tìm thấy thông tin phim này!</h4>
        <a href="/ASSGD1/movies" class="btn btn-warning mt-3">Quay lại danh sách phim</a>
    </div>
@else
    @php
        $posterUrl = poster_url($movie->poster);
    @endphp
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card card-custom p-2 overflow-hidden shadow-sm">
                <img src="{{ $posterUrl }}" class="img-fluid rounded-3 w-100" style="max-height: 520px; object-fit: cover;" alt="{{ $movie->title }}">
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-custom p-4 h-100">
                <h1 class="fw-bold mb-3 text-dark">{{ $movie->title }}</h1>
                
                <div class="d-flex flex-wrap gap-3 mb-4 p-3 rounded-3 bg-light border">
                    <div>
                        <span class="text-secondary small d-block">Đạo diễn</span>
                        <strong class="text-dark">{{ $movie->director ?? 'Đang cập nhật' }}</strong>
                    </div>
                    <div class="border-start ps-3">
                        <span class="text-secondary small d-block">Thời lượng</span>
                        <strong class="text-dark">{{ $movie->duration }} Phút</strong>
                    </div>
                    <div class="border-start ps-3">
                        <span class="text-secondary small d-block">Khởi chiếu</span>
                        <strong class="text-dark">{{ $movie->release_date ?? 'Đang chiếu' }}</strong>
                    </div>
                    <div class="border-start ps-3">
                        <span class="text-secondary small d-block">Trạng thái</span>
                        <span class="badge {{ $movie->status == 'dang_chieu' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $movie->status == 'dang_chieu' ? 'Đang chiếu' : 'Sắp chiếu' }}
                        </span>
                    </div>
                </div>
                
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Nội dung phim</h5>
                <p class="text-secondary" style="line-height: 1.8; font-size: 1.05rem;">
                    {{ $movie->description ?? 'Đang cập nhật nội dung...' }}
                </p>

                @if(!empty($movie->trailer_url))
                    <h5 class="fw-bold text-dark border-bottom pb-2 mt-4 mb-3">Trailer chính thức</h5>
                    <div class="ratio ratio-16x9 mb-4 rounded-3 overflow-hidden border shadow-sm">
                        <iframe src="{{ $movie->trailer_url }}" title="YouTube video" allowfullscreen></iframe>
                    </div>
                @endif

                <div class="mt-auto pt-3">
                    <a href="/ASSGD1/booking/seat?movie_id={{ $movie->id }}" class="btn btn-warning btn-lg fw-bold w-100 py-3 fs-5 shadow text-white">
                        <i class="bi bi-ticket-detailed-fill me-2"></i> MUA VÉ NGAY
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
