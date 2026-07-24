<?php
/** Model: Department — หน่วยงาน/สาขา */
class Department extends Model
{
    protected string $table = 'departments';
    public function activeAll(): array { return $this->where(['status' => 'active'], 'sort_order ASC'); }
}
