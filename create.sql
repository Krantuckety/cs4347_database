CREATE DATABASE IF NOT EXISTS cs4347_database;
USE cs4347_database;

-- Ignore Foreign Key relationships
SET FOREIGN_KEY_CHECKS = 0;

-- Drop statements to delete tables if already created
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS RolePermission;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Supplier;
DROP TABLE IF EXISTS Inventory;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS OrderContents;
DROP TABLE IF EXISTS Shipments;
DROP TABLE IF EXISTS ShipmentContents;

-- Set to acknowledge Foreign Key relationships
SET FOREIGN_KEY_CHECKS = 1;

-- Create table: Users
CREATE TABLE Users 
(
    userID INT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'customer'
);

CREATE TABLE RolePermission
(
    role VARCHAR(50) PRIMARY KEY,
    permissionLevel INT GENERATED ALWAYS AS (
        CASE
            WHEN role = 'administrator' THEN 1
            ELSE 0
        END
    ) STORED
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

-- Create table: Supplier
CREATE TABLE Supplier
(
    supplierID INT PRIMARY KEY,
    SupplierName VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL,
    ContactInfo VARCHAR(255) NOT NULL
);

-- Create table: Inventory
CREATE TABLE Inventory
(
    productID INT NOT NULL,
    inventoryID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    location VARCHAR(255) NOT NULL,
    lastUpdated DATE NOT NULL DEFAULT '2000-01-01',
    PRIMARY KEY (productID, inventoryID),
    FOREIGN KEY (productID) REFERENCES Product(productID) ON DELETE CASCADE
);

-- Create table: Orders
CREATE TABLE Orders
(
    orderID INT PRIMARY KEY,
    userID INT NOT NULL,
    orderDate DATE,
    deliveryDate DATE,
    status VARCHAR(50),
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE
);

-- Create table: OrderContents
CREATE TABLE OrderContents
(
    orderID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    PRIMARY KEY (orderID, productID),
    FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES Product(productID) ON DELETE CASCADE
);

-- Create table: Shipments
CREATE TABLE Shipments
(
    shipmentID INT PRIMARY KEY,
    supplierID INT NOT NULL,
    destination VARCHAR(255) NOT NULL,
    datePurchased DATE NOT NULL DEFAULT '2000-01-01',
    status VARCHAR(255),
    FOREIGN KEY (supplierID) REFERENCES Supplier(supplierID) ON DELETE CASCADE
);

-- Create table: OrderContents
CREATE TABLE ShipmentContents
(
    shipmentID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    PRIMARY KEY (shipmentID, productID),
    FOREIGN KEY (shipmentID) REFERENCES Shipments(shipmentID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES Product(productID) ON DELETE CASCADE
);
