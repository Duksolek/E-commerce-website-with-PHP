<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dostawa i płatność - Sklep Internetowy</title>
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
        <div class="payment-page-container">
            <h2 class="payment-page-title">Dostawa i płatność</h2>

            <div class="payment-form-container">
                <form action="payment.php" method="POST" class="payment-form">
                    <fieldset class="customer-details">
                        <legend>Dane Odbiorcy</legend>
                        <div class="form-group">
                            <input type="text" id="street" name="street" placeholder="Ulica" required>
                        </div>
                        <div class="form-group">
                            <input type="text" id="house-number" name="house-number" placeholder="Numer domu" required>
                        </div>
                        <div class="form-group">
                            
                            <input type="text" id="city" name="city" placeholder="Miasto" required>
                        </div>
                        <div class="form-group">
                            
                            <input type="text" id="postal-code" name="postal-code" placeholder="Kod pocztowy" required>
                        </div>
                        <div class="form-group">
                            
                            <input type="text" id="country" name="country" placeholder="Kraj" required>
                        </div>
                    </fieldset>

                    <fieldset class="delivery-options">
                        <legend>Metoda dostawy</legend>
                        <div class="form-group">
                            <input type="radio" id="inpost" name="delivery-method" value="inpost" required>
                            <label for="inpost">Kurier InPost</label>
                        </div>
                    </fieldset>

                    <fieldset class="payment-options">
                        <legend>Metoda płatności</legend>
                        <div class="form-group">
                            <input type="radio" id="credit-card" name="payment-method" value="credit-card" required>
                            <label for="credit-card">Karta kredytowa</label>
                        </div>
                        <div class="form-group">
                            <input type="radio" id="paypal" name="payment-method" value="paypal" required>
                            <label for="paypal">PayPal</label>
                        </div>
                        <div class="form-group">
                            <input type="radio" id="blik" name="payment-method" value="blik" required>
                            <label for="blik">BLIK</label>
                        </div>
                        <div class="form-group">
                            <input type="radio" id="apple-pay" name="payment-method" value="apple-pay" required>
                            <label for="apple-pay">Apple Pay</label>
                        </div>
                    </fieldset>
                    <?php

                        if (isset($_SESSION['user_id'])) {
                            header("Location: login.php");
                            exit();
                        }
                        else{
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "sklep";

                            // Połączenie z bazą danych
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $user_id = $_SESSION['user_id'];
                            $payment_method = $_POST['payment-method']; // Metoda płatności z formularza
                            $delivery_company = $_POST['delivery-method']; // Firma kurierska z formularza
                            $order_status = "Oczekujące"; // Ustawienie statusu domyślnie na "Oczekujące"

                            // Wstawienie głównego zamówienia do tabeli zamowienia
                            $sql_order = "INSERT INTO zamowienia (id_uzytkownika, metoda_platnosci, firma_kurierska, stan_realizacji, data_zamowienia) 
                                        VALUES ('$user_id', '$payment_method', '$delivery_company', '$order_status', NOW())";

                            if ($conn->query($sql_order) === TRUE) {
                                // Pobranie ID nowo utworzonego zamówienia
                                $order_id = $conn->insert_id;

                                // Pobranie produktów z koszyka użytkownika
                                $sql_cart = "SELECT id_produktu, ilosc FROM koszyk WHERE id_klienta = $user_id";
                                $result_cart = $conn->query($sql_cart);

                                if ($result_cart->num_rows > 0) {
                                    // Dodanie każdego produktu z koszyka do zamówienia
                                    while ($row = $result_cart->fetch_assoc()) {
                                        $product_id = $row['id_produktu'];
                                        $quantity = $row['ilosc'];

                                        $sql_order_product = "INSERT INTO zamowienia_produkty (id_zamowienia, id_produktu, ilosc) 
                                                            VALUES ('$order_id', '$product_id', '$quantity')";
                                        $conn->query($sql_order_product);
                                    }

                                    $sql_clear_cart = "DELETE FROM koszyk WHERE id_klienta = $user_id";
                                    $conn->query($sql_clear_cart);

                                    echo "Zamówienie zostało złożone pomyślnie.";
                                } else {
                                    echo "Twój koszyk jest pusty.";
                                }
                            } else {
                                echo "Wystąpił błąd podczas składania zamówienia: " . $conn->error;
                            }

                            $conn->close();
                            header("Location: ../index.php");
                            exit();
                        }

                        
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
                        $sql = "SELECT SUM(cena) AS suma_cen FROM produkty INNER JOIN koszyk ON produkty.id_produktu = koszyk.id_produktu WHERE id_klienta =".$user_id.";";
                        $result = $conn->query($sql);
                        

                        if ($result) {
                            $row = $result->fetch_assoc(); 

                            $suma = $row['suma_cen'];
                            $_SESSION['suma'] = $row['suma_cen'];
                            if(empty($suma)){
                                $suma = 0;
                            }
                            
                            echo "<h2 class='summary-payment'>Łącznie: ".$suma." zł</p>";
                            echo "<br>";

                        } else {
                            echo "Błąd zapytania: " . $conn->error; 
                        }
                    
                    ?>


                    <div class="form-submit">
                        <button type="submit" class="submit-button">Złóż zamówienie</button>
                    </div>
                </form>
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


<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.payment-form');
    
    form.addEventListener('submit', function (e) {
        e.preventDefault();

    
        let valid = true;

    
        const street = document.getElementById('street').value.trim();
        const houseNumber = document.getElementById('house-number').value.trim();
        const city = document.getElementById('city').value.trim();
        const postalCode = document.getElementById('postal-code').value.trim();
        const country = document.getElementById('country').value.trim();
        
        
        const postalCodePattern = /^\d{2}-\d{3}$/;

        if (!street || !houseNumber || !city || !postalCode || !country) {
            alert('Proszę wypełnić wszystkie pola adresu.');
            valid = false;
        }

    
        if (!postalCodePattern.test(postalCode)) {
            alert('Kod pocztowy musi mieć format 00-000.');
            valid = false;
        }


        const deliveryMethod = document.querySelector('input[name="delivery-method"]:checked');
        if (!deliveryMethod) {
            alert('Proszę wybrać metodę dostawy.');
            valid = false;
        }

        const paymentMethod = document.querySelector('input[name="payment-method"]:checked');
        if (!paymentMethod) {
            alert('Proszę wybrać metodę płatności.');
            valid = false;
        }

    });
});
</script>
</body>
</html>