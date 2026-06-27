<?php
// Connexion à la base de données avec mysqli_connect
// Modifie ces valeurs si ton phpMyAdmin utilise un autre identifiant/mot de passe.
$host = "localhost";
$user = "root";
$password = "root";
$database = "qcm_platform";

$connexion = mysqli_connect($host, $user, $password, $database);

if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

mysqli_set_charset($connexion, "utf8mb4");
?>
