<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarejestruj się - Sklep internetowy</title>
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
                <a href="../pages/login.html" class="auth-link">Zaloguj się / Zarejestruj się</a>
            </div>
        </div>
    </header>
    <main>
        <div class="register-container">
            <form action="../pages/register.php" method="post">
                <h1>Zarejestruj się</h1>
                <input type="text" name="imie" id="imie" autocomplete="name" placeholder="Imię" required>
                <input type="text" name="nazwisko" id="nazwisko" autocomplete="family-name" placeholder="Nazwisko" required>
                <input type="email" name="email" id="email" autocomplete="email" placeholder="Adres e-mail" required>
                <input type="tel" name="numer_telefonu" id="phone" placeholder="Numer telefonu" required>
                <input type="password" name="haslo" id="password" placeholder="Hasło" required>
                <input type="password" name="confirm-password" id="confirm-password" placeholder="Powtórz hasło" required>
                <input type="date" name="data_urodzenia" id="birthdate" placeholder="Data urodzenia" required>
                <select name="plec" id="gender" required>
                    <option value="" disabled selected>Wybierz płeć</option>
                    <option value="Mężczyzna">Mężczyzna</option>
                    <option value="Kobieta">Kobieta</option>
                </select>
                <button type="submit">Zarejestruj się</button>
                <p>Masz już konto? <a href="../pages/login.php">Zaloguj się</a></p>
                <script>
                    document.querySelector("form").addEventListener("submit", function(event) {
                        const imie = document.querySelector("#imie").value;
                        const nazwisko = document.querySelector("#nazwisko").value;
                        const email = document.querySelector("#email").value;
                        const password = document.querySelector("#password").value;
                        const confirmPassword = document.querySelector("#confirm-password").value;
                        const phone = document.querySelector("#phone").value;
                        const birthdate = document.querySelector("#birthdate").value;
                        
                        let errors = [];

                    
                        if (imie.length < 2 || imie.length > 40) {
                            errors.push("Imię musi mieć od 2 do 40 znaków.");
                        }

                        
                        if (nazwisko.length < 2 || nazwisko.length > 50) {
                            errors.push("Nazwisko musi mieć od 2 do 50 znaków.");
                        }

                        
                        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                        if (!emailRegex.test(email)) {
                            errors.push("Podaj prawidłowy adres e-mail.");
                        }

                        
                        const passwordRegex = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[A-Z])[A-Za-z\d!@#$%^&*]{8,20}$/;
                        if (!passwordRegex.test(password)) {
                            errors.push("Hasło musi zaczynać się z dużej litery, zawierać cyfry, znak specjalny, oraz mieć od 8 do 20 znaków.");
                        }

                        
                        if (password !== confirmPassword) {
                            errors.push("Hasła nie są zgodne.");
                        }

                        
                        const phoneRegex = /^\d{9}$/;
                        if (!phoneRegex.test(phone)) {
                            errors.push("Numer telefonu musi składać się z dokładnie 9 cyfr.");
                        }

                       
                        const birthDateObj = new Date(birthdate);
                        const today = new Date();
                        const age = today.getFullYear() - birthDateObj.getFullYear();
                        const ageDiffMonths = today.getMonth() - birthDateObj.getMonth();
                        const ageDiffDays = today.getDate() - birthDateObj.getDate();

                        if (age < 16 || (age === 16 && (ageDiffMonths < 0 || (ageDiffMonths === 0 && ageDiffDays < 0)))) {
                            errors.push("Musisz mieć co najmniej 16 lat, aby się zarejestrować.");
                        }

                        
                        if (errors.length > 0) {
                            event.preventDefault(); 
                            alert(errors.join("\n")); 
                        }
                    });


                </script>
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "sklep";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        if (isset($_POST['email']) && isset($_POST['imie']) && isset($_POST['nazwisko']) && isset($_POST['data_urodzenia']) && isset($_POST['numer_telefonu']) && isset($_POST['haslo'])) {
                            
                            $email = $_POST['email'];
                            $imie = $_POST['imie'];
                            $nazwisko = $_POST['nazwisko'];
                            $data_urodzenia = $_POST['data_urodzenia'];
                            $numer_telefonu = $_POST['numer_telefonu'];
                            $haslo = $_POST['haslo'];
                            $plec = $_POST['plec'];

                            $sql = "SELECT * FROM Klienci WHERE email='$email' OR numer_telefonu=$numer_telefonu";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<p>Już istnieje użytkownik o podanym adresie e-mail lub numerze telefonu.</p>";
                            } else {

                                $haslo_hash = password_hash($haslo, PASSWORD_BCRYPT);

                                $sql = "INSERT INTO Klienci (imie, nazwisko, email, data_urodzenia, numer_telefonu, plec, haslo) 
                                        VALUES ('$imie', '$nazwisko', '$email', '$data_urodzenia', '$numer_telefonu', '$plec', '$haslo_hash')";

                                if ($conn->query($sql) === TRUE) {
                                    echo "<p>Nowy klient został zarejestrowany pomyślnie!</p>";
                                } else {
                                    echo "Błąd: " . $sql . "<br>" . $conn->error;
                                }
                            }

                        } else {
                            echo "<p>Wszystkie pola muszą być wypełnione.</p>";
                        }

                        $conn->close();
                    }
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