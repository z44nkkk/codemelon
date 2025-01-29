CREATE DATABASE cocounut_sb;
USE cocounut_sb;

CREATE TABLE users (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    name varchar(120) NOT NULL,
    email varchar(255) NOT NULL,
    pwd varchar(128) NOT NULL
);

CREATE TABLE users_data (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT(20) NOT NULL,
    user_token VARCHAR(8) UNIQUE,
    permissions int(1) NOT NULL,
    store_name varchar(200) NOT NULL,
    creation_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    first_time_login TINYINT(1) DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE users_data ADD COLUMN profile_picture TEXT AFTER store_name;
ALTER TABLE users_data ADD COLUMN google_id VARCHAR(255) AFTER user_token;

CREATE TABLE user_page_access (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT(20) NOT NULL,
    page_name VARCHAR(255) NOT NULL,
    access_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    device_type VARCHAR(50),
    ip_address VARCHAR(45),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE suggestions (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT(20) NOT NULL,
    page_name VARCHAR(255) NOT NULL,
    suggestion TEXT NOT NULL,
    creation_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status TINYINT(1) DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- ALTER TABLE users_data ADD COLUMN first_time_login TINYINT(1) DEFAULT 1; 
-- Hostinger:
-- Nombre de la base de datos: 
-- Usuario de la base de datos: 
-- Contraseña de la base de datos: 