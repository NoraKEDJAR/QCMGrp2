<?php
require_once "../includes/functions.php";
verifier_admin();

$id = intval($_POST["id_question"] ?? 0);
$texte = securiser($_POST["texte_question"] ?? "");
$r1 = securiser($_POST["reponse1"] ?? "");
$r2 = securiser($_POST["reponse2"] ?? "");
$r3 = securiser($_POST["reponse3"] ?? "");
$r4 = securiser($_POST["reponse4"] ?? "");
$bonne = intval($_POST["bonne_reponse"] ?? 1);
$categorie = securiser($_POST["categorie"] ?? "");

if ($bonne < 1 || $bonne > 4) {
    $bonne = 1;
}

if ($id > 0) {
    $sql = "UPDATE questions SET
            texte_question = '$texte',
            reponse1 = '$r1',
            reponse2 = '$r2',
            reponse3 = '$r3',
            reponse4 = '$r4',
            bonne_reponse = $bonne,
            categorie = '$categorie'
            WHERE id_question = $id";
} else {
    $sql = "INSERT INTO questions (texte_question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie)
            VALUES ('$texte', '$r1', '$r2', '$r3', '$r4', $bonne, '$categorie')";
}

mysqli_query($connexion, $sql);
header("Location: questions.php");
exit;
?>
