<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista życzeń - Sklep Internetowy</title>
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
                    <li class="active">
                        <a href="../pages/wishlist.php"><img src="../media/heart.svg" alt="Lista życzeń" class="icon"> Lista życzeń</a>
                    </li>
                    <li>
                        <a href="../pages/cart.php"><img src="../media/shopping-cart.svg" alt="Koszyk" class="icon"> Koszyk</a>
                    </li>
                    <li>
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
        
        <div class="wishlist-container">
            <h1 class="title">Lista życzeń</h1>

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
                } else {
                    $user_id = $_SESSION['user_id']; 
                    $sql = "SELECT SUM(cena) AS suma_cen FROM produkty 
                            INNER JOIN listazyczen ON produkty.id_produktu = listazyczen.id_produktu 
                            WHERE id_uzytkownika =".$user_id.";"; 

                    $result = $conn->query($sql);

                    if ($result) {
                        $row = $result->fetch_assoc(); 

                        $suma = $row['suma_cen'];
                        if(empty($suma)){
                            $suma = 0;
                        }
                        
                        
                        echo "<h2 class='summary'>Łącznie " . $suma . " zł</h2>";
                        echo "<br><br>";
                    } else {
                        echo "Błąd zapytania: " . $conn->error; 
                    }
                }

                $conn->close(); 
                ?>



            <?php 
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "sklep";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }


                        $user_id = $_SESSION['user_id']; 
                        $sql = "SELECT nazwa, cena, zdjecie, produkty.id_produktu FROM produkty INNER JOIN listazyczen ON produkty.id_produktu = listazyczen.id_produktu WHERE listazyczen.id_uzytkownika =".$user_id.";";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $nazwa = $row['nazwa'];
                                $cena = $row['cena'];
                                $zdjecie = $row['zdjecie'];
                                $id_produktu = $row['id_produktu'];

                                echo "<div class='wishlist-product'>";
                                echo "<img src='../media/img/" . $zdjecie . "' alt='" . $nazwa . "' />";
                                echo "<div class='product-info'>";
                                echo "<h2>" . $nazwa . "</h2>";
                                echo "<p class='wishlist-cena'>Cena: " . $cena . " zł</p>";
                                echo "<a href='../pages/remove_wishlist.php?id_produktu=" . $id_produktu . " ><button class='wishlist-delete'>Usuń z listy</a>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "Brak produktów.";
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