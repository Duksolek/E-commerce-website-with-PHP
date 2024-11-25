<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk - Sklep Internetowy</title>
    <link rel="stylesheet" href="../style.css">
    <?php
        session_start(); 

        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); 
            exit();
        }

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "sklep";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_GET['remove'])) {  
            $id_przedmiotu = intval($_GET['remove']);  

            $sql = "DELETE FROM koszyk WHERE id_przedmiotu = ? AND id_klienta = ?";  

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_przedmiotu, $_SESSION['user_id']); 
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Produkt został usunięty z koszyka.";
            } else {
                echo "Nie udało się usunąć produktu. Sprawdź, czy produkt istnieje w koszyku.";
            }

            $stmt->close();
        } else {
            echo "Brak ID produktu do usunięcia.";
        }

        $conn->close();

        header("Location: ../pages/cart.php"); 
        exit();

        ?>

</head>
<body>
</body>
</html>