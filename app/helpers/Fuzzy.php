<?php
/**
 * ============================================================
 *  Fuzzy (Helper)
 * ------------------------------------------------------------
 *  จับคำที่สะกดใกล้เคียง (พิมพ์ผิด 1–2 ตัว) รองรับภาษาไทย
 *
 *  หมายเหตุ: levenshtein() ของ PHP ใช้กับภาษาไทยไม่ได้
 *  เพราะนับเป็นไบต์ (อักษรไทย 1 ตัว = 3 ไบต์) จึงต้องเขียนเอง
 *  โดยเทียบทีละอักขระแบบ multibyte
 * ============================================================
 */

class Fuzzy
{
    /** แคชผลการตัดอักขระ กันคำนวณซ้ำ */
    private static array $charCache = [];

    /**
     * ระยะห่างระหว่างสองข้อความ (จำนวนครั้งที่ต้องเพิ่ม/ลบ/แก้อักขระ)
     * รองรับ UTF-8 — "ค่าเทอม" vs "ค่าเทิม" = 1
     */
    public static function distance(string $a, string $b, int $max = 3): int
    {
        $s = self::chars(mb_strtolower($a));
        $t = self::chars(mb_strtolower($b));
        $m = count($s);
        $n = count($t);

        if ($m === 0) { return $n; }
        if ($n === 0) { return $m; }
        // ความยาวต่างกันมากเกินเกณฑ์ ไม่ต้องคำนวณต่อ
        if (abs($m - $n) > $max) { return $max + 1; }

        $prev = range(0, $n);
        for ($i = 1; $i <= $m; $i++) {
            $cur    = [$i];
            $rowMin = $i;
            for ($j = 1; $j <= $n; $j++) {
                $cost   = ($s[$i - 1] === $t[$j - 1]) ? 0 : 1;
                $val    = min(
                    $prev[$j] + 1,        // ลบ
                    $cur[$j - 1] + 1,     // เพิ่ม
                    $prev[$j - 1] + $cost // แทนที่
                );
                $cur[$j] = $val;
                $rowMin  = min($rowMin, $val);
            }
            // ทั้งแถวเกินเกณฑ์แล้ว หยุดได้เลย (ตัดการคำนวณให้เร็วขึ้น)
            if ($rowMin > $max) { return $max + 1; }
            $prev = $cur;
        }
        return $prev[$n];
    }

    /**
     * จำนวนตัวอักษรที่ยอมให้ผิดได้ ตามความยาวคำ
     * คำสั้นต้องตรงเป๊ะ (กันจับผิดคำ) คำยาวยอมผิดได้มากขึ้น
     */
    public static function tolerance(string $word): int
    {
        $len = mb_strlen($word);
        if ($len <= 3)  { return 0; }
        if ($len <= 6)  { return 1; }
        if ($len <= 12) { return 2; }
        return 3;
    }

    /** สองคำนี้ถือว่าเป็นคำเดียวกันไหม (ยอมให้สะกดผิดเล็กน้อย) */
    public static function isSimilar(string $a, string $b, ?int $tolerance = null): bool
    {
        if ($a === $b) { return true; }
        $tol = $tolerance ?? self::tolerance($a);
        if ($tol === 0) { return mb_strtolower($a) === mb_strtolower($b); }
        return self::distance($a, $b, $tol) <= $tol;
    }

