USE cs4347_database;

INSERT INTO Users (userID, username, email, password, role) VALUES
(1, 'John Doe', 'johndoe@gmail.com', '123wasd', 'customer'),
(2, 'Jane Duo', 'janeduo@gmail.com', '234wasd', 'customer'),
(12, 'Henry Sikes', 'hsikes@yahoo.com', 'K@nUdoit?', 'customer'),
(12345678, 'Adminus Unus', 'adminguy@yahoo.com', '123wasd', 'administrator');

INSERT INTO RolePermission (role) VALUES
('customer'),
('administrator');

INSERT INTO Product (productID, name, description, category, unitPrice) VALUES
(10000012, 'Monopoly', 'A board game', 'Toys', 25.0),
(10000167, 'Mirror', 'A 4ft x 3ft mirror', 'Decor', 75.0),
(10000234, 'iPhone', 'iPhone 16 Pro', 'Technology', 800.0);

INSERT INTO Supplier (supplierID, SupplierName, Address, ContactInfo) VALUES
(20000001, 'Acme Distribution', '123 Industrial Way', 'acme-support@example.com'),
(20000002, 'NorthStar Logistics', '87 Harbor Road', 'contact@northstarlogi.com'),
(20000003, 'TechSource Importers', '455 Silicon Avenue', 'info@techsource.io');

INSERT INTO Inventory (productID, inventoryID, quantity, location, lastUpdated) VALUES
(10000012, 30000001, 42, 'Dallas Warehouse A', '2025-10-15'),
(10000167, 30000002, 15, 'Houston Storage B', '2025-09-10'),
(10000234, 30000003, 8, 'Austin Tech Hub',  '2025-10-01');


INSERT INTO Orders (orderID, userID, orderDate, deliveryDate, status) VALUES
(40000001, 1, '2025-01-10', '2025-01-18', 'Processing'),
(40000002, 2, '2025-01-12', '2025-01-20', 'Shipped'),
(40000003, 1, '2025-01-14', '2025-01-22', 'Delivered');

INSERT INTO OrderContents (orderID, productID, quantity) VALUES
(40000001, 10000012, 2),
(40000001, 10000167, 1),
(40000002, 10000234, 1),
(40000003, 10000012, 3);

INSERT INTO Shipments (shipmentID, supplierID, destination, datePurchased, status) VALUES
(50000001, 20000001, 'Dallas Warehouse A', '2025-01-09', 'In Transit'),
(50000002, 20000002, 'Houston Storage B', '2025-01-11', 'Delivered'),
(50000003, 20000003, 'Austin Tech Hub', '2025-01-13', 'Processing');

INSERT INTO ShipmentContents (shipmentID, productID, quantity) VALUES
(50000001, 10000012, 10),
(50000001, 10000167, 4),
(50000002, 10000234, 6),
(50000003, 10000012, 12);
