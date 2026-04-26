/*
    SQL Server Basic Commands Reference
    Based on the style of: database_sqlserver.sql

    Notes:
    1) This file is written for SQL Server.
    2) Some commands like CHANGE and MODIFY are MySQL syntax, not SQL Server syntax.
    3) In SQL Server, we usually use:
       - sp_rename        -> to rename tables or columns
       - ALTER TABLE      -> to add, drop, or alter columns
       - ALTER COLUMN     -> to change a column data type / nullability
*/

/* ============================================================
   1) CREATE DATABASE
   Creates a new database
   ============================================================ */
CREATE DATABASE practice_db;
GO

/* ============================================================
   2) USE
   Selects the database you want to work with
   ============================================================ */
USE practice_db;
GO

/* ============================================================
   3) CREATE TABLE
   Creates a new table with columns
   ============================================================ */
CREATE TABLE students (
    student_id INT IDENTITY(1,1) PRIMARY KEY,
    full_name NVARCHAR(100) NOT NULL,
    email NVARCHAR(100) UNIQUE,
    age INT NULL,
    department NVARCHAR(100) NULL,
    created_at DATETIME DEFAULT GETDATE()
);
GO

CREATE TABLE courses (
    course_id INT IDENTITY(1,1) PRIMARY KEY,
    course_name NVARCHAR(100) NOT NULL,
    hours INT NOT NULL
);
GO

CREATE TABLE enrollments (
    enrollment_id INT IDENTITY(1,1) PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade NVARCHAR(10) NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);
GO

/* ============================================================
   4) INSERT INTO
   Adds new rows into a table
   ============================================================ */
INSERT INTO students (full_name, email, age, department)
VALUES
('Youssef Hussein', 'youssef@example.com', 21, 'Cybersecurity'),
('Sara Ali', 'sara@example.com', 20, 'Artificial Intelligence'),
('Omar Adel', 'omar@example.com', 22, 'Information Systems');
GO

INSERT INTO courses (course_name, hours)
VALUES
('Database Systems', 3),
('Network Security', 4),
('Python Automation', 3);
GO

INSERT INTO enrollments (student_id, course_id, grade)
VALUES
(1, 1, 'A'),
(1, 2, 'B+'),
(2, 1, 'A-'),
(3, 3, 'B');
GO

/* ============================================================
   5) SELECT
   Reads data from a table
   ============================================================ */
SELECT * FROM students;

SELECT full_name, department
FROM students;
GO

/* ============================================================
   6) WHERE
   Filters rows based on a condition
   ============================================================ */
SELECT *
FROM students
WHERE age > 20;
GO

/* ============================================================
   7) ORDER BY
   Sorts the result
   ============================================================ */
SELECT *
FROM students
ORDER BY full_name ASC;
GO

/* ============================================================
   8) UPDATE
   Changes existing data inside rows
   ============================================================ */
UPDATE students
SET department = 'Cybersecurity and Automation'
WHERE student_id = 1;
GO

/* ============================================================
   9) DELETE
   Deletes rows only, but keeps the table structure
   IMPORTANT: always use WHERE unless you want to delete everything
   ============================================================ */
DELETE FROM students
WHERE student_id = 3;
GO

/* ============================================================
   10) ALTER TABLE ... ADD
   Adds a new column to a table
   "ADD" in SQL Server is usually used with ALTER TABLE
   ============================================================ */
ALTER TABLE students
ADD phone NVARCHAR(20) NULL;
GO

/* ============================================================
   11) ALTER TABLE ... DROP COLUMN
   Removes a column from a table
   Be careful: this deletes the column and its data
   ============================================================ */
ALTER TABLE students
DROP COLUMN phone;
GO

/* ============================================================
   12) ALTER TABLE ... ALTER COLUMN
   Changes the data type, size, or NULL/NOT NULL rule of a column
   This is the SQL Server way to MODIFY a column
   ============================================================ */
ALTER TABLE students
ALTER COLUMN full_name NVARCHAR(150) NOT NULL;
GO

/* ============================================================
   13) RENAME TABLE
   SQL Server does NOT use: RENAME TABLE old_name TO new_name
   Instead, use sp_rename
   ============================================================ */
EXEC sp_rename 'students', 'trainees';
GO

/* Rename it back so later examples stay clear */
EXEC sp_rename 'trainees', 'students';
GO

/* ============================================================
   14) RENAME COLUMN
   SQL Server also uses sp_rename for columns
   Format: table_name.old_column_name , new_column_name
   ============================================================ */
EXEC sp_rename 'students.full_name', 'student_name', 'COLUMN';
GO

