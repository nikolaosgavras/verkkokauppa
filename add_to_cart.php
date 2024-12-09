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

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (!isset($_SESSION['cart_id'])) {
    $_SESSION['cart_id'] = session_id();
}

$session_id = $_SESSION['cart_id'];

if ($product_id > 0 && $quantity > 0) {
    // Check if the product already exists in the cart
    $sql = "SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $session_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Product exists in the cart, update the quantity
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        $cart_id = $row['id'];

        $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_quantity, $cart_id);
        $update_stmt->execute();
    } else {
        // Product does not exist in the cart, insert a new entry
        $insert_sql = "INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sii", $session_id, $product_id, $quantity);
        $insert_stmt->execute();
    }

    $stmt->close();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid product or quantity']);
}

$conn->close();
?>