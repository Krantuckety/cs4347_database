-- Drop statements to delete tables if already created
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS OrderProduct;
DROP TABLE IF EXISTS ShipmentProduct;
DROP TABLE IF EXISTS InventoryOrder;
DROP TABLE IF EXISTS ProductInventory;
-- Create table: Supplier
CREATE TABLE Supplier
(
    supplierID INT PRIMARY KEY,
    SupplierName VARCHAR(255),
    Address VARCHAR(255),
    ContactInfo VARCHAR(255)
);

-- Create table: Inventory
CREATE TABLE Inventory
(
    inventoryID INT PRIMARY KEY,
    ProductID INT,
    Quantity INT DEFAULT 0,
    Location VARCHAR(255),
    LastUpdated DATE
);

-- Create table: Users
CREATE TABLE Users 
(
    userID INT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'customer',
    CHECK (role IN ('customer', 'administrator')),
    permissionLevel INT AS
    (
        CASE
            WHEN role = 'administrator' THEN 1
            ELSE 0
        END
    ) STORED
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

-- Create table: Shipments
CREATE TABLE Shipments
(
    shipmentID INT PRIMARY KEY,
    supplierID INT NOT NULL,
    shipmentDate DATE,
    status VARCHAR(255),
    FOREIGN KEY (supplierID) REFERENCES Supplier(supplierID) ON DELETE CASCADE
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

-- Create table: OrderProduct
CREATE TABLE OrderProduct
(
    orderID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    PRIMARY KEY (orderID, productID),
    FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES Product(productID) ON DELETE CASCADE
);

-- Create table: ShipmentProduct
CREATE TABLE ShipmentProduct
(
    shipmentID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    PRIMARY KEY (shipmentID, productID),
    FOREIGN KEY (shipmentID) REFERENCES Shipments(shipmentID) ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES Product(productID) ON DELETE CASCADE
);

-- Create table: InventoryOrder
CREATE TABLE InventoryOrder
(
    inventoryID INT NOT NULL,
    orderID INT NOT NULL,
    PRIMARY KEY (inventoryID, orderID),
    FOREIGN KEY (inventoryID) REFERENCES Inventory(inventoryID) ON DELETE CASCADE,
    FOREIGN KEY (orderID) REFERENCES Orders(orderID) ON DELETE CASCADE
);

-- Create table: ProductInventory
CREATE TABLE ProductInventory
(
    productID INT NOT NULL,
    inventoryID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    PRIMARY KEY (productID, inventoryID),
    FOREIGN KEY (productID) REFERENCES Product(productID) ON DELETE CASCADE,
    FOREIGN KEY (inventoryID) REFERENCES Inventory(inventoryID) ON DELETE CASCADE
);
