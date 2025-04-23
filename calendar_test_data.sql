-- Create a test department
INSERT INTO departments (name, description, manager_id, created_at, updated_at)
VALUES ('Engineering', 'Department for software engineers', 1, NOW(), NOW());

-- Get the department ID
SET @dept_id = LAST_INSERT_ID();

-- Create test projects
INSERT INTO projects (name, description, created_by, start_date, end_date, status, manager_id, department_id, created_at, updated_at)
VALUES 
('Website Redesign', 'Revamp the company website with new branding and improved UX', 1, DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 60 DAY), 'in_progress', 1, @dept_id, NOW(), NOW()),
('Mobile App Development', 'Build a new mobile app for our customers', 1, DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_ADD(NOW(), INTERVAL 45 DAY), 'planning', 1, @dept_id, NOW(), NOW()),
('Marketing Campaign', 'Q3 Digital Marketing Campaign planning and execution', 1, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 30 DAY), 'on_hold', 1, @dept_id, NOW(), NOW()),
('Product Launch', 'New product launch activities and coordination', 1, DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_ADD(NOW(), INTERVAL 15 DAY), 'in_progress', 1, @dept_id, NOW(), NOW()),
('CRM Implementation', 'Implement new CRM system and data migration', 1, DATE_SUB(NOW(), INTERVAL 25 DAY), DATE_ADD(NOW(), INTERVAL 90 DAY), 'planning', 1, @dept_id, NOW(), NOW());

-- Define project variables
SET @website_project_id = (SELECT id FROM projects WHERE name = 'Website Redesign' LIMIT 1);
SET @mobile_project_id = (SELECT id FROM projects WHERE name = 'Mobile App Development' LIMIT 1);
SET @marketing_project_id = (SELECT id FROM projects WHERE name = 'Marketing Campaign' LIMIT 1);
SET @product_project_id = (SELECT id FROM projects WHERE name = 'Product Launch' LIMIT 1);
SET @crm_project_id = (SELECT id FROM projects WHERE name = 'CRM Implementation' LIMIT 1);

-- Create tasks for Website Redesign
INSERT INTO tasks (title, description, project_id, created_by, due_date, priority, current_status, start_date, status, created_at, updated_at)
VALUES
('Homepage Redesign', 'Redesign the homepage with new branding elements', @website_project_id, 1, DATE_ADD(NOW(), INTERVAL 7 DAY), 'high', 'in_progress', DATE_SUB(NOW(), INTERVAL 5 DAY), 'todo', NOW(), NOW()),
('Contact Form Implementation', 'Create a new contact form with validation', @website_project_id, 1, DATE_ADD(NOW(), INTERVAL 14 DAY), 'medium', 'todo', DATE_SUB(NOW(), INTERVAL 1 DAY), 'todo', NOW(), NOW()),
('Mobile Responsiveness', 'Ensure the site works well on mobile devices', @website_project_id, 1, DATE_ADD(NOW(), INTERVAL 20 DAY), 'high', 'todo', DATE_ADD(NOW(), INTERVAL 7 DAY), 'todo', NOW(), NOW()),
('SEO Optimization', 'Optimize the website for search engines', @website_project_id, 1, DATE_ADD(NOW(), INTERVAL 30 DAY), 'medium', 'todo', DATE_ADD(NOW(), INTERVAL 14 DAY), 'todo', NOW(), NOW());

-- Create tasks for Mobile App
INSERT INTO tasks (title, description, project_id, created_by, due_date, priority, current_status, start_date, status, created_at, updated_at)
VALUES
('User Authentication', 'Implement login and registration functionality', @mobile_project_id, 1, DATE_ADD(NOW(), INTERVAL 5 DAY), 'high', 'in_progress', DATE_SUB(NOW(), INTERVAL 10 DAY), 'in_progress', NOW(), NOW()),
('Payment Gateway Integration', 'Integrate payment processing', @mobile_project_id, 1, DATE_ADD(NOW(), INTERVAL 10 DAY), 'high', 'todo', DATE_ADD(NOW(), INTERVAL 2 DAY), 'todo', NOW(), NOW()),
('Push Notifications', 'Add push notification capabilities', @mobile_project_id, 1, DATE_ADD(NOW(), INTERVAL 15 DAY), 'medium', 'todo', DATE_ADD(NOW(), INTERVAL 5 DAY), 'todo', NOW(), NOW()),
('Offline Mode', 'Implement offline functionality', @mobile_project_id, 1, DATE_ADD(NOW(), INTERVAL 25 DAY), 'low', 'todo', DATE_ADD(NOW(), INTERVAL 10 DAY), 'todo', NOW(), NOW());

