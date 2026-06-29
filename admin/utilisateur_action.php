<?php
require_once "../includes/functions.php";
verifier_admin();

$id = intval($_GET["id"] ?? 0);
$action = $_GET["action"] ?? "";

if ($id > 0 && $id !== intval($_SESSION["id_utilisateur"])) {
    if ($action === "bloquer") {
        mysqli_query($connexion, "UPDATE utilisateurs SET statut = 'bloque' WHERE id_utilisateur = $id");
    } elseif ($action === "activer") {
        mysqli_query($connexion, "UPDATE utilisateurs SET statut = 'actif' WHERE id_utilisateur = $id");
    } elseif ($action === "supprimer") {
        mysqli_query($connexion, "DELETE FROM utilisateurs WHERE id_utilisateur = $id");
    }
}

header("Location: utilisateurs.php");
exit;
?>
