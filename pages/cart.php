<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk - Sklep Internetowy</title>
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
                    <li class="active">
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
        <div class="cart-container">
            <h2 class="cart-title">Twój koszyk</h2>
            
            <div class="cart-products">

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
                    else{
                        $user_id = $_SESSION['user_id']; 
                        $sql = "SELECT nazwa, cena, zdjecie, produkty.id_produktu, koszyk.id_przedmiotu FROM produkty INNER JOIN koszyk ON produkty.id_produktu = koszyk.id_produktu WHERE koszyk.id_klienta = $user_id;";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $nazwa = htmlspecialchars($row['nazwa']); 
                                $cena = htmlspecialchars($row['cena']);
                                $zdjecie = htmlspecialchars($row['zdjecie']);
                                $id_przedmiotu = htmlspecialchars($row['id_przedmiotu']);

                                echo "<div class='cart-product'>";
                                echo "<div class='product-item'>";
                                echo "<img src='../media/img/" . $zdjecie . "' alt='" . $nazwa . "' />";
                                echo "<div class='product-details'>";
                                echo "<h3>" . $nazwa . "</h3>"; 
                                echo "<p class='product-price'>" . $cena . " zł</p>";
                                echo "</div>";
                                echo "</div>";
                                echo "<div class='product-quantity'>";
                                echo "</div>";
                                echo "<div class='product-total'>";
                                echo "<p>" . $cena . " zł</p>"; 
                                echo "</div>";
                                echo "<div class='product-remove'>";
                                echo "<a href='../pages/cart-remove.php?remove=" . $id_przedmiotu . "' class='remove-button'>&times;</a>"; 
                                echo "</div>";
                                echo "</div>";
                            }
                        } 
                        else{
                            echo "Brak produktów.";
                        }

                        $conn->close(); 
                    }
                    
                ?>

                
            </div>

            <div class="cart-summary">
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
                    $sql = "SELECT SUM(cena) AS suma_cen FROM produkty INNER JOIN koszyk ON produkty.id_produktu = koszyk.id_produktu WHERE id_klienta =".$user_id.";";
                    $result = $conn->query($sql);
                    

                    if ($result) {
                        $row = $result->fetch_assoc(); 

                        $suma = $row['suma_cen'];
                        $_SESSION['suma'] = $row['suma_cen'];
                        if(empty($suma)){
                            $suma = 0;
                        }
                        
                        echo "<p>Łącznie: <span>".$suma." zł</span></p>";

                    } else {
                        echo "Błąd zapytania: " . $conn->error; 
                    }
                ?>
            </div>

            <div class="cart-actions">
                <a href="../index.php"><button class="continue-shopping">Kontynuuj zakupy</button></a>
                <a href="../pages/payment.php"><button class="checkout">Przejdź do płatności</button></a>
            </div>
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