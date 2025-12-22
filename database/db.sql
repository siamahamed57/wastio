CREATE DATABASE IF NOT EXISTS recycling_platform;
USE recycling_platform;

CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO roles (role_name) VALUES
('Waste Seller'),
('Waste Buyer'),
('System Admin'),
('Collection Agent');

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    address TEXT,
    is_approved BOOLEAN DEFAULT FALSE,
    is_blocked BOOLEAN DEFAULT FALSE,
    availability_status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE TABLE waste_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE waste_items (
    waste_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    status ENUM('Available','Requested','Sold') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (seller_id) REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES waste_categories(category_id)
);

CREATE TABLE buy_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    waste_id INT NOT NULL,
    buyer_id INT NOT NULL,
    status ENUM('Pending','Accepted','Rejected','Cancelled') DEFAULT 'Pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (waste_id) REFERENCES waste_items(waste_id),
    FOREIGN KEY (buyer_id) REFERENCES users(user_id)
);

CREATE TABLE collection_requests (
    collection_id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    agent_id INT NOT NULL,
    pickup_status ENUM('Assigned','Picked Up','Completed','Issue') DEFAULT 'Assigned',
    pickup_date DATE,
    remarks TEXT,

    FOREIGN KEY (request_id) REFERENCES buy_requests(request_id),
    FOREIGN KEY (agent_id) REFERENCES users(user_id)
);

CREATE TABLE collection_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    collection_id INT NOT NULL,
    status VARCHAR(50),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (collection_id) REFERENCES collection_requests(collection_id)
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE VIEW admin_dashboard_stats AS
SELECT
    (SELECT COUNT(*) FROM users) AS total_users,
    (SELECT COUNT(*) FROM waste_items) AS total_waste_items,
    (SELECT COUNT(*) FROM buy_requests) AS total_buy_requests;
