-- Create remember_me_tokens table
CREATE TABLE IF NOT EXISTS `remember_me_tokens` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `token` varchar(64) NOT NULL,
    `expires` datetime NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `token` (`token`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `fk_remember_me_user` FOREIGN KEY (`user_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create pending_patients table if not exists
CREATE TABLE IF NOT EXISTS `pending_patients` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` varchar(255) DEFAULT NULL,
    `middle_name` varchar(255) DEFAULT NULL,
    `last_name` varchar(255) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `phone_number` varchar(255) DEFAULT NULL,
    `region` varchar(255) DEFAULT NULL,
    `province` varchar(255) DEFAULT NULL,
    `city` varchar(255) DEFAULT NULL,
    `barangay` varchar(255) DEFAULT NULL,
    `zip_code` varchar(255) DEFAULT NULL,
    `date_of_birth` date DEFAULT NULL,
    `password_hash` varchar(255) DEFAULT NULL,
    `gender` varchar(10) DEFAULT NULL,
    `role` enum('dentist','dental_helper','user') DEFAULT 'user',
    `otp` varchar(10) DEFAULT NULL,
    `otp_expires` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Update patients table if needed
ALTER TABLE `patients`
    ADD COLUMN IF NOT EXISTS `role` enum('dentist','dental_helper','user') DEFAULT 'user' AFTER `gender`,
    ADD COLUMN IF NOT EXISTS `profile_img` varchar(255) DEFAULT 'profile-placeholder.jpg' AFTER `created_at`;

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS `idx_remember_me_expires` ON `remember_me_tokens` (`expires`);
CREATE INDEX IF NOT EXISTS `idx_pending_patients_otp_expires` ON `pending_patients` (`otp_expires`);

-- Make personal and medical fields optional
ALTER TABLE appointments 
    MODIFY COLUMN blood_type VARCHAR(5) NULL,
    MODIFY COLUMN medical_history TEXT NULL,
    MODIFY COLUMN allergies TEXT NULL,
    MODIFY COLUMN health VARCHAR(10) NULL,
    MODIFY COLUMN pregnant VARCHAR(10) NULL,
    MODIFY COLUMN nursing VARCHAR(10) NULL,
    MODIFY COLUMN birth_control VARCHAR(10) NULL,
    MODIFY COLUMN blood_pressure VARCHAR(20) NULL;