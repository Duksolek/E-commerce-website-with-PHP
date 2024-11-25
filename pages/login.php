<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaloguj się - Sklep internetowy</title>
    <link rel="stylesheet" href="../style.css">
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
                    <li>
                        <a href="../index.php"><img src="../media/home.svg" alt="Główna" class="icon"> Strona główna</a>
                    </li>
                    <li>
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
            <br><br>
            <div class="auth-container">
                <a href="../pages/login.php" class="auth-link">Zaloguj się / Zarejestruj się</a>
            </div>
        </div>
    </header>
    <main>
        <div class="login-container">
            <form action="../pages/login.php" method="post">
                <h1>Zaloguj się</h1>
                <input type="email" name="email" id="email" autocomplete="email" placeholder="Adres e-mail" required>
                <input type="password" name="haslo" id="password" placeholder="Hasło" required>
                <p class="niepamietasz"><a href="">Nie pamiętasz hasła?</a></p>
                <button type="submit">Zaloguj się</button>
                <p>Nie masz konta? <a href="../pages/register.php">Utwórz konto</a></p>

                <?php 

                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "sklep";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Połączenie nieudane: " . $conn->connect_error);
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        
                        $email = $_POST['email'];
                        $haslo = $_POST['haslo'];

                        $sql = "SELECT * FROM Klienci WHERE email='$email'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();

                            if($haslo == $row['haslo']){
                                session_start();

                                $_SESSION['user_id'] = $row['id_uzytkownika'];
                                $_SESSION['user_email'] = $row['email']; 
                                $_SESSION['user_name'] = $row['imie']; 
                                $_SESSION['user_surname'] = $row['nazwisko'];

                                header("Location: ../index.php");
                                exit();
                            } else {
                                echo "Hasło niepoprawne.";
                            }
                        } else {
                            echo "Użytkownik nie znaleziony.";
                        }
                    }

                    $conn->close();
                ?>




            </form>
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