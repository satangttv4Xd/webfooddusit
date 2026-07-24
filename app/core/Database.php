<?php
/**
 * ============================================================
 *  Database (Core)
 * ------------------------------------------------------------
 *  คลาสเชื่อมต่อฐานข้อมูลด้วย PDO แบบ Singleton
 *  พร้อมเมธอดช่วยรัน query แบบ Prepared Statement (กัน SQL Injection)
 * ============================================================
 */

class Database
{
    /** @var PDO|null อินสแตนซ์การเชื่อมต่อเดียวที่ใช้ร่วมกันทั้งระบบ */
    private static ?PDO $instance = null;

    /** @var PDOStatement|null คำสั่งที่เตรียมไว้ล่าสุด */
    private $stmt = null;

    /**
     * คืนค่าอินสแตนซ์ PDO (สร้างครั้งเดียวแล้วใช้ซ้ำ)
     */
    public static function pdo(): PDO
    {
        if (self::$instance === null) {
            $config = require APP_PATH . '/config/database.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                // ในโหมด production ไม่ควรโชว์รายละเอียด error ให้ผู้ใช้เห็น
                if (APP_ENV === 'development') {
                    die('เชื่อมต่อฐานข้อมูลไม่สำเร็จ: ' . $e->getMessage());
                }
                error_log('DB Connection Error: ' . $e->getMessage());
                die('ขออภัย ระบบไม่สามารถเชื่อมต่อฐานข้อมูลได้ในขณะนี้');
            }
        }
        return self::$instance;
    }

    /**
     * เตรียมคำสั่ง SQL
     * @param string $sql คำสั่ง SQL ที่มี placeholder เช่น :id หรือ ?
     */
    public function query(string $sql): self
    {
        $this->stmt = self::pdo()->prepare($sql);
        return $this;
    }

    /**
     * ผูกค่าพารามิเตอร์เข้ากับคำสั่ง (แบบ array)
     * @param array $params เช่น ['id' => 5, 'name' => 'ก']
     */
    public function bind(array $params): self
    {
        foreach ($params as $key => $value) {
            $type = match (true) {
                is_int($value)  => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default         => PDO::PARAM_STR,
            };
            // รองรับทั้งรูปแบบ :key และ key
            $param = is_int($key) ? $key + 1 : ':' . ltrim($key, ':');
            $this->stmt->bindValue($param, $value, $type);
        }
        return $this;
    }

    /** รันคำสั่งที่เตรียมไว้ (คืน true/false) */
    public function execute(array $params = []): bool
    {
        return $params ? $this->stmt->execute($params) : $this->stmt->execute();
    }

    /** ดึงผลลัพธ์หลายแถว */
    public function all(array $params = []): array
    {
        $this->execute($params ?: []);
        return $this->stmt->fetchAll();
    }

    /** ดึงผลลัพธ์แถวเดียว */
    public function one(array $params = [])
    {
        $this->execute($params ?: []);
        return $this->stmt->fetch();
    }

    /** ดึงค่าคอลัมน์เดียว (เช่น COUNT(*)) */
    public function column(array $params = [])
    {
        $this->execute($params ?: []);
        return $this->stmt->fetchColumn();
    }

    /** จำนวนแถวที่ได้รับผลกระทบจากคำสั่งล่าสุด */
    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    /** id ของแถวล่าสุดที่ถูก insert */
    public function lastId(): string
    {
        return self::pdo()->lastInsertId();
    }

    /** เริ่ม transaction */
    public function beginTransaction(): bool
    {
        return self::pdo()->beginTransaction();
    }

    /** ยืนยัน transaction */
    public function commit(): bool
    {
        return self::pdo()->commit();
    }

    /** ยกเลิก transaction */
    public function rollBack(): bool
    {
        return self::pdo()->rollBack();
    }
}
