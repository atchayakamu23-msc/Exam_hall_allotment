CREATE DATABASE IF NOT EXISTS exam_hall_db;
USE exam_hall_db;

-- 1. Admin Table (Password without hash as requested)
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL -- Stores plain text password
);

-- Insert Default Admin (Username: admin, Password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', 'admin123');

-- 2. Students Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    register_number VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(50) NOT NULL
);

-- 3. Halls Table
CREATE TABLE halls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hall_name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL
);

-- 4. Seating Allotment Table
CREATE TABLE seating_allotment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    hall_id INT NOT NULL,
    seat_number INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (hall_id) REFERENCES halls(id)
);

