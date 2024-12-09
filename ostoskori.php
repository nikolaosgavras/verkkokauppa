<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ostoskori</title>
</head>
<body>
<div class="main-content">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="row w-100">
                <div class="col-8 d-flex align-items-center">
                    <img src="tuottajamarketlogo.png" class="logo rounded">
                    <a href="verkkokauppa.php" id="headerButton" class="btn btn-primary rounded-5 ms-3">Verkkokauppa</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Ostoskori</h2>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "verkkokauppa";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_SESSION['cart_id'])) {
            $session_id = $_SESSION['cart_id'];
            $sql = "SELECT p.*, c.quantity, c.id as cart_item_id 
                    FROM cart c 
                    JOIN products p ON c.product_id = p.id 
                    WHERE c.session_id = ?";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $session_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $total = 0;

                if ($result->num_rows > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table">';
                    echo '<thead><tr><th>Tuote</th><th>Hinta</th><th>Määrä</th><th></th></thead>';
                    echo '<tbody>';
                    
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td class="align-middle" style="width: 50%;">
                                <div class="d-flex align-items-center">
                                    <img src="' . $row['kuva_url'] . '" class="product-img me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                    <span>' . $row['nimi'] . '</span>
                                </div>
                              </td>';
                        echo '<td class="align-middle">' . number_format($row['hinta'], 2) . ' €</td>';
                        echo '<td class="align-middle" style="width: 150px;">
                                <select class="form-select quantity-select" data-cart-id="' . $row['cart_item_id'] . '">';
                        for ($i = 1; $i <= 10; $i++) {
                            echo '<option value="' . $i . '"' . ($row['quantity'] == $i ? ' selected' : '') . '>' . $i . '</option>';
                        }
                        echo '</select></td>';
                        echo '<td class="align-middle text-end">
                                <button class="btn btn-danger remove-item" data-cart-id="' . $row['cart_item_id'] . '">
                                    <i class="bi bi-trash"></i> Poista
                                </button>
                              </td>';
                        echo '</tr>';
                        
                        $total += $row['hinta'] * $row['quantity'];
                    }
                    
                    echo '</tbody>';
                    echo '<tfoot>
                            <tr>
                                <td colspan="2" class="text-start"><strong>Yhteensä: ' . number_format($total, 2) . ' €</strong></td>
                                <td colspan="2" class="text-end">
                                        <a href="clear_cart.php" class="btn btn-primary rounded-5">Siirry kassalle</a>
                                    </form>
                                </td>
                            </tr>
                          </tfoot>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p>Ostoskori on tyhjä</p>';
                }
                $stmt->close();
            } else {
                echo '<div class="alert alert-danger">Error preparing statement</div>';
            }
        } else {
            echo '<div class="alert alert-info">Ostoskorisi on tyhjä</div>';
        }
        $conn->close();
        ?>
    </div>
</div>
<footer class="py-3 mt-4">
    <div class="container">
        <p class="text-center">&copy; 2024 Tuottajamarket</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.quantity-select').change(function() {
        const cartId = $(this).data('cart-id');
        const quantity = $(this).val();
        
        $.ajax({
            url: 'update_cart.php',
            method: 'POST',
            data: { cart_id: cartId, quantity: quantity },
            success: function(response) {
                location.reload();
            }
        });
    });

    $('.remove-item').click(function() {
        const cartId = $(this).data('cart-id');
        
        $.ajax({
            url: 'remove_cart_item.php',
            method: 'POST',
            data: { cart_id: cartId },
            success: function(response) {
                location.reload();
            }
        });
    });

    // Add this to your existing JavaScript
    $('#exportCart').click(function() {
        $.ajax({
            url: 'ostoskori.php',
            method: 'POST',
            data: { export_cart: true },
            success: function(response) {
                alert('Cart exported to file: ' + response);
            }
        });
    });
});
</script>
</body>
</html>