# Add Product name
ALTER TABLE user_serial
    ADD COLUMN product_name VARCHAR(64) NOT NULL DEFAULT '' AFTER pc_hash;