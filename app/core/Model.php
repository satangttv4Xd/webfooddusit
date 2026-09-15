<?php
/**
 * ============================================================
 *  Model (Core / Base Model)
 * ------------------------------------------------------------
 *  คลาสแม่ของทุก Model ให้เมธอด CRUD พื้นฐาน + Soft Delete
 *  Model ลูกเพียงกำหนด $table และ (ถ้ามี) $fillable
 * ============================================================
 */

abstract class Model
{
    /** @var Database ตัวช่วยจัดการฐานข้อมูล */
    protected Database $db;

    /** @var string ชื่อตารางในฐานข้อมูล (กำหนดใน Model ลูก) */
    protected string $table = '';

    /** @var string ชื่อ primary key */
    protected string $primaryKey = 'id';

    /** @var bool ตารางนี้รองรับ soft delete หรือไม่ */
    protected bool $softDelete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * ดึงข้อมูลทั้งหมด (ไม่รวมที่ถูกลบแบบ soft delete)
     * @param string $orderBy คอลัมน์และทิศทางการเรียง เช่น 'id DESC'
     */
    public function all(string $orderBy = 'id DESC'): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->softDelete) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        $sql .= " ORDER BY {$orderBy}";
        return $this->db->query($sql)->all();
    }

    /**
     * ค้นหาด้วย primary key
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        if ($this->softDelete) {
            $sql .= " AND deleted_at IS NULL";
        }
        $sql .= " LIMIT 1";
        $row = $this->db->query($sql)->bind(['id' => $id])->one();
        return $row ?: null;
    }

    /**
     * ค้นหาแถวแรกที่ตรงเงื่อนไข where แบบ key=value
     * @param array $conditions เช่น ['slug' => 'abc', 'status' => 'published']
     */
    public function findWhere(array $conditions): ?array
    {
        [$whereSql, $params] = $this->buildWhere($conditions);
        $sql = "SELECT * FROM {$this->table} {$whereSql} LIMIT 1";
        $row = $this->db->query($sql)->bind($params)->one();
        return $row ?: null;
    }

    /**
     * ดึงหลายแถวตามเงื่อนไข
     */
    public function where(array $conditions, string $orderBy = 'id DESC', ?int $limit = null): array
    {
        [$whereSql, $params] = $this->buildWhere($conditions);
        $sql = "SELECT * FROM {$this->table} {$whereSql} ORDER BY {$orderBy}";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }
        return $this->db->query($sql)->bind($params)->all();
    }

    /**
     * เพิ่มข้อมูลใหม่ คืนค่า id ที่สร้าง
     * @param array $data ['column' => value, ...]
     */
    public function create(array $data): int
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $columns = array_keys($data);
        $placeholders = array_map(fn($c) => ':' . $c, $columns);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $this->db->query($sql)->bind($data)->execute();
        return (int)$this->db->lastId();
    }

    /**
     * แก้ไขข้อมูลตาม id คืนจำนวนแถวที่แก้
     */
    public function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

        $set = implode(', ', array_map(fn($c) => "{$c} = :{$c}", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = :__id";

        $data['__id'] = $id;
        $this->db->query($sql)->bind($data)->execute();
        return $this->db->rowCount();
    }

    /**
     * ลบแบบ Soft Delete (ตั้งค่า deleted_at) — กู้คืนได้ภายหลัง
     */
    public function delete(int $id): int
    {
        if ($this->softDelete) {
            $sql = "UPDATE {$this->table} SET deleted_at = :now, status = 'inactive'
                    WHERE {$this->primaryKey} = :id";
            $this->db->query($sql)->bind(['now' => date('Y-m-d H:i:s'), 'id' => $id])->execute();
        } else {
            $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
            $this->db->query($sql)->bind(['id' => $id])->execute();
        }
        return $this->db->rowCount();
    }

    /**
     * กู้คืนข้อมูลที่ถูก soft delete
     */
    public function restore(int $id): int
    {
        $sql = "UPDATE {$this->table} SET deleted_at = NULL, status = 'active'
                WHERE {$this->primaryKey} = :id";
        $this->db->query($sql)->bind(['id' => $id])->execute();
        return $this->db->rowCount();
    }

    /**
     * ลบถาวร (Force Delete) — ใช้เมื่อกดลบจากถังขยะ
     */
    public function forceDelete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $this->db->query($sql)->bind(['id' => $id])->execute();
        return $this->db->rowCount();
    }

    /**
     * นับจำนวนแถวตามเงื่อนไข (ไม่รวมที่ถูกลบ)
     */
    public function count(array $conditions = []): int
    {
        [$whereSql, $params] = $this->buildWhere($conditions);
        $sql = "SELECT COUNT(*) FROM {$this->table} {$whereSql}";
        return (int)$this->db->query($sql)->bind($params)->column();
    }

    /**
     * แบ่งหน้า (Pagination) พร้อมรองรับการค้นหา/กรอง
     * คืน ['data' => [], 'total' => n, 'page' => n, 'per_page' => n, 'total_pages' => n]
     */
    public function paginate(int $page = 1, int $perPage = 15, array $conditions = [], string $orderBy = 'id DESC', string $searchSql = '', array $searchParams = []): array
    {
        [$whereSql, $params] = $this->buildWhere($conditions);

        // ผนวกเงื่อนไขค้นหาเพิ่มเติม (ถ้ามี)
        if ($searchSql !== '') {
            $whereSql = $whereSql === '' ? "WHERE {$searchSql}" : "{$whereSql} AND ({$searchSql})";
            $params = array_merge($params, $searchParams);
        }

        $total = (int)$this->db->query("SELECT COUNT(*) FROM {$this->table} {$whereSql}")
                               ->bind($params)->column();

        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM {$this->table} {$whereSql} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        $data = $this->db->query($sql)->bind($params)->all();

        return [
            'data'        => $data,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    /**
     * สร้างส่วน WHERE จาก array เงื่อนไข พร้อมพ่วง soft delete ให้อัตโนมัติ
     * @return array [string $sql, array $params]
     */
    protected function buildWhere(array $conditions): array
    {
        $clauses = [];
        $params = [];

        foreach ($conditions as $col => $val) {
            $clauses[] = "{$col} = :w_{$col}";
            $params["w_{$col}"] = $val;
        }

        if ($this->softDelete) {
            $clauses[] = "deleted_at IS NULL";
        }

        $sql = $clauses ? 'WHERE ' . implode(' AND ', $clauses) : '';
        return [$sql, $params];
    }

    /**
     * รับประกันว่า slug ไม่ซ้ำกับที่มีอยู่แล้วในตาราง (เช็คทุกแถวรวมที่ถูก soft-delete ไปแล้ว
     * เพราะ constraint UNIQUE ระดับฐานข้อมูลไม่สนใจ deleted_at) ถ้าซ้ำจะเติม -2, -3, ... ต่อท้ายให้อัตโนมัติ
     * @param string   $slug      slug ที่ต้องการใช้ (ผ่าน Security::slug() มาแล้ว)
     * @param int|null $excludeId ไม่นับแถวนี้ (ใช้ตอนแก้ไขแถวเดิมที่ slug ไม่ได้เปลี่ยน)
     */
    public function uniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $base = $slug;
        $i = 2;
        while (true) {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE slug = :slug";
            $params = ['slug' => $slug];
            if ($excludeId !== null) {
                $sql .= " AND {$this->primaryKey} <> :id";
                $params['id'] = $excludeId;
            }
            $exists = (int)$this->db->query($sql)->bind($params)->column();
            if ($exists === 0) { return $slug; }
            $slug = $base . '-' . $i++;
        }
    }

    /** เข้าถึง Database โดยตรงสำหรับ query ซับซ้อนใน Model ลูก */
    protected function db(): Database
    {
        return $this->db;
    }
}
