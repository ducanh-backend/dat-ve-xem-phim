@extends('layout.app')

@section('title', 'Tất Cả Phim Đang Chiếu - Ticket Box')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
    <div>
        <h2 class="fw-bold mb-1 text-dark d-flex align-items-center">
            <i class="bi bi-collection-play-fill text-primary me-2"></i>
            TẤT CẢ PHIM ĐANG CHIẾU
        </h2>
        <span class="text-secondary small">Chọn phim để đặt vé và xem thông tin chi tiết</span>
    </div>
    <span class="badge bg-light text-secondary border px-3 py-2 fs-6">
        Tổng số: <strong class="text-primary">{{ count($movies) }}</strong> bộ phim
    </span>
</div>

<div class="row">
    <!-- Sidebar Bộ lọc -->
    <div class="col-md-3 mb-4">
        <div class="filter-box">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="bi bi-funnel-fill text-primary me-2"></i> Lọc Thể Loại
            </h5>
            @if(empty($categories))
                <p class="text-muted small mb-0">Không có thể loại nào.</p>
            @else
                <div class="d-flex flex-column gap-2">
                    @foreach($categories as $cat)
                        <div class="form-check p-2 rounded bg-light border">
                            <input class="form-check-input filter-checkbox ms-0 me-2" type="checkbox" value="{{ $cat->slug ?? $cat->id }}" id="filter{{ $cat->id }}" checked>
                            <label class="form-check-label fw-semibold text-dark cursor-pointer" for="filter{{ $cat->id }}">
                                {{ $cat->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Danh sách phim -->
    <div class="col-md-9">
        <div class="row row-cols-1 row-cols-md-3 g-4" id="movie-list">
            @if(empty($movies))
                <div class="col-12 text-center text-secondary py-5">
                    <i class="bi bi-film fs-1 text-muted d-block mb-3"></i>
                    <h5>Hiện chưa có bộ phim nào trong rạp.</h5>
                    <p class="text-muted">Vui lòng quay lại sau hoặc liên hệ quản trị viên.</p>
                </div>
            @else
                @foreach($movies as $movie)
                    @php
                        $posterUrl = poster_url($movie->poster);
                    @endphp
                    <div class="col movie-item" data-category="{{ $movie->category_slug ?? ($movie->category_id ?? '') }}">
                        <div class="card h-100 movie-card">
                            <div class="position-relative overflow-hidden">
                                <img src="{{ $posterUrl }}" class="card-img-top" alt="{{ $movie->title }}">
                                <span class="badge bg-primary position-absolute top-0 end-0 m-3 shadow-sm">
                                    {{ $movie->duration ?? 120 }}'
                                </span>
                            </div>
                            <div class="card-body">
                                <div>
                                    <h6 class="card-title text-truncate fw-bold" title="{{ $movie->title }}">{{ $movie->title }}</h6>
                                    <p class="card-text">
                                        <span class="badge bg-light text-secondary border">
                                            <i class="bi bi-tag-fill text-primary me-1 small"></i>{{ $movie->category_name ?? 'Phim chiếu rạp' }}
                                        </span>
                                    </p>
                                </div>
                                <a href="/ASSGD1/movie/{{ $movie->id }}" class="btn btn-outline-warning w-100">
                                    <i class="bi bi-ticket-perforated me-1"></i> Chi Tiết / Đặt Vé
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
    // Xử lý Lọc phim Live (Live Filter)
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll('.filter-checkbox');
        const movies = document.querySelectorAll('.movie-item');

        checkboxes.forEach(chk => {
            chk.addEventListener('change', function() {
                let checkedCategories = Array.from(checkboxes).filter(i => i.checked).map(i => i.value);
                
                movies.forEach(movie => {
                    let movieCategories = (movie.getAttribute('data-category') || '').split(' ');
                    let isVisible = movieCategories.some(cat => checkedCategories.includes(cat));
                    
                    if (isVisible) {
                        movie.style.display = 'block';
                    } else {
                        movie.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endsection
