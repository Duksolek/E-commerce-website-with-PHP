<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna - Sklep Internetowy</title>
    <link rel="stylesheet" href="../style.css">
    <?php
        session_start();

        if (isset($_SESSION['user_id'])) {
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

        if (!isset($_GET['add'])) {
            $id_produktu = intval($_GET['add']);
            $user_id = $_SESSION['user_id'];

            $sql = "INSERT INTO koszyk (id_produktu, id_klienta) VALUES ($id_produktu, $user_id)";
            $conn->query($sql);
        } else {
            echo "Brak ID produktu do dodania.";
        }

        $conn->close();

        header("Location: ../index.php");
        exit();
        ?>



</head>
<body>
</body>
</html>