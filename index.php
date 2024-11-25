<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna - Sklep internetowy</title>
    <link rel="stylesheet" href="style.css">
    <?php session_start(); ?>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <h1>Sklep Internetowy</h1>
            </div>
            <br><br>
            <nav class="nav-center">
                <ul>
                    <li class="active">
                        <a href="index.php"><img src="media/home.svg" alt="Główna" class="icon"> Strona główna</a>
                    </li>
                    <li>
                        <a href="pages/wishlist.php"><img src="media/heart.svg" alt="Lista życzeń" class="icon"> Lista życzeń</a>
                    </li>
                    <li>
                        <a href="pages/cart.php"><img src="media/shopping-cart.svg" alt="Koszyk" class="icon"> Koszyk</a>
                    </li>
                    <li>
                        <a href="pages/shippings.php"><img src="media/box-open.svg" alt="Zamówienia" class="icon"> Zamówienia</a>
                    </li>
                </ul>
            </nav>
            <br><br>
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
        <br><br><br>
        <div class="polecane-container">
            <h1>Polecane</h1>
            <div class="produkty-grid">

                <?php 
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "sklep";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT id_produktu, nazwa, cena, zdjecie FROM `produkty` ORDER BY ilosc_zakupow DESC LIMIT 4;";
                        $result = $conn->query($sql);
                        $currentDate = new DateTime();
                        $currentDate->modify('+3 days');

                        $deliveryDate = $currentDate->format('d-m-Y');



                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $id_produktu = $row['id_produktu'];
                                $nazwa = $row['nazwa'];
                                $cena = $row['cena'];
                                $zdjecie = $row['zdjecie'];

                                echo "<div class='produkt'>";
                                echo "<img src='media/img/" . $zdjecie . "' alt='" . $nazwa . "' />";
                                echo "<h2>" . $nazwa . "</h2>";
                                echo "<p class='cena'>Cena: " . $cena . " zł</p>";
                                echo "<p class='dostawa'>Dostawa do " .$deliveryDate. "</p>";
                                echo "<ul>";
                                echo "<li><a href='../pages/cartadd.php?add=" . $id_produktu . "'><img src='media/plus-small.svg'/></a></li>";
                                echo "<li><a href='sklep internetowy na nowo/pages/wishlistadd.php?add=" . $id_produktu . "'><img src='media/heart.svg'/></a></li>";
                                echo "</ul>";
                                echo "</div>";
                            }
                        } else {
                            echo "Brak produktów.";
                        }


                        $conn->close();
                
                
                ?>
                
            </div>
        </div>
        <br><br><br>
        <div class="polecane-container">
            <h1>Wszystkie produkty</h1>
            <div class="produkty-grid">
                <?php 
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "sklep";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT nazwa, cena, zdjecie FROM `produkty`;";
                        $result = $conn->query($sql);
                        
                        $currentDate = new DateTime();
                        $currentDate->modify('+3 days');

                        $deliveryDate = $currentDate->format('d-m-Y');


                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $nazwa = $row['nazwa'];
                                $cena = $row['cena'];
                                $zdjecie = $row['zdjecie'];

                                echo "<div class='produkt'>";
                                echo "<img src='media/img/" . $zdjecie . "' alt='" . $nazwa . "' />";
                                echo "<h2>" . $nazwa . "</h2>";
                                echo "<p class='cena'>Cena: " . $cena . " zł</p>";
                                echo "<p class='dostawa'>Dostawa do " .$deliveryDate. "</p>";
                                echo "<ul>";
                                echo "<li><a href=''><img src='media/plus-small.svg'/></a></li>";
                                echo "<li><a href=''><img src='media/heart.svg'/></a></li>";
                                echo "</ul>";
                                echo "</div>";
                            }
                        } else {
                            echo "Brak produktów.";
                        }


                        $conn->close();
                
                
                ?>
                
            </div>
        </div>
    </main>
    <br><br><br>
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
                <img src="media/facebook (1).svg" alt="Facebook" class="social-icon">
                <img src="media/instagram (1).svg" alt="Instagram" class="social-icon">
                <img src="media/twitter-alt-circle.svg" alt="Twitter" class="social-icon">
                <img src="media/youtube.svg" alt="YouTube" class="social-icon">
            </div>
            <p class="email">pomoc@sklepinternetowy.pl</p>
        </div>
</footer>
</body>
</html>