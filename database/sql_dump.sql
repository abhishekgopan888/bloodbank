-- Basic SQL dump for main tables (generated for assessment)

CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  role VARCHAR(50),
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE blood_banks (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  location VARCHAR(255),
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE refrigerators (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  identifier VARCHAR(255),
  blood_bank_id BIGINT,
  status VARCHAR(50),
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE blood_bags (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  bag_number VARCHAR(255),
  blood_group VARCHAR(10),
  donor_name VARCHAR(255),
  collection_date DATE,
  expiry_date DATE,
  quantity INT,
  status VARCHAR(50),
  refrigerator_id BIGINT,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE temperature_logs (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  refrigerator_id BIGINT,
  temperature FLOAT,
  recorded_at DATETIME,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE alerts (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  refrigerator_id BIGINT,
  type VARCHAR(255),
  message TEXT,
  metadata JSON,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE TABLE blood_bank_user (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  blood_bank_id BIGINT,
  user_id BIGINT,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);
