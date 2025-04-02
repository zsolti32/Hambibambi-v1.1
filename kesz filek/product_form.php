<?php
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="hu" ng-app="productApp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új termék felvitele</title>
    <link rel="stylesheet" href="../../../assets/css/productform.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
</head>

<body ng-controller="ProductController">
    <h2>Új termék felvitele</h2>
    <form ng-submit="addProduct()">
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

        <button type="submit">➕ Hozzáadás</button>
        <button type="button" onclick="window.location.href='dashboard.php'" class="dashboard-button">くくくVissza</button>
    </form>

    <p ng-if="message">{{ message }}</p>
    <script>
        let app = angular.module('productApp', []);

        app.controller('ProductController', function ($scope, $http) {
            $scope.product = {
                product_name: "",
                price: "",
                description: "",
                picture: "",
                product_category_id: "",
                quantity_unit_id: ""
            };

            $scope.addProduct = function () {
                $http.post("http://localhost/hambibambi/application/controller/add_product.php",
                    $scope.product)
                    .then(function (response) {
                        alert(response.data.message); // Visszajelzés
                        $scope.product = {}; // Űrlap ürítése
                        $window.location.href = "http://localhost/hambibambi/application/view/product_list.php"; 
                        // Átirányítás
                    })
                    .catch(function (error) {
                        console.error("Hiba történt:", error);
                        alert("Hiba történt a mentés során.");
                    });
            };
        });


    </script>
</body>

</html>