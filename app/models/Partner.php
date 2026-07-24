<?php
/** Model: Partner — พันธมิตร */
class Partner extends Model
{
    protected string $table = 'partners';
    public function activeAll(): array { return $this->where(['status' => 'active'], 'sort_order ASC'); }
}
