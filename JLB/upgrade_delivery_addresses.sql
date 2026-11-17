USE jlb_collections;

CREATE TABLE IF NOT EXISTS customer_addresses (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 recipient_name VARCHAR(120) NOT NULL,
 phone VARCHAR(30) NOT NULL,
 region VARCHAR(120) NOT NULL,
 province VARCHAR(120) NOT NULL,
 city VARCHAR(120) NOT NULL,
 barangay VARCHAR(120) NOT NULL,
 street VARCHAR(180) NOT NULL,
 postal_code VARCHAR(12) NOT NULL,
 landmark VARCHAR(180) DEFAULT '',
 is_default TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_customer_addresses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 INDEX idx_customer_address_default (user_id, is_default)
) ENGINE=InnoDB;