-- Create tasks for Marketing Campaign
INSERT INTO tasks (title, description, project_id, created_by, due_date, priority, current_status, start_date, status, created_at, updated_at)
VALUES
('Social Media Strategy', 'Develop a social media marketing plan', @marketing_project_id, 1, DATE_ADD(NOW(), INTERVAL 3 DAY), 'high', 'completed', DATE_SUB(NOW(), INTERVAL 15 DAY), 'completed', NOW(), NOW()),
('Email Newsletter Design', 'Design email templates for the campaign', @marketing_project_id, 1, DATE_ADD(NOW(), INTERVAL 8 DAY), 'medium', 'in_progress', DATE_SUB(NOW(), INTERVAL 5 DAY), 'in_progress', NOW(), NOW()),
('Content Calendar', 'Create a content publishing schedule', @marketing_project_id, 1, DATE_ADD(NOW(), INTERVAL 12 DAY), 'medium', 'todo', DATE_SUB(NOW(), INTERVAL 1 DAY), 'todo', NOW(), NOW()),
('Analytics Setup', 'Configure analytics tracking', @marketing_project_id, 1, DATE_ADD(NOW(), INTERVAL 20 DAY), 'low', 'todo', DATE_ADD(NOW(), INTERVAL 5 DAY), 'todo', NOW(), NOW());

-- Create tasks for Product Launch
INSERT INTO tasks (title, description, project_id, created_by, due_date, priority, current_status, start_date, status, created_at, updated_at)
VALUES
('Press Release Drafting', 'Write and finalize press release', @product_project_id, 1, DATE_ADD(NOW(), INTERVAL 2 DAY), 'high', 'completed', DATE_SUB(NOW(), INTERVAL 5 DAY), 'completed', NOW(), NOW()),
('Demo Video Production', 'Create product demonstration video', @product_project_id, 1, DATE_ADD(NOW(), INTERVAL 6 DAY), 'high', 'in_progress', DATE_SUB(NOW(), INTERVAL 2 DAY), 'in_progress', NOW(), NOW()),
('Launch Event Planning', 'Organize product launch event', @product_project_id, 1, DATE_ADD(NOW(), INTERVAL 10 DAY), 'medium', 'in_progress', DATE_SUB(NOW(), INTERVAL 1 DAY), 'in_progress', NOW(), NOW()),
('Promotional Materials', 'Design and produce promotional materials', @product_project_id, 1, DATE_ADD(NOW(), INTERVAL 5 DAY), 'medium', 'todo', DATE_ADD(NOW(), INTERVAL 1 DAY), 'todo', NOW(), NOW());

-- Create tasks for CRM Implementation
INSERT INTO tasks (title, description, project_id, created_by, due_date, priority, current_status, start_date, status, created_at, updated_at)
VALUES
('Data Migration Plan', 'Create a plan for migrating customer data', @crm_project_id, 1, DATE_ADD(NOW(), INTERVAL 15 DAY), 'high', 'in_progress', DATE_SUB(NOW(), INTERVAL 20 DAY), 'in_progress', NOW(), NOW()),
('User Training Schedule', 'Create training materials and schedule', @crm_project_id, 1, DATE_ADD(NOW(), INTERVAL 45 DAY), 'medium', 'todo', DATE_ADD(NOW(), INTERVAL 15 DAY), 'todo', NOW(), NOW()),
('Integration Testing', 'Test integration with existing systems', @crm_project_id, 1, DATE_ADD(NOW(), INTERVAL 30 DAY), 'high', 'todo', DATE_ADD(NOW(), INTERVAL 10 DAY), 'todo', NOW(), NOW()),
('Custom Report Development', 'Build custom reports for management', @crm_project_id, 1, DATE_ADD(NOW(), INTERVAL 60 DAY), 'low', 'todo', DATE_ADD(NOW(), INTERVAL 30 DAY), 'todo', NOW(), NOW());

-- Assign all tasks to the first user
INSERT INTO task_assignments (task_id, user_id, assigned_by, assigned_at, created_at, updated_at)
SELECT id, 1, 1, NOW(), NOW(), NOW() FROM tasks; 