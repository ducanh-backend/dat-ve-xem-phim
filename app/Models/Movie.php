<?php

namespace App\Models;

class Movie extends BaseModel {
    protected $table = 'movies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'category_id',
        'title',
        'poster',
        'director',
        'duration',
        'release_date',
        'description',
        'trailer_url',
        'status'
    ];

    // Lấy tất cả phim kèm tên thể loại (khớp tên hàm getAllCategory trong ảnh mẫu)
    public static function getAllCategory() {
        $model = new static();
        $sql = "SELECT m.*, c.name AS category_name, c.slug AS category_slug 
                FROM {$model->table} m 
                LEFT JOIN categories c ON m.category_id = c.id 
                ORDER BY m.id DESC";
        return self::rawQuery($sql);
    }

    // Lấy phim kèm tên thể loại (JOIN)
    public static function getWithCategory() {
        return self::getAllCategory();
    }

    // Lấy phim đang chiếu
    public static function getNowShowing() {
        $model = new static();
        $sql = "SELECT m.*, c.name AS category_name 
                FROM {$model->table} m 
                LEFT JOIN categories c ON m.category_id = c.id 
                WHERE m.status = 'dang_chieu' 
                ORDER BY m.id DESC";
        return self::rawQuery($sql);
    }
}
