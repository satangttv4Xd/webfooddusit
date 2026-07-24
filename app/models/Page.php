<?php
/** Model: Page — หน้าเนื้อหา */
class Page extends Model
{
    protected string $table = 'pages';
    public function findBySlug(string $slug): ?array
    {
        return $this->findWhere(['slug' => $slug, 'status' => 'published']);
    }
}
