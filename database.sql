CREATE DATABASE IF NOT EXISTS ai_meeting_minutes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ai_meeting_minutes;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS meetings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    meeting_date DATE NOT NULL,
    duration VARCHAR(50) DEFAULT '',
    transcript LONGTEXT,
    summary TEXT,
    discussion_points TEXT,
    decisions TEXT,
    action_items TEXT,
    speakers TEXT,
    language VARCHAR(50) DEFAULT 'English',
    ai_summary LONGTEXT,
    file_name VARCHAR(255) DEFAULT '',
    stored_file VARCHAR(255) DEFAULT '',
    minutes_json LONGTEXT,
    engine VARCHAR(30) DEFAULT 'local',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Demo account: demo@example.com / password
INSERT IGNORE INTO users (name, email, password)
VALUES ('Demo User', 'demo@example.com', '$2y$10$H5JeG/BG/AOdAaTx17LiSOFDNb1oUx0GrXRJxg1Z./cVTc46XeMxy');
