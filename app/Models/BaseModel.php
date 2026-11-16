<?php

namespace App\Models;

use PDO;

class BaseModel extends DB {
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = []; // Mảng tên trường của bảng

    public static function all() {
        $model = new static();
        $sql = "SELECT * FROM $model->table";
        // chuẩn bị truy vấn
        $stmt = $model->conn->prepare($sql);
        // thực thi truy vấn
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_CLASS);
        return $results;
    }

    // Lấy ra 1 bản ghi
    public static function find($id) {
        $model = new static();
        $sql = "SELECT * FROM $model->table WHERE $model->primaryKey=:$model->primaryKey";
        $stmt = $model->conn->prepare($sql);
        $stmt->execute([$model->primaryKey => $id]);
        $results = $stmt->fetchAll(PDO::FETCH_CLASS);
        return !empty($results) ? $results[0] : null;
    }

    // Thêm mới bản ghi
    public static function create($data) {
        $model = new static();
        if (!empty($model->fillable)) {
            $data = array_intersect_key($data, array_flip($model->fillable));
        }
        $keys = array_keys($data);
        $escapedFields = array_map(function($key) {
            return "`$key`";
        }, $keys);
        $fields = implode(', ', $escapedFields);
        $placeholders = ':' . implode(', :', $keys);
        $sql = "INSERT INTO `{$model->table}` ($fields) VALUES ($placeholders)";
        $stmt = $model->conn->prepare($sql);
        $stmt->execute($data);
        return $model->conn->lastInsertId();
    }

    // Cập nhật bản ghi
    public static function update($id, $data) {
        $model = new static();
        unset($data[$model->primaryKey]);
        if (!empty($model->fillable)) {
            $data = array_intersect_key($data, array_flip($model->fillable));
        }

        $sets = [];
        $params = [];
        foreach ($data as $key => $value) {
            $sets[] = "`$key` = :set_$key";
            $params["set_$key"] = $value;
        }
        $setString = implode(', ', $sets);
        $sql = "UPDATE `{$model->table}` SET {$setString} WHERE `{$model->primaryKey}` = :pk_id";
        $params['pk_id'] = $id;

        $stmt = $model->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Xóa bản ghi
    public static function delete($id) {
        $model = new static();
        $sql = "DELETE FROM `{$model->table}` WHERE `{$model->primaryKey}` = :id";
        $stmt = $model->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Thực thi câu lệnh SQL tùy biến (VD: JOIN bảng, WHERE phức tạp)
    public static function rawQuery($sql, $params = []) {
        $model = new static();
        $stmt = $model->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    // Lấy kết nối PDO khi cần
    public function getConnection() {
        return $this->conn;
    }
}
