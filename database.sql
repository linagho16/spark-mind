-- ============================================
-- Database Schema for Feedbacks System
-- Database Name: strimr
-- ============================================

-- Create database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS strimr;
USE strimr;

-- ============================================
-- Table: Users
-- Stores user information
-- ============================================
CREATE TABLE IF NOT EXISTS Users (
    id VARCHAR(100) PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: Feedbacks
-- Stores user feedbacks
-- ============================================
CREATE TABLE IF NOT EXISTS Feedbacks (
    id VARCHAR(100) PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at),
    INDEX idx_email_created (email, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: Reactions
-- Stores likes/reactions on feedbacks
-- ============================================
CREATE TABLE IF NOT EXISTS Reactions (
    id VARCHAR(100) PRIMARY KEY,
    feedback_id VARCHAR(100) NOT NULL,
    user_id VARCHAR(100) NOT NULL,
    type VARCHAR(50) DEFAULT 'heart',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (feedback_id) REFERENCES Feedbacks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_reaction (feedback_id, user_id, type),
    INDEX idx_feedback_id (feedback_id),
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: Comments
-- Stores comments on feedbacks
-- ============================================
CREATE TABLE IF NOT EXISTS Comments (
    id VARCHAR(100) PRIMARY KEY,
    feedback_id VARCHAR(100) NOT NULL,
    user_id VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (feedback_id) REFERENCES Feedbacks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    INDEX idx_feedback_id (feedback_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Sample Data (Optional - for testing)
-- ============================================

-- Insert sample users
INSERT INTO Users (id, username, email, password_hash, created_at) VALUES
('1', 'testuser', 'test@example.com', 'dummyhash', NOW()),
('2', 'john_doe', 'john@example.com', 'dummyhash', NOW()),
('3', 'jane_smith', 'jane@example.com', 'dummyhash', NOW())
ON DUPLICATE KEY UPDATE username=username;

-- Insert sample feedbacks
INSERT INTO Feedbacks (id, email, description, created_at) VALUES
('feedback_1', 'test@example.com', 'This is a great feedback system! I love how easy it is to share my thoughts.', NOW()),
('feedback_2', 'john@example.com', 'The interface is user-friendly and the statistics feature is very helpful.', NOW()),
('feedback_3', 'jane@example.com', 'I appreciate the ability to like and comment on feedbacks. Keep up the good work!', NOW())
ON DUPLICATE KEY UPDATE description=description;

-- Insert sample reactions
INSERT INTO Reactions (id, feedback_id, user_id, type, created_at) VALUES
('reaction_1', 'feedback_1', '1', 'heart', NOW()),
('reaction_2', 'feedback_1', '2', 'heart', NOW()),
('reaction_3', 'feedback_2', '1', 'heart', NOW())
ON DUPLICATE KEY UPDATE type=type;

-- Insert sample comments
INSERT INTO Comments (id, feedback_id, user_id, content, created_at) VALUES
('comment_1', 'feedback_1', '2', 'I completely agree with this feedback!', NOW()),
('comment_2', 'feedback_1', '3', 'Great point! This feature is really useful.', NOW()),
('comment_3', 'feedback_2', '3', 'Thanks for sharing your experience.', NOW())
ON DUPLICATE KEY UPDATE content=content;

-- ============================================
-- Useful Queries for Testing
-- ============================================

-- View all feedbacks with like counts
-- SELECT 
--     f.id,
--     f.email,
--     f.description,
--     f.created_at,
--     COUNT(r.id) as like_count
-- FROM Feedbacks f
-- LEFT JOIN Reactions r ON r.feedback_id = f.id AND r.type = 'heart'
-- GROUP BY f.id
-- ORDER BY like_count DESC, f.created_at DESC;

-- View feedbacks with comments count
-- SELECT 
--     f.id,
--     f.description,
--     COUNT(DISTINCT c.id) as comment_count
-- FROM Feedbacks f
-- LEFT JOIN Comments c ON c.feedback_id = f.id
-- GROUP BY f.id;

-- ============================================
-- Notes:
-- ============================================
-- 1. All IDs are VARCHAR(100) to support the custom ID generation format:
--    prefix_timestamp_randomhex (e.g., 'feedback_1234567890_abc123def456')
--
-- 2. Foreign keys use ON DELETE CASCADE to automatically delete related
--    reactions and comments when a feedback or user is deleted.
--
-- 3. The UNIQUE constraint on Reactions prevents duplicate reactions
--    (same user can't like the same feedback twice).
--
-- 4. Indexes are added for frequently queried columns to improve performance.
--
-- 5. The database uses utf8mb4 charset to support emojis and special characters.
--
-- ============================================

