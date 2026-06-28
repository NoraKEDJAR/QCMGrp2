<?php
require_once "includes/functions.php";
if (!est_connecte() || !isset($_SESSION["id_tentative"])) {
    exit;
}

$_SESSION["triche_count"] = intval($_SESSION["triche_count"] ?? 0) + 1;
$id_tentative = intval($_SESSION["id_tentative"]);
$type = securiser($_POST["type"] ?? "triche");

if ($_SESSION["triche_count"] >= 2) {
    mysqli_query($connexion, "UPDATE tentatives SET statut = 'annulee' WHERE id_tentative = $id_tentative");
}

echo "ok";
?>
