<?php
$servername = "localhost";
$username = "u995433666_sai";
$password = "VIg]9/0;B2FPrU^S09";
$dbname = "u995433666_tapzo";
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>