-- Create a test department
INSERT INTO departments (name, description, manager_id, created_at, updated_at)
VALUES ('Engineering', 'Department for software engineers', 1, NOW(), NOW()); 