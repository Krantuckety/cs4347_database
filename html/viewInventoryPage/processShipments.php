<?php
require_once "../../includes/config.php";

// Start a transaction
$mysqli->begin_transaction();

try {

    // 1. Get all shipments that are not marked as Delivered
    $shipmentsQuery = $mysqli->query("
        SELECT * FROM Shipments 
        WHERE status != 'Delivered'
    ");

    while ($shipment = $shipmentsQuery->fetch_assoc()) {

        $shipmentID = $shipment['shipmentID'];
        $location   = $shipment['destination'];

        // 2. Get all products for this shipment
        $contentsQuery = $mysqli->prepare("
            SELECT productID, quantity 
            FROM ShipmentContents
            WHERE shipmentID = ?
        ");
        $contentsQuery->bind_param("i", $shipmentID);
        $contentsQuery->execute();
        $contentsResult = $contentsQuery->get_result();

        if ($contentsResult->num_rows === 0) {
            continue; // no contents → skip
        }

        // 3. Determine inventoryID for this shipment's location
        $locationCheck = $mysqli->prepare("
            SELECT inventoryID 
            FROM Inventory
            WHERE location = ?
            LIMIT 1
        ");
        $locationCheck->bind_param("s", $location);
        $locationCheck->execute();
        $locResult = $locationCheck->get_result();

        if ($locResult->num_rows > 0) {
            // Location already exists — reuse inventoryID
            $row = $locResult->fetch_assoc();
            $inventoryID = $row['inventoryID'];

        } else {
            // Location does NOT exist — create a new inventoryID
            $maxQuery = $mysqli->query("SELECT MAX(inventoryID) AS maxID FROM Inventory");
            $maxID = $maxQuery->fetch_assoc()['maxID'];
            $inventoryID = ($maxID !== null) ? $maxID + 1 : 30000001;

            // Insert placeholder row for new location
            $placeholder = $mysqli->prepare("
                INSERT INTO Inventory (productID, inventoryID, quantity, location, lastUpdated)
                VALUES (NULL, ?, 0, ?, CURDATE())
            ");
            $placeholder->bind_param("is", $inventoryID, $location);
            $placeholder->execute();
        }

        // 4. Process each product from the shipment
        while ($item = $contentsResult->fetch_assoc()) {
            $productID = $item['productID'];
            $qty       = $item['quantity'];

            // Check if product already exists at this inventoryID
            $productCheck = $mysqli->prepare("
                SELECT quantity
                FROM Inventory
                WHERE inventoryID = ? AND productID = ?
            ");
            $productCheck->bind_param("ii", $inventoryID, $productID);
            $productCheck->execute();
            $productResult = $productCheck->get_result();

            if ($productResult->num_rows > 0) {
                // Product exists — update quantity
                $update = $mysqli->prepare("
                    UPDATE Inventory
                    SET quantity = quantity + ?, lastUpdated = CURDATE()
                    WHERE inventoryID = ? AND productID = ?
                ");
                $update->bind_param("iii", $qty, $inventoryID, $productID);
                $update->execute();

            } else {
                // Product is new to this location — insert row
                $insert = $mysqli->prepare("
                    INSERT INTO Inventory (productID, inventoryID, quantity, location, lastUpdated)
                    VALUES (?, ?, ?, ?, CURDATE())
                ");
                $insert->bind_param("iiis", $productID, $inventoryID, $qty, $location);
                $insert->execute();
            }
        }

        // 5. Mark shipment as delivered
        $markDelivered = $mysqli->prepare("
            UPDATE Shipments
            SET status = 'Delivered'
            WHERE shipmentID = ?
        ");
        $markDelivered->bind_param("i", $shipmentID);
        $markDelivered->execute();
    }

    // All updates succeeded → commit
    $mysqli->commit();

    // Redirect back to inventory page
    header("Location: inventory.html");
    exit();

} catch (Exception $e) {

    // Something failed → roll back
    $mysqli->rollback();
    echo "Error processing shipments: " . $e->getMessage();
    exit();
}
?>