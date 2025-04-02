<footer>
<nav>
        <ul class="navbar">
            <li><a href=<?= $Path . "index.php"; ?>>🏠︎ Kezdőlap</a></li>
            <li><a href=<?= $Path . "application/view/menu/menu.php"; ?>>🗒 Étlap</a></li>
            <li><a href=<?= $Path . "application/view/contacts/contact.php"; ?>>ⓘ Kapcsolat</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Ha a felhasználó be van jelentkezve -->
                <li><a href=<?= $Path . "application/view/profile/profile.php"; ?>>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg> Profil</a></li>
            <?php else: ?>
                <!-- Ha a felhasználó nincs bejelentkezve -->
                <li><a href=<?= $Path . "application/view/logreg/loginreg.php"; ?>>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg> Belépés / Regisztráció</a></li>
            <?php endif; ?>
        </ul>
    </nav> 
        <h3>HambiBambi Étterem</h3>
        <div class="logo">
            <img src="<?= $Path."assets/img/HambiBambi_Logo.png"?>" alt="HambiBambi Étterem Logó">
        </div>
    </footer>
    <script src="<?= $Path."assets/js/cart.js";?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html> 