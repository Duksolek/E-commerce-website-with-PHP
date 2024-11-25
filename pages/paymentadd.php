<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizacja - Sklep Internetowy</title>
    <link rel="stylesheet" href="../style.css">
    <?php
        session_start();

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




</head>
<body>
</body>
</html>