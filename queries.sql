USE doingsdone;

# Заполнение пользователей        
INSERT INTO users (email, name, password)
VALUES
    ('ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'),
    ('kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'),
    ('warrior07@mail.ru', 'Руслан', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW');

# Заполнение проектов
INSERT INTO projects (name, user_id)
VALUES
('Входящие', 1),
('Учеба', 2),
('Работа', 3),
('Домашние дела', 2),
('Авто', 1);

# Заполнение задач
INSERT INTO tasks (created_at, completed_at, name, deadline, user_id, project_id)
VALUES
(NOW(), null, 'Собеседование в IT компании', STR_TO_DATE('01.06.2018', '%d.%m.%Y'), 1, 3),
(NOW(), null, 'Выполнить тестовое задание', STR_TO_DATE('25.05.2018', '%d.%m.%Y'), 2, 3),
(NOW(), STR_TO_DATE('2018-04-20 14:20:00', '%Y-%m-%d %H:%i:%s'), 'Сделать задание первого раздела', STR_TO_DATE('21.04.2018', '%d.%m.%Y'), 2, 2),
(NOW(), null, 'Встреча с другом', STR_TO_DATE('22.04.2018', '%d.%m.%Y'), 3, 1),
(NOW(), null, 'Купить корм для кота', null, 2, 4),
(NOW(), null, 'Заказать пиццу', null, 1, 4);
    
# Получить список из всех проектов для одного пользователя
SELECT * FROM projects
WHERE user_id = 1;
    
# Получить список из всех задач для одного проекта
SELECT * FROM tasks
WHERE project_id = 2;

# Пометить задачу как выполненную
UPDATE tasks SET completed_at = NOW() WHERE id = 1;

# Получить все задачи для завтрашнего дня
SELECT * FROM tasks WHERE deadline = CURDATE() + INTERVAL 1 DAY;

# Обновить название задачи по её идентификатору
UPDATE tasks SET name = 'name_provided' WHERE id = 1;