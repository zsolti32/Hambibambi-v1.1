<?php

include "../../../connect.php";

// Ellenőrzi, hogy a felhasználó már be van-e jelentkezve
if (isset($_SESSION['user_id'])) {
    header("Location: ./../loginreg.php");
    exit();
}

$sql = "SELECT counties.county_id, counties.county_name, settlements.settlement_name, settlements.settlement_id 
        FROM counties 
        LEFT JOIN settlements 
        ON counties.county_id = settlements.county_id
        ORDER BY county_name, settlement_name";

$result = $conn->query($sql);

$counties = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $countyName = $row['county_name'];
        $settlementName = $row['settlement_name'];

        // Ellenőrizzük, hogy a vármegye már létezik-e a tömbben
        if (!isset($counties[$countyName])) {
            $counties[$countyName] = [];
        }

        // Hozzáadjuk a települést a vármegyéhez
        if (!empty($settlementName)) { // Csak akkor adjuk hozzá, ha a település neve nem üres
            $counties[$countyName][] = $settlementName;
        }
    }
}

// Regisztrációs adatok kezelése
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['full_name'], $_POST['email'], $_POST['password'], $_POST['phone_number'], $_POST['address'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);

    // Ellenőrizzük, hogy az e-mail már létezik-e
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' jelzi, hogy az email egy string típusú paraméter
    $stmt->execute();
    $stmt->store_result(); // Ez tárolja az eredményeket

    if ($stmt->num_rows > 0) {
        echo "A felhasználó már regisztrálva van ezzel az e-mail címmel.";
    } else {
        // Jelszó titkosítása
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Adatok mentése az adatbázisba
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone_number, address, registration_date) 
        VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $full_name, $email, $hashed_password, $phone_number, $address); // A paraméterek típusa: 's' (string)
        $stmt->execute();

        // Sikeres regisztráció után átirányítás
        header("Location: loginreg.php");
        exit();
    }
}

// Bejelentkezés a weboldalra
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ellenőrizzük, hogy az email szerepel-e az adatbázisban
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' jelzi, hogy az email egy string típusú paraméter
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password); // Az eredmény tárolása és a változókhoz rendelése
    $stmt->fetch();

    if ($user_id && password_verify($password, $hashed_password)) {
        // Bejelentkezés sikeres, munkamenet beállítása
        $_SESSION['user_id'] = $user_id;
        header("Location: ../../../index.php"); // Átirányítás a főoldalra
        exit();
    } else {
        $error = "Hibás email vagy jelszó!";
    }
}


$conn->close();
?>
<?php include("header.php"); ?>
<?php include("../navbar/navbar.php"); ?>
<div class="container">
<div class="area1">
<div id="register" class="registration-form form-container">
        <h2>Regisztráció</h2>
        <form action="" method="POST">
            <div class="field input">
                <label for="full_name">Teljesnév:</label>
                <input type="text" name="full_name" placeholder="Teljesnév" required>
            </div>
            <div class="field input">
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="minta@gmail.com" required>
            </div>
            <div class="field input">
                <label for="password">Jelszó:</label>
                <input type="password" name="password" placeholder="Jelszó" required>
            </div>
            <div class="field input">
                <label for="phone_number">Telefonszám:</label>
                <input type="text" name="phone_number" placeholder="+23563324591" required>
            </div>
            <div class="field input">
                <label for="address">Vármegye:</label>
                <select id="county" required onchange="updateSettlements()">
                    <option id="county" value="">Válasszon</option>
                    <?php foreach ($counties as $county => $settlements): ?>
                        <option id="county" value="<?= htmlspecialchars($county) ?>"><?= htmlspecialchars($county) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field input">
                <label for="address">Település:</label>
                <select id="settlement" required>
                    <option id="settlement" value="">Válasszon</option>
                </select>
            </div>
            <div class="field input">
                <label for="address">Cím:</label>
                <input type="text" name="address" placeholder="Lakcím" required>
            </div>
            <button type="submit">Regisztráció</button>
            <div class="link">Ha már van regisztrációja, lépjen be: <a href="loginregLogin.php">Belépés</a></div>
        </form>
</div>
</div>
</div>
<?php include("footer.php"); ?>
