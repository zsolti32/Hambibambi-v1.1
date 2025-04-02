<?php
require "../../../connect.php";
session_start();

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terméklista</title>
    <link rel="stylesheet" href="../../../assets/css/productlist.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }

        th:hover {
            background-color: #ddd;
        }

        button {
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>

<body ng-app="productApp" ng-controller="ProductController">

    <h2>Terméklista</h2>
    
    <button type="button" onclick="window.location.href='dashboard.php'" class="dashboard-button">くくくVissza</button>

<h3>A termékek id, név és ár szerint rendezhetőek, kattintson a fejlécre!</h3>
    <table>
        <tr>
            <th ng-click="sortData('product_id')">
                ID <span ng-show="sortColumn == 'product_id'">{{ reverseSort ? '⬇️' : '⬆️' }}</span>
            </th>
            <th ng-click="sortData('product_name')">
                Név <span ng-show="sortColumn == 'product_name'">{{ reverseSort ? '⬇️' : '⬆️' }}</span>
            </th>
            <th ng-click="sortData('price')">
                Ár <span ng-show="sortColumn == 'price'">{{ reverseSort ? '⬇️' : '⬆️' }}</span>
            </th>
            <th>Leírás</th>
            <th>Kép</th>
            <th>Műveletek</th>
        </tr>
        <tr class="product" ng-repeat="product in products | orderBy:sortColumn:reverseSort">
            <td>{{ product.product_id }}</td>
            <td>{{ product.product_name }}</td>
            <td>{{ product.price }} Ft</td>
            <td>{{ product.description }}</td>
            <td> <img ng-src="http://localhost/hambibambi/assets/img/{{product.picture}}" alt="{{product.product_name}}" width="50"></td>
            <td>
                <button ng-click="editProduct(product.product_id)">⚙️ Módosítás</button>
                <button ng-click="deleteProduct(product.product_id)">🗑️ Törlés</button>
            </td>
        </tr>
    </table>

    <script>
        let app = angular.module('productApp', []);

        app.controller('ProductController', function ($scope, $http) {
            // Kezdőértékek a rendezéshez
            $scope.sortColumn = "product_id";
            $scope.reverseSort = false;

            // Oszlop szerinti rendezés
            $scope.sortData = function (column) {
                if ($scope.sortColumn == column) {
                    $scope.reverseSort = !$scope.reverseSort;
                } else {
                    $scope.sortColumn = column;
                    $scope.reverseSort = false;
                }
            };

            // Termékek beolvasása
            $scope.loadProducts = function () {
                $http.get("http://localhost/hambibambi/application/controller/api.php")
                    .then(function (response) {
                        $scope.products = response.data;
                    })
                    .catch(function (error) {
                        console.error("Hiba a termékek betöltésekor:", error);
                    });
            };

            // Termék törlése
            $scope.deleteProduct = function (id) {
                if (confirm("Biztosan törölni szeretnéd ezt a terméket?")) {
                    $http({
                        method: "DELETE",
                        url: "http://localhost/hambibambi/application/controller/api.php",
                        data: { id: id }, // Törlésnél az id-t a body-ban küldjük
                        headers: { "Content-Type": "application/json" }
                    })
                        .then(function (response) {
                            alert(response.data.message);
                            $scope.loadProducts(); // Terméklista frissítése
                        })
                        .catch(function (error) {
                            console.error("Hiba a törlés során:", error);
                        });
                }
            };

            $scope.editProduct = function (id) {
                window.location.href = 'update_product.php?id=' + id;
            };

            // Felvitel gomb működtetése
            $scope.addNewProduct = function () {
                window.location.href = "http://localhost/hambibambi/application/view/admin/product_form.html";
            };

            $scope.loadProducts(); // Oldal betöltésekor lekérjük a termékeket
        });
    </script>

</body>
</html>
