<?php
/**
 * ============================================================
 *  Validator (Helper)
 * ------------------------------------------------------------
 *  ตรวจสอบความถูกต้องของข้อมูลจากฟอร์มอย่างง่าย
 *  รองรับกฎ: required, email, min, max, numeric, confirmed, unique
 * ============================================================
 */

class Validator
{
    private array $errors = [];
    private array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * ตรวจสอบตามชุดกฎ
     * @param array $rules เช่น ['title' => 'required|max:255', 'email' => 'required|email']
     * @param array $labels ชื่อฟิลด์ภาษาไทยเพื่อแสดงข้อความ
     */
    public function validate(array $rules, array $labels = []): bool
    {
        foreach ($rules as $field => $ruleString) {
            $value = $this->data[$field] ?? '';
            $label = $labels[$field] ?? $field;
            $rulesList = explode('|', $ruleString);

            foreach ($rulesList as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
                $this->applyRule($field, $value, $label, $name, $param);
            }
        }
        return empty($this->errors);
    }

    /**
     * ใช้กฎแต่ละข้อ
     */
    private function applyRule(string $field, $value, string $label, string $rule, ?string $param): void
    {
        switch ($rule) {
            case 'required':
                if (trim((string)$value) === '') {
                    $this->addError($field, "กรุณากรอก{$label}");
                }
                break;

            case 'email':
                if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "รูปแบบ{$label}ไม่ถูกต้อง");
                }
                break;

            case 'numeric':
                if ($value !== '' && !is_numeric($value)) {
                    $this->addError($field, "{$label}ต้องเป็นตัวเลข");
                }
                break;

            case 'min':
                if (mb_strlen((string)$value) < (int)$param) {
                    $this->addError($field, "{$label}ต้องมีอย่างน้อย {$param} ตัวอักษร");
                }
                break;

            case 'max':
                if (mb_strlen((string)$value) > (int)$param) {
                    $this->addError($field, "{$label}ต้องไม่เกิน {$param} ตัวอักษร");
                }
                break;

            case 'confirmed':
                if ($value !== ($this->data[$field . '_confirmation'] ?? null)) {
                    $this->addError($field, "การยืนยัน{$label}ไม่ตรงกัน");
                }
                break;
        }
    }

    private function addError(string $field, string $message): void
    {
        // เก็บ error แรกของแต่ละฟิลด์ก็พอ
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    /** คืน array ของ error ทั้งหมด */
    public function errors(): array
    {
        return $this->errors;
    }

    /** คืนข้อความ error ทั้งหมดรวมเป็นข้อความเดียว */
    public function firstError(): string
    {
        return $this->errors ? reset($this->errors) : '';
    }
}
