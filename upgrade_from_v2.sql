-- Adds every column the current version needs. If a line says "Duplicate column name", that column already exists - just run the next line.
USE ai_meeting_minutes;
ALTER TABLE meetings ADD COLUMN duration VARCHAR(50) DEFAULT '';
ALTER TABLE meetings ADD COLUMN transcript LONGTEXT NULL;
ALTER TABLE meetings ADD COLUMN summary TEXT NULL;
ALTER TABLE meetings ADD COLUMN discussion_points TEXT NULL;
ALTER TABLE meetings ADD COLUMN decisions TEXT NULL;
ALTER TABLE meetings ADD COLUMN action_items TEXT NULL;
ALTER TABLE meetings ADD COLUMN speakers TEXT NULL;
ALTER TABLE meetings ADD COLUMN language VARCHAR(50) DEFAULT 'English';
ALTER TABLE meetings ADD COLUMN ai_summary LONGTEXT NULL;
ALTER TABLE meetings ADD COLUMN file_name VARCHAR(255) DEFAULT '';
ALTER TABLE meetings ADD COLUMN stored_file VARCHAR(255) DEFAULT '';
ALTER TABLE meetings ADD COLUMN minutes_json LONGTEXT NULL;
ALTER TABLE meetings ADD COLUMN engine VARCHAR(30) DEFAULT 'local';
UPDATE users SET password = '$2y$10$H5JeG/BG/AOdAaTx17LiSOFDNb1oUx0GrXRJxg1Z./cVTc46XeMxy' WHERE email = 'demo@example.com';
