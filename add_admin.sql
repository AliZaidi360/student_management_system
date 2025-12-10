-- Add or update admin user with username 'admin' and password 'admin'
-- This will insert if admin doesn't exist, or update if it does

-- First, delete any existing admin with username 'admin' (optional, if you want to reset)
DELETE FROM `admin` WHERE `username` = 'admin';

-- Insert the admin user
INSERT INTO `admin` (`username`, `password`) VALUES
('admin', 'admin');

