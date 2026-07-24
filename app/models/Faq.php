<?php
/** Model: Faq — คำถามที่พบบ่อย */
class Faq extends Model
{
    protected string $table = 'faq';
    public function activeAll(): array { return $this->where(['status' => 'active'], 'sort_order ASC'); }
}
