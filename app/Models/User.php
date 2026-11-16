<?php

namespace App\Models;

class User extends BaseModel {
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role'
    ];

    // Tìm user theo email để phục vụ đăng nhập
    public static function findByEmail($email) {
        $model = new static();
        $sql = "SELECT * FROM {$model->table} WHERE email = :email LIMIT 1";
        $stmt = $model->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $results = $stmt->fetchAll(\PDO::FETCH_CLASS);
        return !empty($results) ? $results[0] : null;
    }
}
