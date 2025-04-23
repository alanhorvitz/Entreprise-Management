-- Get the first department ID
SELECT @dept_id := id FROM departments ORDER BY id LIMIT 1;

-- Create test projects
INSERT INTO projects (name, description, created_by, start_date, end_date, status, manager_id, department_id, created_at, updated_at)
VALUES 
('Website Redesign', 'Revamp the company website with new branding and improved UX', 1, DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 60 DAY), 'planning', 1, @dept_id, NOW(), NOW()),
('Mobile App Development', 'Build a new mobile app for our customers', 1, DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_ADD(NOW(), INTERVAL 45 DAY), 'planning', 1, @dept_id, NOW(), NOW()),
('Marketing Campaign', 'Q3 Digital Marketing Campaign planning and execution', 1, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 30 DAY), 'planning', 1, @dept_id, NOW(), NOW()),
('Product Launch', 'New product launch activities and coordination', 1, DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_ADD(NOW(), INTERVAL 15 DAY), 'in_progress', 1, @dept_id, NOW(), NOW()),
('CRM Implementation', 'Implement new CRM system and data migration', 1, DATE_SUB(NOW(), INTERVAL 25 DAY), DATE_ADD(NOW(), INTERVAL 90 DAY), 'planning', 1, @dept_id, NOW(), NOW()); 