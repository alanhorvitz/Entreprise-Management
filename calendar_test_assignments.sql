-- Assign all tasks to the first user
INSERT INTO task_assignments (task_id, user_id, assigned_by, assigned_at, created_at, updated_at)
SELECT id, 1, 1, NOW(), NOW(), NOW() FROM tasks; 