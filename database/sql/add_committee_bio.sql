ALTER TABLE school_committee ADD COLUMN IF NOT EXISTS bio TEXT NULL COMMENT 'ประวัติ/ผลงาน' AFTER position;
