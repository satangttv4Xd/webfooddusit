<?php
/** Model: Banner — แบนเนอร์หน้าแรก */
class Banner extends Model
{
    protected string $table = 'banner';
    public function activeAll(): array { return $this->where(['status' => 'active'], 'sort_order ASC'); }
}
