<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "verkkokauppa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['cart_id'])) {
    $sql = "DELETE FROM cart WHERE session_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $_SESSION['cart_id']);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: kiitos.html");
exit();
?>