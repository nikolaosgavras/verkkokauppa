<?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "verkkokauppa";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
            $sortOption = isset($_GET['sort']) ? (int)$_GET['sort'] : 0;

            $sql = "SELECT id, nimi, kuvaus, hinta, kuva_url FROM products";
            if ($category > 0) {
                $sql .= " WHERE kategoria = $category";
            }

            switch ($sortOption) {
                case 1:
                    $sql .= " ORDER BY hinta ASC";
                    break;
                case 2:
                    $sql .= " ORDER BY hinta DESC";
                    break;
                case 3:
                    $sql .= " ORDER BY nimi ASC";
                    break;
                case 4:
                    $sql .= " ORDER BY nimi DESC";
                    break;
                default:
                    break;
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<div class="row" id="productRow">';
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-3">';
                    echo '    <div class="card mb-4 shadow-sm">';
                    echo '        <img src="' . $row["kuva_url"] . '" class="card-img-top" alt="' . $row["nimi"] . '">';
                    echo '        <div class="card-body">';
                    echo '            <h5 class="card-title">' . $row["nimi"] . '</h5>';
                    echo '            <p class="card-text">' . $row["kuvaus"] . '</p>';
                    echo '            <span class="text-muted d-block mb-2" data-price="' . $row["hinta"] . '">€' . $row["hinta"] . '</span>';
                    echo '            <div class="d-flex justify-content-between align-items-center">';
                    echo '                <select class="form-select form-select-sm w-auto" id="quantity' . $row["id"] . '">';
                    echo '                    <option value="1">1</option>';
                    echo '                    <option value="2">2</option>';
                    echo '                    <option value="3">3</option>';
                    echo '                    <option value="4">4</option>';
                    echo '                    <option value="5">5</option>';
                    echo '                </select>';
                    echo '                <button class="btn btn-sm btn-outline-secondary btn-add-to-cart">Lisää ostoskoriin</button>';
                    echo '            </div>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo "0 results";
            }
            $conn->close();
?>