<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Verkkokauppa</title>
</head>
<body>
<div class="main-content">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="row w-100">
                <div class="col-8 d-flex align-items-center">
                    <img src="tuottajamarketlogo.png" class="logo rounded">
                    <a href="index.html" id="headerButton" class="btn btn-primary rounded-5 ms-3">Etusivu</a>
                </div>
                <div class="col-4 d-flex justify-content-end align-items-center">
                    <a href="ostoskori.php" id="ostoskoriButton" class="btn btn-secondary rounded-5">Ostoskori</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-4" id="sortCategorySearch">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <select class="form-select" id="lajittele">
                    <option selected>Lajittele</option>
                    <option value="1">Hinta: Alin ensin</option>
                    <option value="2">Hinta: Korkein ensin</option>
                    <option value="3">Nimi: A-Z</option>
                    <option value="4">Nimi: Z-A</option>
                </select>
            </div>
            <div class="col-md-6">
                <form class="d-flex justify-content-center" role="search" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Hae tuotteita" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Hae</button>
                </form>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="tuotteenKategoria">
                    <option selected>Tuotteen kategoria</option>
                    <option value="1">Elektroniikka</option>
                    <option value="2">Vaatteet</option>
                    <option value="3">Koti ja puutarha</option>
                    <option value="4">Urheilu ja ulkoilu</option>
                </select>
            </div>
        </div>
    </div>
    <div class="container mt-4" id="categoryProductContainer">
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
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

            $sql = "SELECT id, nimi, kuvaus, hinta, kuva_url FROM products WHERE 1=1";
            if ($category > 0) {
                $sql .= " AND kategoria = $category";
            }
            if (!empty($search)) {
                $sql .= " AND (nimi LIKE '%$search%' OR kuvaus LIKE '%$search%')";
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
    </div>
</div>
<footer class="py-3 py-md-5 py-xl-6">
      <div class="container pt-3">
        <div class="row gy-3 align-items-center justify-content-center">
          <div class="col-12 col-lg-6 text-center">
            <div class="copyright-wrapper d-block mb-1 fs-8">
              <p>&copy; 2024 Tuottajamarket</p>
            </div>
          </div>
        </div>
      </div>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    function attachEventListeners() {
        document.querySelectorAll('.btn-outline-secondary').forEach(button => {
            button.addEventListener('click', function() {
                const button = this;
                const card = button.closest('.card');
                const productId = card.querySelector('select').id.replace('quantity', '');
                const quantity = card.querySelector('select').value;

                fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.classList.add('btn-success-transition');
                        setTimeout(() => {
                            button.classList.remove('btn-success-transition');
                        }, 1000);
                    } else {
                        alert('Error adding product to cart: ' + data.message);
                    }
                });
            });
        });

        document.querySelectorAll('.form-select').forEach(select => {
            select.addEventListener('change', function() {
                const card = this.closest('.card');
                const priceSpan = card.querySelector('.text-muted[data-price]');
                const basePrice = parseFloat(priceSpan.getAttribute('data-price'));
                const quantity = parseInt(this.value);
                const newPrice = (basePrice * quantity).toFixed(2);
                priceSpan.textContent = `€${newPrice}`;
            });
        });
    }

    function getCurrentSortOption() {
        return document.getElementById('lajittele').value;
    }

    document.getElementById('tuotteenKategoria').addEventListener('change', function() {
        const category = this.value;
        const sortOption = getCurrentSortOption();
        const productContainer = document.getElementById('productRow');

        fetch(`kategoria.php?category=${category}&sort=${sortOption}`)
            .then(response => response.text())
            .then(html => {
                console.log(html);
                productContainer.outerHTML = html;
                attachEventListeners(); // Reattach event listeners after updating content
            });
    });

    document.getElementById('lajittele').addEventListener('change', function() {
        const sortOption = this.value;
        const productContainer = document.getElementById('productRow');
        const products = Array.from(productContainer.getElementsByClassName('col-md-3'));

        products.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('[data-price]').getAttribute('data-price'));
            const priceB = parseFloat(b.querySelector('[data-price]').getAttribute('data-price'));
            const nameA = a.querySelector('.card-title').textContent.toUpperCase();
            const nameB = b.querySelector('.card-title').textContent.toUpperCase();

            switch (sortOption) {
                case '1': // Hinta: Alin ensin
                    return priceA - priceB;
                case '2': // Hinta: Korkein ensin
                    return priceB - priceA;
                case '3': // Nimi: A-Z
                    return nameA.localeCompare(nameB);
                case '4': // Nimi: Z-A
                    return nameB.localeCompare(nameA);
                default:
                    return 0;
            }
        });

        productContainer.innerHTML = '';    
        products.forEach(product => productContainer.appendChild(product));
        attachEventListeners(); // Reattach event listeners after sorting
    });

    // Initial call to attach event listeners
    attachEventListeners();
</script>
</body>
</html>