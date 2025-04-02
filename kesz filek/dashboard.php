<?php
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: logreg/login.php");
    exit;
}

// Ellenőrizzük, hogy létezik-e a 'user' kulcs a session tömbben
$admin_user = isset($_SESSION['user']) ? $_SESSION['user'] : "Admin";
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../../../assets/css/dashboardadmin.css">
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
        <nav>
            <ul>
                <li><a href="product_list.php">📦 Termékek kezelése</a></li>
                <li><a href="logout.php">🚪 Kijelentkezés</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Üdvözlünk az Admin Panelen, <?php echo htmlspecialchars($admin_user); ?>!</h2>
        <p>Itt kezelheted a termékeket, szerkesztheted és törölheted azokat.</p>

        <section>
            <h3>Elérhető funkciók:</h3>
            <ul>
                <li><a href="product_list.php">📋 Terméklista megtekintése</a></li>
                <li><a href="product_form.php">➕ Új termék felvitele</a></li>
            </ul>
        </section>
    </main>

    <footer>
        <p>Admin rendszer &copy; <?php echo date("Y"); ?></p>
    </footer>
</body>
</html>
