@extends('admin.layout.app')

@section('title', 'Cập Nhật Phim - Admin Ticket Box')
@section('page_title', 'CẬP NHẬT THÔNG TIN PHIM')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-custom p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="text-dark mb-0 fw-bold">Chỉnh sửa phim: {{ $movie->title }}</h5>
                <a href="/ASSGD1/admin/movies" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>

            @if(!empty($error['general']))
                <div class="alert alert-danger mb-3">{{ $error['general'] }}</div>
            @endif

            <form action="/ASSGD1/admin/movies/edit/{{ $movie->id }}" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Tiêu đề phim <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control {{ !empty($error['title']) ? 'is-invalid' : '' }}" 
                               value="{{ $posts['title'] ?? $movie->title }}" required>
                        @if(!empty($error['title']))
                            <div class="invalid-feedback">{{ $error['title'] }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thể loại phim <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ ((isset($posts['category_id']) ? $posts['category_id'] : $movie->category_id) == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Đạo diễn</label>
                        <input type="text" name="director" class="form-control" 
                               value="{{ $posts['director'] ?? $movie->director }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thời lượng (Phút) <span class="text-danger">*</span></label>
                        <input type="number" name="duration" class="form-control" 
                               value="{{ $posts['duration'] ?? $movie->duration }}" required min="1">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ngày khởi chiếu</label>
                        <input type="date" name="release_date" class="form-control" 
                               value="{{ $posts['release_date'] ?? $movie->release_date }}">
                    </div>
                </div>

                <!-- Thay đổi ảnh Poster -->
                <div class="card p-3 mb-3 bg-light border">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3 mb-md-0 border-end">
                            <span class="text-secondary small d-block mb-2">Poster hiển thị:</span>
                            @php
                                $currentPoster = !empty($movie->poster) ? poster_url($movie->poster) : '';
                            @endphp
                            <div class="d-flex align-items-center justify-content-center rounded-3 shadow-sm" 
                                 style="width: 100px; height: 140px; margin: 0 auto; background: #ffffff; border: 1.5px dashed var(--border-color); overflow: hidden;">
                                <img id="edit-poster-preview" src="{{ $currentPoster }}" alt="Poster" 
                                     style="width: 100%; height: 100%; object-fit: cover; display: {{ !empty($currentPoster) ? 'block' : 'none' }};">
                                <span id="edit-preview-placeholder" class="text-muted small" style="display: {{ empty($currentPoster) ? 'block' : 'none' }};">
                                    Chưa có ảnh
                                </span>
                            </div>
                        </div>

                        <div class="col-md-9 ps-md-3">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-primary"><i class="bi bi-upload me-1"></i> Chọn file ảnh mới từ máy tính:</label>
                                <input type="file" id="edit-poster-file" name="poster" class="form-control {{ !empty($error['poster']) ? 'is-invalid' : '' }}" accept="image/*">
                                @if(!empty($error['poster']))
                                    <div class="invalid-feedback d-block">{{ $error['poster'] }}</div>
                                @endif
                                <div class="form-text text-muted small mt-1">Hỗ trợ: JPG, PNG, JPEG, WEBP, JFIF, AVIF (Tối đa 50MB).</div>
                            </div>
                            <div>
                                <label class="form-label small fw-bold text-info"><i class="bi bi-link-45deg me-1"></i> Hoặc dán link ảnh mới:</label>
                                <input type="text" id="edit-poster-url" name="poster_url" class="form-control" 
                                       placeholder="VD: https://... (để trống nếu giữ ảnh cũ hoặc đã chọn file)" 
                                       value="{{ $posts['poster_url'] ?? '' }}">
                            </div>
                            <div class="form-text text-muted small mt-1">Để trống cả hai mục trên nếu muốn tiếp tục giữ nguyên poster hiện tại.</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng thái chiếu</label>
                        <select name="status" class="form-select">
                            <option value="dang_chieu" {{ ((isset($posts['status']) ? $posts['status'] : $movie->status) == 'dang_chieu') ? 'selected' : '' }}>Đang chiếu</option>
                            <option value="sap_chieu" {{ ((isset($posts['status']) ? $posts['status'] : $movie->status) == 'sap_chieu') ? 'selected' : '' }}>Sắp chiếu</option>
                            <option value="ngung_chieu" {{ ((isset($posts['status']) ? $posts['status'] : $movie->status) == 'ngung_chieu') ? 'selected' : '' }}>Ngừng chiếu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Link Trailer (YouTube Embed URL)</label>
                        <input type="text" name="trailer_url" class="form-control" 
                               value="{{ $posts['trailer_url'] ?? $movie->trailer_url }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Nội dung tóm tắt phim</label>
                    <textarea name="description" rows="4" class="form-control">{{ $posts['description'] ?? $movie->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning fw-bold px-4 py-2 text-white shadow-sm">
                    <i class="bi bi-save-fill me-1"></i> Cập Nhật Phim
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('edit-poster-file');
        const urlInput = document.getElementById('edit-poster-url');
        const previewImg = document.getElementById('edit-poster-preview');
        const placeholder = document.getElementById('edit-preview-placeholder');

        function showImage(src) {
            if (src) {
                previewImg.src = src;
                previewImg.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            }
        }

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    if (file.size > 50 * 1024 * 1024) {
                        alert('Cảnh báo: File ảnh lớn hơn 50MB có thể bị máy chủ từ chối!');
                    }
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        showImage(e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (urlInput) {
            urlInput.addEventListener('input', function () {
                const val = this.value.trim();
                if (val && (!fileInput.files || fileInput.files.length === 0)) {
                    showImage(val);
                }
            });
        }
    });
</script>
@endsection
