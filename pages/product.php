<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkt - Sklep Internetowy</title>
    <link rel="stylesheet" href="../style.css">
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
        <div class="produkt-strona-container">
            <img src="../media/img/kreatynaarbuzkfd.webp" alt="Nazwa Produktu" class="produkt-strona-image"> <!-- Zastąp 'product-image.jpg' dynamicznie przez PHP -->
            <div class="produkt-strona-details">
                <h2 class="produkt-strona-name">Nazwa Produktu</h2> <!-- Zastąp "Nazwa Produktu" dynamicznie przez PHP -->
                <p class="produkt-strona-price">Cena: 100 zł</p> <!-- Zastąp "100 zł" dynamicznie przez PHP -->
                <p class="produkt-strona-description">To jest opis produktu. Opis powinien zawierać wszystkie ważne informacje o produkcie. Może być dłuższy, aby dobrze opisać jego cechy i zalety.</p> <!-- Zastąp dynamicznie przez PHP -->
                <div class="produkt-strona-actions">
                    <button class="produkt-strona-add-to-wishlist">Dodaj do listy życzeń</button>
                    <button class="produkt-strona-add-to-cart">Dodaj do koszyka</button>
                </div>
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