/* Rename it back */
EXEC sp_rename 'students.student_name', 'full_name', 'COLUMN';
GO

/* ============================================================
   15) CHANGE command
   IMPORTANT:
   CHANGE is a MySQL command, not a SQL Server command.

   MySQL example:
   ALTER TABLE students CHANGE full_name student_name VARCHAR(150);

   SQL Server equivalent:
   - Use sp_rename to change the column name
   - Use ALTER TABLE ... ALTER COLUMN to change the data type
   ============================================================ */
/* SQL Server equivalent for changing name */
EXEC sp_rename 'students.full_name', 'student_name', 'COLUMN';
GO

/* SQL Server equivalent for changing type */
ALTER TABLE students
ALTER COLUMN student_name NVARCHAR(150) NOT NULL;
GO

/* Rename back */
EXEC sp_rename 'students.student_name', 'full_name', 'COLUMN';
GO

/* ============================================================
   16) MODIFY command
   IMPORTANT:
   MODIFY is also MySQL syntax, not SQL Server syntax.

   MySQL example:
   ALTER TABLE students MODIFY full_name VARCHAR(150);

   SQL Server equivalent:
   ALTER TABLE students ALTER COLUMN full_name NVARCHAR(150) NOT NULL;
   ============================================================ */
ALTER TABLE students
ALTER COLUMN full_name NVARCHAR(150) NOT NULL;
GO

/* ============================================================
   17) INNER JOIN
   Returns only matching rows from both tables
   If no match exists, the row is not shown
   ============================================================ */
SELECT 
    s.full_name,
    c.course_name,
    e.grade
FROM students s
INNER JOIN enrollments e ON s.student_id = e.student_id
INNER JOIN courses c ON e.course_id = c.course_id;
GO

/* ============================================================
   18) LEFT JOIN
   Returns all rows from the LEFT table,
   even if there is no match in the RIGHT table
   ============================================================ */
SELECT 
    s.full_name,
    e.grade
FROM students s
LEFT JOIN enrollments e ON s.student_id = e.student_id;
GO

/* ============================================================
   19) RIGHT JOIN
   Returns all rows from the RIGHT table,
   even if there is no match in the LEFT table
   ============================================================ */
SELECT 
    s.full_name,
    c.course_name
FROM students s
RIGHT JOIN enrollments e ON s.student_id = e.student_id
RIGHT JOIN courses c ON e.course_id = c.course_id;
GO

/* ============================================================
   20) DROP TABLE
   Deletes the whole table structure and all its data
   Stronger than DELETE
   ============================================================ */
/* Example only - do not run unless you really want to remove the table */
-- DROP TABLE enrollments;
GO

/* ============================================================
   21) DROP DATABASE
   Deletes the whole database
   Very dangerous, use with care
   ============================================================ */
/* Example only - do not run unless you really want to remove the database */
-- DROP DATABASE practice_db;
GO

/* ============================================================
   22) TRUNCATE TABLE
   Removes all rows very quickly, but keeps the table structure
   Difference from DELETE:
   - DELETE can use WHERE
   - TRUNCATE removes all rows only
   ============================================================ */
/* Example only */
-- TRUNCATE TABLE students;
GO

/* ============================================================
   23) Common useful basic commands
   ============================================================ */

/* Show all rows with a condition */
SELECT *
FROM students
WHERE department = 'Cybersecurity';
GO

/* Search with LIKE */
SELECT *
FROM students
WHERE full_name LIKE 'Y%';
GO

/* Sort descending */
SELECT *
FROM students
ORDER BY age DESC;
GO

/* Count rows */
SELECT COUNT(*) AS total_students
FROM students;
GO

/* Maximum age */
SELECT MAX(age) AS max_age
FROM students;
GO

/* Minimum age */
SELECT MIN(age) AS min_age
FROM students;
GO

/* Average age */
SELECT AVG(age) AS avg_age
FROM students;
GO

/* Sum example */
SELECT SUM(hours) AS total_hours
FROM courses;
GO

/* ============================================================
   24) Difference Summary
   ============================================================ */
/*
DELETE   -> removes rows only
DROP     -> removes the whole object (table/database)
TRUNCATE -> removes all rows, keeps table structure
ADD      -> used with ALTER TABLE to add a column
ALTER    -> changes table structure
RENAME   -> in SQL Server, done with sp_rename
CHANGE   -> MySQL command, not SQL Server
MODIFY   -> MySQL command, not SQL Server
INNER JOIN -> matching rows only
LEFT JOIN  -> all rows from left table + matching rows from right
RIGHT JOIN -> all rows from right table + matching rows from left
*/
