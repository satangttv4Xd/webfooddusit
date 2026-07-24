<?php
/** Model: Dean — ทำเนียบคณบดี */
class Dean extends Model
{
    protected string $table = 'deans';

    /** คณบดีที่เผยแพร่ เรียงตามลำดับ */
    public function activeAll(): array
    {
        return $this->where(['status' => 'active'], 'sort_order ASC, id ASC');
    }

    /** ทั้งหมดสำหรับหลังบ้าน */
    public function listAll(): array
    {
        return $this->all('sort_order ASC, id ASC');
    }
}
