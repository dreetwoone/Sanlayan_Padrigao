-- ============================================================
--  PROFIT DATABASE
--  HOW TO USE:
--  1. Open phpMyAdmin (go to http://localhost/phpmyadmin)
--  2. Click "New" on the left sidebar to create a database
--  3. Name it:  profit_db   then click Create
--  4. Click the "SQL" tab at the top
--  5. Paste this entire file and click "Go"
-- ============================================================

-- Always use this database
USE profit_db;

-- ============================================================
--  TABLE: users
--  Stores everyone who has an account (members + admins)
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,  -- unique ID for each user
    name       VARCHAR(100) NOT NULL,           -- full name
    email      VARCHAR(150) NOT NULL UNIQUE,    -- must be unique, used to log in
    password   VARCHAR(255) NOT NULL,           -- hashed password (NEVER store plain text)
    role       ENUM('member','trainer','admin') DEFAULT 'member',
    status     ENUM('active','pending','banned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
--  TABLE: buddies
--  Trainers / workout buddies users can connect with
-- ============================================================
CREATE TABLE IF NOT EXISTS buddies (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    tag        VARCHAR(100) NOT NULL,           -- e.g. "💪 Gym Enthusiast"
    tag_color  VARCHAR(20)  DEFAULT 'text-info',-- Bootstrap text color class
    avatar     VARCHAR(20)  DEFAULT '🐱',       -- emoji avatar
    user_id    INT,                             -- linked user account (optional)
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
--  TABLE: workouts
--  The types of workouts available to book
-- ============================================================
CREATE TABLE IF NOT EXISTS workouts (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(100) NOT NULL,             -- e.g. "Strength Training"
    icon     VARCHAR(10)  NOT NULL,             -- emoji icon
    duration VARCHAR(20)  NOT NULL              -- e.g. "60 min"
);

-- ============================================================
--  TABLE: bookings
--  When a user books a session with a buddy/trainer
-- ============================================================
CREATE TABLE IF NOT EXISTS bookings (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    buddy_id    INT NOT NULL,
    workout_id  INT NOT NULL,
    date        DATE NOT NULL,
    time        VARCHAR(20) NOT NULL,           -- e.g. "7:00 AM"
    notes       TEXT,                           -- optional notes from user
    status      ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (buddy_id)   REFERENCES buddies(id)  ON DELETE CASCADE,
    FOREIGN KEY (workout_id) REFERENCES workouts(id) ON DELETE CASCADE
);

-- ============================================================
--  TABLE: sessions
--  Completed workout sessions (logged after a booking is done)
-- ============================================================
CREATE TABLE IF NOT EXISTS sessions (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    workout_id  INT NOT NULL,
    buddy_id    INT,                            -- optional, solo sessions allowed
    duration    INT NOT NULL,                  -- in minutes
    calories    INT NOT NULL,
    session_date DATE NOT NULL,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (workout_id) REFERENCES workouts(id) ON DELETE CASCADE,
    FOREIGN KEY (buddy_id)   REFERENCES buddies(id)  ON DELETE SET NULL
);

-- ============================================================
--  TABLE: user_buddies
--  Tracks which buddies each user has added/connected with
-- ============================================================
CREATE TABLE IF NOT EXISTS user_buddies (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    buddy_id   INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_connection (user_id, buddy_id),  -- prevent duplicates
    FOREIGN KEY (user_id)  REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (buddy_id) REFERENCES buddies(id) ON DELETE CASCADE
);

-- ============================================================
--  TABLE: goals
--  Personal fitness goals for each user
-- ============================================================
CREATE TABLE IF NOT EXISTS goals (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    icon       VARCHAR(10)  NOT NULL,
    title      VARCHAR(200) NOT NULL,
    sub        VARCHAR(100),                    -- subtitle / category
    current    VARCHAR(50),                     -- current value, e.g. "84"
    target     VARCHAR(50),                     -- target value, e.g. "80 kg"
    pct        INT DEFAULT 0,                   -- percentage complete 0–100
    due_date   DATE,
    badge      ENUM('active','close','done') DEFAULT 'active',
    accent     VARCHAR(30) DEFAULT 'var(--green)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
--  SAMPLE DATA — so the app has something to show right away
-- ============================================================

-- Admin user  (password is:  admin123)
-- The hash below was made with PHP's password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (name, email, password, role, status) VALUES
('Paul Santos',    'paul@profit.app',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',   'active'),
('Michael Lee',    'michael@profit.app','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', 'active'),
('Mohammad Ali',   'moh@profit.app',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', 'active'),
('John Cruz',      'john@profit.app',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'trainer', 'active'),
('Ana Reyes',      'ana@profit.app',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member',  'active'),
('Carlos Tan',     'carlos@profit.app', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member',  'active'),
('Mia Santos',     'mia@profit.app',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'member',  'pending');

-- NOTE: ALL passwords above are currently "password"
-- (that hash is Laravel's default test hash — change all passwords after setup!)

-- Buddies / Trainers
INSERT INTO buddies (name, tag, tag_color, avatar, user_id) VALUES
('Michael',  '💪 Gym Enthusiast',  'text-info',    '🐱', 2),
('Mohammad', '🔥 High Activity',   'text-warning', '🏋️', 3),
('John',     '🏃 Runner',          'text-primary', '🐶', 4),
('Lebron',   '🥊 Boxing Enthusiast','text-light',  '🥊', NULL),
('Obiwan',   '🥊 Boxing Trainer',  'text-success', '🐕', NULL),
('Ippo',     '🥊 Shadow Boxer',    'text-danger',  '🐾', NULL);

-- Workout types
INSERT INTO workouts (name, icon, duration) VALUES
('Strength Training', '🏋️', '60 min'),
('Cardio',            '🏃', '45 min'),
('HIIT',              '⚡', '30 min'),
('Yoga & Stretch',    '🧘', '50 min'),
('Boxing',            '🥊', '45 min'),
('Cycling',           '🚴', '40 min');

-- Sample bookings for user 1 (Paul)
INSERT INTO bookings (user_id, buddy_id, workout_id, date, time, status) VALUES
(1, 1, 1, CURDATE(),                  '7:00 AM',  'confirmed'),
(1, 3, 5, DATE_ADD(CURDATE(),INTERVAL 2 DAY), '5:00 PM', 'pending'),
(5, 2, 3, CURDATE(),                  '8:00 AM',  'confirmed'),
(6, 3, 2, DATE_ADD(CURDATE(),INTERVAL 1 DAY), '10:00 AM','pending');

-- Sample completed sessions for user 1
INSERT INTO sessions (user_id, workout_id, buddy_id, duration, calories, session_date) VALUES
(1, 1, 1, 60, 420, CURDATE()),
(1, 2, NULL, 35, 310, DATE_SUB(CURDATE(), INTERVAL 1 DAY)),
(1, 3, 3, 30, 380, DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
(1, 6, NULL, 45, 350, DATE_SUB(CURDATE(), INTERVAL 3 DAY)),
(1, 4, NULL, 50, 180, DATE_SUB(CURDATE(), INTERVAL 4 DAY)),
(1, 1, 1, 60, 430, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(1, 5, 4, 45, 400, DATE_SUB(CURDATE(), INTERVAL 6 DAY)),
(1, 2, NULL, 40, 290, DATE_SUB(CURDATE(), INTERVAL 7 DAY)),
(1, 1, 2, 60, 415, DATE_SUB(CURDATE(), INTERVAL 8 DAY)),
(1, 3, NULL, 30, 360, DATE_SUB(CURDATE(), INTERVAL 9 DAY)),
(1, 4, 6, 50, 190, DATE_SUB(CURDATE(), INTERVAL 10 DAY)),
(1, 1, 1, 60, 410, DATE_SUB(CURDATE(), INTERVAL 11 DAY));

-- Sample goals for user 1
INSERT INTO goals (user_id, icon, title, sub, current, target, pct, due_date, badge, accent) VALUES
(1, '⚖️', 'Reach 80 kg',            'Weight goal',        '84',    '80 kg',     70, '2025-05-30', 'active', 'var(--green)'),
(1, '🏋️', 'Bench Press 100 kg',     'Strength milestone', '95',    '100 kg',    82, '2025-04-20', 'close',  'var(--orange)'),
(1, '🏃', 'Run 5K under 25 min',    'Cardio goal',        '24:10', '24:00',     88, '2025-04-15', 'close',  'var(--orange)'),
(1, '📅', '20 Sessions This Month', 'Consistency goal',   '12',    '20',        60, '2025-04-30', 'active', 'var(--green)'),
(1, '🔥', '14-Day Streak',          'Active days',        '9',     '14',        64, '2025-04-12', 'active', '#4f9eff'),
(1, '🥊', 'Complete Boxing Course', 'Skill goal',         '4',     '8 classes', 50, '2025-05-01', 'active', 'var(--cyan)');