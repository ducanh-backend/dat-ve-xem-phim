@extends('admin.layout.app')

@section('title', 'Thêm Phim Mới - Admin Ticket Box')
@section('page_title', 'THÊM PHIM MỚI VÀO RẠP')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-custom p-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="text-dark mb-0 fw-bold">Nhập thông tin bộ phim</h5>
                <a href="/ASSGD1/admin/movies" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>

            @if(!empty($error['general']))
                <div class="alert alert-danger mb-3">{{ $error['general'] }}</div>
            @endif

            <form action="/ASSGD1/admin/movies/store" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Tiêu đề phim <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control {{ !empty($error['title']) ? 'is-invalid' : '' }}" 
                               placeholder="VD: Siêu Anh Hùng Trở Lại..." value="{{ $posts['title'] ?? '' }}" required>
                        @if(!empty($error['title']))
                            <div class="invalid-feedback">{{ $error['title'] }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thể loại phim <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select {{ !empty($error['category_id']) ? 'is-invalid' : '' }}" required>
                            <option value="">-- Chọn thể loại --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (isset($posts['category_id']) && $posts['category_id'] == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @if(!empty($error['category_id']))
                            <div class="invalid-feedback">{{ $error['category_id'] }}</div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Đạo diễn</label>
                        <input type="text" name="director" class="form-control" 
                               placeholder="VD: Christopher Nolan" value="{{ $posts['director'] ?? '' }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thời lượng (Phút) <span class="text-danger">*</span></label>
                        <input type="number" name="duration" class="form-control {{ !empty($error['duration']) ? 'is-invalid' : '' }}" 
                               placeholder="VD: 120" value="{{ $posts['duration'] ?? 120 }}" required min="1">
                        @if(!empty($error['duration']))
                            <div class="invalid-feedback">{{ $error['duration'] }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ngày khởi chiếu</label>
                        <input type="date" name="release_date" class="form-control" 
                               value="{{ $posts['release_date'] ?? date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Phần ảnh Poster: Linh hoạt cả Upload file lẫn Link Online -->
                <div class="card p-3 mb-3 bg-light border">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-3 mb-md-0 border-end">
                            <span class="text-secondary small d-block mb-2">Xem trước Poster:</span>
                            <div id="preview-wrapper" class="d-flex align-items-center justify-content-center rounded-3 shadow-sm" 
                                 style="width: 100px; height: 140px; margin: 0 auto; background: #ffffff; border: 1.5px dashed var(--border-color); overflow: hidden;">
                                <img id="poster-preview" src="{{ !empty($posts['poster_url']) ? poster_url($posts['poster_url']) : '' }}" 
                                     alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: {{ !empty($posts['poster_url']) ? 'block' : 'none' }};">
                                <span id="preview-placeholder" class="text-muted small" style="display: {{ !empty($posts['poster_url']) ? 'none' : 'block' }};">
                                    <i class="bi bi-image fs-3 d-block"></i> Chưa có ảnh
                                </span>
                            </div>
                        </div>

                        <div class="col-md-9 ps-md-3">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-primary"><i class="bi bi-upload me-1"></i> Cách 1: Tải file ảnh từ máy tính</label>
                                <input type="file" id="poster-file-input" name="poster" class="form-control {{ !empty($error['poster']) ? 'is-invalid' : '' }}" accept="image/*">
                                @if(!empty($error['poster']))
                                    <div class="invalid-feedback d-block">{{ $error['poster'] }}</div>
                                @endif
                                <div class="form-text text-muted small mt-1">Hỗ trợ định dạng: JPG, PNG, JPEG, WEBP, JFIF, AVIF (Tối đa 50MB).</div>
                            </div>

                            <div>
                                <label class="form-label small fw-bold text-info"><i class="bi bi-link-45deg me-1"></i> Cách 2: Hoặc dán link ảnh Online trực tiếp</label>
                                <input type="text" id="poster-url-input" name="poster_url" class="form-control" 
                                       placeholder="VD: https://images.unsplash.com/photo-..." 
                                       value="{{ $posts['poster_url'] ?? '' }}">
                                <div class="form-text text-muted small mt-1">Dán trực tiếp URL ảnh trên mạng nếu không tải file từ máy tính.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng thái chiếu</label>
                        <select name="status" class="form-select">
                            <option value="dang_chieu" {{ (isset($posts['status']) && $posts['status'] == 'dang_chieu') ? 'selected' : '' }}>Đang chiếu</option>
                            <option value="sap_chieu" {{ (isset($posts['status']) && $posts['status'] == 'sap_chieu') ? 'selected' : '' }}>Sắp chiếu</option>
                            <option value="ngung_chieu" {{ (isset($posts['status']) && $posts['status'] == 'ngung_chieu') ? 'selected' : '' }}>Ngừng chiếu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Link Trailer (YouTube Embed URL)</label>
                        <input type="text" name="trailer_url" class="form-control" 
                               placeholder="VD: https://www.youtube.com/embed/dQw4w9WgXcQ" value="{{ $posts['trailer_url'] ?? '' }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Tóm tắt nội dung phim</label>
                    <textarea name="description" rows="4" class="form-control" 
                              placeholder="Mô tả cốt truyện bộ phim...">{{ $posts['description'] ?? '' }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning fw-bold px-4 py-2 text-white shadow-sm">
                    <i class="bi bi-plus-circle-fill me-1"></i> Lưu Phim Mới
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('poster-file-input');
        const urlInput = document.getElementById('poster-url-input');
        const previewImg = document.getElementById('poster-preview');
        const placeholder = document.getElementById('preview-placeholder');

        function showImage(src) {
            if (src) {
                previewImg.src = src;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            } else {
                previewImg.src = '';
                previewImg.style.display = 'none';
                placeholder.style.display = 'block';
            }
        }

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    if (file.size > 50 * 1024 * 1024) {
                        alert('Cảnh báo: Ảnh lớn hơn 50MB có thể bị máy chủ từ chối. Hãy chọn ảnh nhẹ hơn!');
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
