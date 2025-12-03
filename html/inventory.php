<?php
require_once "../includes/config.php";

$sql = "
    SELECT 
        Inventory.inventoryID,
        Inventory.productID,
        Inventory.quantity,
        Inventory.location,
        Inventory.lastUpdated
    FROM Inventory
    ORDER BY Inventory.inventoryID DESC, Inventory.productID DESC
";

$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 8px;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>Inventory ID</th>
            <th>Product ID</th>
            <th>Quantity</th>
            <th>Location</th>
            <th>Last Updated</th>
        </tr>
    </thead>
    <tbody>

<?php
if ($result->num_rows === 0) {
    echo "
    <tr>
        <td colspan='5' style='text-align:center; font-style:italic;'>
            No inventory records found.
        </td>
    </tr>";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "
        <tr>
            <td>{$row['inventoryID']}</td>
            <td>{$row['productID']}</td>
            <td>{$row['quantity']}</td>
            <td>{$row['location']}</td>
            <td>{$row['lastUpdated']}</td>
        </tr>";
    }
}
?>

    </tbody>
</table>

</body>
</html>