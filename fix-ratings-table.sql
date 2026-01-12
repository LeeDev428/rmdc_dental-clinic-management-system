-- Add user_id column if it doesn't exist
ALTER TABLE ratings_review 
ADD COLUMN IF NOT EXISTS user_id BIGINT UNSIGNED NULL AFTER id,
ADD COLUMN IF NOT EXISTS appointment_id BIGINT UNSIGNED NULL AFTER user_id;

-- Add foreign key constraints if they don't exist
ALTER TABLE ratings_review
ADD CONSTRAINT ratings_review_user_id_foreign 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE ratings_review
ADD CONSTRAINT ratings_review_appointment_id_foreign 
FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE;
