<?php
require_once "../../includes/config.php";

// START TRANSACTION
$mysqli->begin_transaction();

try {
    $mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

    // 1. Clear tables
    $mysqli->query("TRUNCATE TABLE ShipmentContents");
    $mysqli->query("TRUNCATE TABLE Shipments");
    $mysqli->query("TRUNCATE TABLE Inventory");

    $mysqli->query("SET FOREIGN_KEY_CHECKS = 1");

    // 2. Re-load inventory from load.sql version

    // RE-INSERT INVENTORY (Update with your exact load.sql values)
    $mysqli->query("INSERT INTO Inventory (productID, inventoryID, quantity, location, lastUpdated) VALUES
        (10000012, 30000001, 42, 'Dallas Warehouse A', '2025-10-15'),
        (10000167, 30000002, 15, 'Houston Storage B', '2025-09-10'),
        (10000234, 30000003,  8, 'Austin Tech Hub', '2025-10-18')
    ");

    $mysqli->query("
    INSERT INTO Shipments (shipmentID, supplierID, destination, datePurchased, status) VALUES
    (50000001, 20000001, 'Dallas Warehouse A', '2025-01-09', 'In Transit'),
    (50000002, 20000002, 'Houston Storage B', '2025-01-11', 'Delivered'),
    (50000003, 20000003, 'Austin Tech Hub', '2025-01-13', 'Processing')
    ");

    $mysqli->query("
    INSERT INTO ShipmentContents (shipmentID, productID, quantity) VALUES
    (50000001, 10000012, 10),
    (50000001, 10000167, 4),
    (50000002, 10000234, 6),
    (50000003, 10000012, 12)
    ");

    // COMMIT CHANGES
    $mysqli->commit();

    // Redirect back to inventory
    header("Location: ../viewInventoryPage/inventory.html");
    exit();

} catch (Exception $e) {
    $mysqli->rollback();
    echo "Error resetting inventory: " . $e->getMessage();
    exit();
}
?>