<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wylogowanie</title>
    <link rel="stylesheet" href="style.css">

    <?php
        session_start();
        session_unset();           
        session_destroy();         

        header("Location: ../index.php"); 
        exit();
    ?>

</head>
<body>
</body>
</html>