    /**
     * ข้อความมีคำนี้อยู่ไหม โดยยอมให้สะกดผิดได้
     * ใช้วิธีเลื่อนหน้าต่าง (sliding window) เทียบทีละช่วงในข้อความ
     * เช่น "ค่าเทิมเท่าไหร่" มีคำว่า "ค่าเทอม" (ผิด 1 ตัว) → true
     */
    public static function contains(string $haystack, string $needle, ?int $tolerance = null): bool
    {
        $h = mb_strtolower($haystack);
        $n = mb_strtolower($needle);

        // ตรงเป๊ะ — เร็วสุด ไม่ต้องคำนวณ
        if (mb_strpos($h, $n) !== false) { return true; }

        $tol = $tolerance ?? self::tolerance($n);
        if ($tol === 0) { return false; }

        $hLen = mb_strlen($h);
        $nLen = mb_strlen($n);
        if ($hLen < $nLen - $tol) { return false; }

        // เทียบช่วงย่อยที่ความยาวใกล้เคียงกับคำค้น
        for ($i = 0; $i <= $hLen - $nLen + $tol; $i++) {
            for ($w = max(1, $nLen - $tol); $w <= $nLen + $tol; $w++) {
                if ($i + $w > $hLen) { break; }
                if (self::distance(mb_substr($h, $i, $w), $n, $tol) <= $tol) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * ปรับรูปคำไทยให้เป็นมาตรฐานก่อนเทียบ — แก้พิมพ์ผิดที่พบบ่อยที่สุด
     *  • ตัดวรรณยุกต์และไม้ทัณฑฆาต (เบเกอรี่ = เบเกอรี)
     *  • ยุบสระเสียงสั้น/ยาวที่สับสนกัน (ิ=ี, ุ=ู)
     *  • ยุบพยัญชนะที่ออกเสียงเหมือนกัน (ศ ษ ส = ส, ท ธ = ท ฯลฯ)
     * ใช้คู่กับการค้นแบบ substring ทำให้เร็วมากและทนคำสะกดผิดได้ดี
     */
    public static function normalize(string $s): string
    {
        $s = mb_strtolower(trim($s));
        // ตัดวรรณยุกต์ ไม้ไต่คู้ ทัณฑฆาต และช่องว่าง
        $s = preg_replace('/[\x{0E48}-\x{0E4C}\x{0E47}\x{0E4E}\s]/u', '', $s) ?? $s;
        // ยุบอักขระที่สับสนกันบ่อย
        $map = [
            'ี' => 'ิ', 'ื' => 'ึ', 'ู' => 'ุ',            // สระเสียงสั้น/ยาว
            'ษ' => 'ส', 'ศ' => 'ส', 'ซ' => 'ส',            // เสียง /s/
            'ธ' => 'ท', 'ถ' => 'ท', 'ฐ' => 'ท', 'ฒ' => 'ท', // เสียง /th/
            'ฆ' => 'ค', 'ข' => 'ค', 'ฅ' => 'ค',            // เสียง /kh/
            'ณ' => 'น', 'ญ' => 'ย', 'ฬ' => 'ล', 'ร' => 'ล', // น/ย/ล-ร
            'ภ' => 'พ', 'ผ' => 'พ', 'ฟ' => 'ฝ',            // เสียง /ph/, /f/
            'ฑ' => 'ด', 'ฎ' => 'ด', 'ต' => 'ด',            // เสียง /d/, /t/
            'ฉ' => 'ช', 'ฌ' => 'ช',                        // เสียง /ch/
        ];
        return strtr($s, $map);
    }

    /** ข้อความมีคำนี้ไหม โดยเทียบแบบปรับรูปคำแล้ว (เร็ว เหมาะกับข้อความยาว) */
    public static function containsNormalized(string $haystack, string $needle): bool
    {
        $n = self::normalize($needle);
        if ($n === '') { return false; }
        return mb_strpos(self::normalize($haystack), $n) !== false;
    }

    /**
     * หาคำที่ใกล้เคียงที่สุดจากรายการ (ใช้ทำ "คุณหมายถึง...?")
     * @return array{word:string,distance:int}|null
     */
    public static function closest(string $word, array $candidates, ?int $tolerance = null): ?array
    {
        $tol  = $tolerance ?? self::tolerance($word);
        $best = null;
        foreach ($candidates as $c) {
            $d = self::distance($word, (string)$c, $tol);
            if ($d <= $tol && ($best === null || $d < $best['distance'])) {
                $best = ['word' => (string)$c, 'distance' => $d];
                if ($d === 0) { break; }
            }
        }
        return $best;
    }

    /** ตัดข้อความเป็นอักขระทีละตัว (รองรับ UTF-8) พร้อมแคช */
    private static function chars(string $s): array
    {
        if (!isset(self::$charCache[$s])) {
            if (count(self::$charCache) > 500) { self::$charCache = []; } // กันหน่วยความจำบวม
            self::$charCache[$s] = function_exists('mb_str_split')
                ? mb_str_split($s)
                : (preg_split('//u', $s, -1, PREG_SPLIT_NO_EMPTY) ?: []);
        }
        return self::$charCache[$s];
    }
}
