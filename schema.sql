CREATE DATABASE doingsdone;
USE doingsdone;

-- Проекты
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(32),
    user_id INT
);

-- Задачи
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME,
    completed_at DATETIME,
    name CHAR(128),
    file CHAR(225),
    deadline DATE,
    user_id INT,
    project_id INT
);

-- Пользователи
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(128),
    email CHAR(128),
    password CHAR(60),
    contacts TEXT
);

-- Индексы  
CREATE UNIQUE INDEX users_email ON user (email);
CREATE INDEX tasks_name ON task (name);
CREATE INDEX tasks_deadline ON task (deadline);

-- Внешние ключи
ALTER TABLE project
	ADD FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
ALTER TABLE task
	ADD FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE,
	ADD FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE;