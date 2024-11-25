<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamówienia - Sklep Internetowy</title>
    <link rel="stylesheet" href="../style.css">
    <?php 
    session_start();
    
    ?>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h1>Sklep Internetowy</h1>
            </div>
            <nav class="nav-center">
                <ul>
                    <li>
                        <a href="../index.php"><img src="../media/home.svg" alt="Główna" class="icon"> Strona główna</a>
                    </li>
                    <li>
                        <a href="../pages/wishlist.php"><img src="../media/heart.svg" alt="Lista życzeń" class="icon"> Lista życzeń</a>
                    </li>
                    <li>
                        <a href="../pages/cart.php"><img src="../media/shopping-cart.svg" alt="Koszyk" class="icon"> Koszyk</a>
                    </li>
                    <li class="active">
                        <a href="../pages/shippings.php"><img src="../media/box-open.svg" alt="Zamówienia" class="icon"> Zamówienia</a>
                    </li>
                </ul>
            </nav>
            <div class="auth-container">
                <?php if (empty($_SESSION)): ?>
                    <a href="pages/login.php" class="auth-link">Zaloguj się / Zarejestruj się</a>
                <?php else: ?>
                    <span>Witaj, <?php echo htmlspecialchars($_SESSION['user_name']) . " " . htmlspecialchars($_SESSION['user_surname']); ?></span>
                    <a href="pages/logout.php" class="auth-link">Wyloguj się</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>
        <div class="orders-container">
        <h2 class="orders-title">Zamówienia</h2>
        
        <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "sklep";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if (empty($_SESSION)) {
                    header("Location: ../pages/login.php");
                    exit();
            }
            else {
                $user_id = $_SESSION['user_id']; 

                $sql = "SELECT zamowienia.id_zamowienia, zamowienia.stan_realizacji, zamowienia.data_zamowienia, SUM(produkty.cena * zamowienia_produkty.ilosc) AS total
                FROM zamowienia 
                INNER JOIN zamowienia_produkty ON zamowienia.id_zamowienia = zamowienia_produkty.id_zamowienia
                INNER JOIN produkty ON zamowienia_produkty.id_produktu = produkty.id_produktu
                WHERE zamowienia.id_uzytkownika = $user_id
                GROUP BY zamowienia.id_zamowienia, zamowienia.stan_realizacji, zamowienia.data_zamowienia;";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {

                    while($order = $result->fetch_assoc()) {
                        $id_zamowienia = $order['id_zamowienia'];
                        $stan_realizacji = htmlspecialchars($order['stan_realizacji']);
                        $data_zamowienia = htmlspecialchars($order['data_zamowienia']);
                        $total = htmlspecialchars($order['total']);

                        echo "<div class='order-block'>";
                        echo "<div class='order-header'>";
                        echo "<p>ID Zamówienia: <strong>#$id_zamowienia</strong></p>";
                        echo "<p>Stan: <span class='status in-progress'>$stan_realizacji</span></p>";
                        echo "</div>";


                        $sql_products = "SELECT produkty.nazwa, produkty.cena, zamowienia_produkty.ilosc
                                        FROM zamowienia_produkty
                                        JOIN produkty ON zamowienia_produkty.id_produktu = produkty.id_produktu
                                        WHERE zamowienia_produkty.id_zamowienia = $id_zamowienia";
                        $result_products = $conn->query($sql_products);

                        echo "<div class='order-products'>";
                        if ($result_products->num_rows > 0) {
                            while($product = $result_products->fetch_assoc()) {
                                $nazwa = htmlspecialchars($product['nazwa']);
                                $cena = htmlspecialchars($product['cena']);
                                $ilosc = htmlspecialchars($product['ilosc']);
                                $product_total = $cena * $ilosc;

                                echo "<div class='order-product'>";
                                echo "<p class='product-name-zamowienia'>$nazwa</p>";
                                echo "<p class='product-quantity-zamowienia'>Ilość: $ilosc</p>";
                                echo "<p class='product-price-zamowienia'>$product_total zł</p>";
                                echo "</div>";
                            }
                        }
                        echo "</div>"; 

                        echo "<div class='order-summary'>";
                        echo "<p>Łącznie: $total zł</p>";
                        echo "<p>Data złożenia: $data_zamowienia</p>";
                        echo "</div>";
                        echo "</div>"; 
                    }
                } else {
                    echo "Brak zamówień.";
                }

                $conn->close();
            }

            
            ?>

        </div>
    </main>

    <footer>
        <div class="footer-container">
            <ul class="footer-links">
                <li><a href="#">O nas</a></li>
                <li><a href="#">Polityka zwrotów</a></li>
                <li><a href="#">Regulamin sklepu</a></li>
                <li><a href="#">Pliki cookies</a></li>
                <li><a href="#">Pomoc techniczna</a></li>
            </ul>
            <div class="social-media">
                <img src="../media/facebook (1).svg" alt="Facebook" class="social-icon">
                <img src="../media/instagram (1).svg" alt="Instagram" class="social-icon">
                <img src="../media/twitter-alt-circle.svg" alt="Twitter" class="social-icon">
                <img src="../media/youtube.svg" alt="YouTube" class="social-icon">
            </div>
            <p class="email">pomoc@sklepinternetowy.pl</p>
        </div>
</footer>
</body>
</html>