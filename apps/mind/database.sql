CREATE DATABASE sb_mind;
USE sb_mind;



CREATE TABLE patients (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT(20) NOT NULL,
    patient_name VARCHAR(255) NOT NULL,
    patient_birthdate DATE,
    patient_school TEXT,
    patient_school_grade TEXT,
    patient_contact_phone TEXT,
    patient_contact_email TEXT,
    patient_gender TEXT,
    patient_status TINYINT(1) DEFAULT 1,
    patient_notes TEXT,
    patient_appt_price DECIMAL(10,2) DEFAULT 0,
    row_status TINYINT(1) DEFAULT 1
);

CREATE TABLE appointments (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT(20) NOT NULL,
    patient_id BIGINT(20) NOT NULL,
    appt_date DATE NOT NULL,
    appt_time TIME NOT NULL,
    appt_duration TIME DEFAULT '01:00:00', -- Default 1 hour
    appt_status TINYINT(1) DEFAULT 1,
    appt_cost DECIMAL(10,2) DEFAULT 0,
    appt_concept TEXT,
    appt_price DECIMAL(10,2) DEFAULT 0,
    appt_mode TINYINT(1) DEFAULT 1,
    appt_payment_status TINYINT(1) DEFAULT 1,
    row_status TINYINT(1) DEFAULT 1,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);


CREATE TABLE actions (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    action_name VARCHAR(255) NOT NULL,
    action_description TEXT
);

CREATE TABLE permissions (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT(20) NOT NULL,
    action_id BIGINT(20) NOT NULL,
    owner_id BIGINT(20) NOT NULL,
    FOREIGN KEY (action_id) REFERENCES actions(id) ON DELETE CASCADE
);
CREATE TABLE action_logs (
    id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT(20) NOT NULL,
    owner_id BIGINT(20) NOT NULL,
    action_id BIGINT(20) NOT NULL,
    action_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    target_id BIGINT(20) NOT NULL,
    target_type VARCHAR(255) NOT NULL,
    changes JSON,
    FOREIGN KEY (action_id) REFERENCES actions(id) ON DELETE CASCADE
);

-- Already applied
ALTER TABLE patients ADD COLUMN patient_appt_price DECIMAL(10,2) DEFAULT 0 AFTER patient_notes;
ALTER TABLE appointments ADD COLUMN appt_price DECIMAL(10,2) DEFAULT 0 AFTER appt_concept;
ALTER TABLE appointments ADD COLUMN appt_mode TINYINT(1) DEFAULT 1 AFTER appt_price;
ALTER TABLE appointments ADD COLUMN appt_payment_status TINYINT(1) DEFAULT 1 AFTER appt_mode;
ALTER TABLE appointments ADD COLUMN appt_duration TIME DEFAULT '01:00:00' AFTER appt_time; 

-- pending
ALTER TABLE patients MODIFY COLUMN patient_school TEXT;
ALTER TABLE patients MODIFY COLUMN patient_school_grade TEXT;
ALTER TABLE patients MODIFY COLUMN patient_contact_phone TEXT;
ALTER TABLE patients MODIFY COLUMN patient_contact_email TEXT;
ALTER TABLE patients MODIFY COLUMN patient_gender TEXT;



