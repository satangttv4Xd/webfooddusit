<?php
/** Model: Menu — เมนูนำทาง */
class Menu extends Model
{
    protected string $table = 'menu';

    public function byPosition(string $position = 'header'): array
    {
        return $this->where(['status' => 'active', 'position' => $position], 'sort_order ASC');
    }

    /**
     * คืนเมนูเป็นโครงต้นไม้ (รองรับหลายระดับ: พาเรนต์ → children → grandchildren)
     * ใช้ทำ dropdown / submenu ในแถบนำทาง
     */
    public function tree(string $position = 'header'): array
    {
        $items = $this->where(['status' => 'active', 'position' => $position], 'sort_order ASC');

        // จัดกลุ่มตาม parent_id (null = ระดับบนสุด → คีย์ 0)
        $byParent = [];
        foreach ($items as $it) {
            $byParent[(int)($it['parent_id'] ?? 0)][] = $it;
        }
        return $this->buildTree($byParent, 0);
    }

    private function buildTree(array $byParent, int $parentId): array
    {
        $out = [];
        foreach ($byParent[$parentId] ?? [] as $it) {
            $it['children'] = $this->buildTree($byParent, (int)$it['id']);
            $out[] = $it;
        }
        return $out;
    }
}
