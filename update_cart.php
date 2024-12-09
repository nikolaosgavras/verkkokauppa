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

if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND session_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $_POST['quantity'], $_POST['cart_id'], $_SESSION['cart_id']);
    $stmt->execute();
}

$conn->close();
