<?php
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: logreg/login.php");
    exit;
}

// Ellenőrizzük, hogy érkezett-e ID a lekérdezési paraméterben
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: product_list.php");
    exit;
}

$product_id = intval($_GET['id']);
?>

<!DOCTYPE html>
<html lang="hu" ng-app="productApp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termék módosítása</title>
    <link rel="stylesheet" href="../../../assets/css/updateprod.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
</head>

<body ng-controller="ProductController">
    <h2>Termék módosítása</h2>
    <form ng-submit="updateProduct()">
        <label>Termék neve:</label>
        <input type="text" ng-model="product.product_name" required>

        <label>Ár:</label>
        <input type="number" ng-model="product.price" required>

        <label>Leírás:</label>
        <textarea ng-model="product.description" required></textarea>

        <label>Kép neve (pl. cezar_salata.jpg):</label>
        <input type="text" ng-model="product.picture" required>

        <label>Kategória ID:</label>
        <input type="number" ng-model="product.product_category_id" required>

        <label>Mennyiségi egység ID:</label>
        <input type="number" ng-model="product.quantity_unit_id" required>

        <button type="submit">⚙️ Módosítás</button>
        <button type="button" onclick="window.location.href='product_list.php'" class="dashboard-button">くくくVissza</button>
    </form>

    <p ng-if="message">{{ message }}</p>

    <script>
        let app = angular.module('productApp', []);

        app.controller('ProductController', function ($scope, $http, $window) {
            let productId = "<?php echo $product_id; ?>";
            
            // Termékadatok lekérése ID alapján
            $http.get("http://localhost/hambibambi/application/controller/api.php?id=" + productId)
                .then(function (response) {
                    if (response.data) {
                        $scope.product = response.data;
                    } else {
                        alert("Nem található ilyen termék!");
                        $window.location.href = "product_list.php"; // Visszairányítás
                    }
                })
                .catch(function (error) {
                    console.error("Hiba a termék betöltése során:", error);
                    alert("Hiba történt a termék adatainak betöltésekor.");
                });

            // Termék módosítása
            $scope.updateProduct = function () {
                $http.put("http://localhost/hambibambi/application/controller/api.php?id=" + productId,
                    $scope.product)
                    .then(function (response) {
                        alert(response.data.message); // Visszajelzés
                        $window.location.href = "product_list.php"; // Átirányítás
                    })
                    .catch(function (error) {
                        console.error("Hiba a módosítás során:", error);
                        alert("Hiba történt a módosítás során.");
                    });
            };
        });
    </script>
</body>
</html>
