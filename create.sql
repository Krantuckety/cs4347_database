-- Drop statements to delete tables if already created
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Product;

-- Create table: Users
CREATE TABLE Users 
(
    UserID INT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'customer',
    permissionLevel INT NOT NULL DEFAULT 0
);

-- Create table: Product
CREATE TABLE Product
(
    productID INT PRIMARY KEY, 
    name VARCHAR(255), 
    description VARCHAR(255), 
    category VARCHAR(255),
    unitPrice DECIMAL(7,2) NOT NULL DEFAULT 0.00
);