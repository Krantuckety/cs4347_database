<?php
session_start();

// 1. Ensure the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Capture form input
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 2. Connect to MySQL (XAMPP defaults)
    $conn = new mysqli("localhost", "root", "", "inventoryManager");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 3. Prepare SQL to check if username exists
    $stmt = $conn->prepare("SELECT userID, password, role FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    // 4. If username exists, verify password
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Your project uses plaintext passwords — so direct comparison:
        if ($password === $user["password"]) {

            // 5. Successful login → store user session data
            $_SESSION["userID"] = $user["userID"];
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $user["role"];

            // Redirect to dashboard
            header("Location: index.html");
            exit();
        } 
        else {
            // Incorrect password
            echo "<p style='color:red;'>Incorrect password.</p>";
            echo "<a href='login.html'>Try again</a>";
        }

    } else {
        // Username not found
        echo "<p style='color:red;'>Username not found.</p>";
        echo "<a href='login.html'>Try again</a>";
    }

    $stmt->close();
    $conn->close();
}
else {
    echo "Invalid request.";
}
?>