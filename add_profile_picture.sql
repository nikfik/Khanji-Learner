-- Add profile_picture column to users table
ALTER TABLE users ADD COLUMN profile_picture BYTEA;

-- Create index for faster queries
CREATE INDEX IF NOT EXISTS idx_users_profile_picture ON users(id) WHERE profile_picture IS NOT NULL;
