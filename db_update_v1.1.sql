# Add Product name
ALTER TABLE user_serial
    ADD COLUMN product_name VARCHAR(64) NOT NULL DEFAULT '' AFTER pc_hash;

# Add Period
ALTER TABLE serial
    ADD COLUMN period INT NOT NULL DEFAULT 30 AFTER serial;

# Add Activated_at
ALTER TABLE user_serial
    ADD COLUMN activated_at DATE AFTER status;
