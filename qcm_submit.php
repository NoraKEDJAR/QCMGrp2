<?php
require_once "includes/functions.php";
verifier_connexion();

if (!isset($_SESSION["id_tentative"], $_SESSION["question_ids"], $_SESSION["qcm_start_time"])) {
    header("Location: dashboard.php");
    exit;
}

$id_tentative = intval($_SESSION["id_tentative"]);
$id_utilisateur = intval($_SESSION["id_utilisateur"]);
$ids = array_map("intval", $_SESSION["question_ids"]);
$temps_utilise = time() - intval($_SESSION["qcm_start_time"]);
$triche_count = intval($_SESSION["triche_count"] ?? 0);
$triche_post = intval($_POST["triche_detectee"] ?? 0);

// Éviter de traiter deux fois la même tentative.
$verif_reponses = mysqli_query($connexion, "SELECT COUNT(*) AS total FROM reponses_utilisateur WHERE id_tentative = $id_tentative");
$total_deja = intval(mysqli_fetch_assoc($verif_reponses)["total"] ?? 0);
if ($total_deja > 0) {
    header("Location: resultat.php?id=$id_tentative");
    exit;
}

$statut_tentative = mysqli_fetch_assoc(mysqli_query($connexion, "SELECT statut FROM tentatives WHERE id_tentative = $id_tentative AND id_utilisateur = $id_utilisateur LIMIT 1"));
$annulee = false;

if (!$statut_tentative || $statut_tentative["statut"] === "annulee") {
    $annulee = true;
}

// Limite de temps serveur : 10 minutes maximum.
if ($temps_utilise > 10 * 60) {
    $annulee = true;
}

// Si deux avertissements anti-triche ont été détectés, tentative annulée.
if ($triche_count >= 2 || $triche_post >= 2) {
    $annulee = true;
}

$score = 0;
$reponses = $_POST["reponse"] ?? [];
$liste_ids = implode(",", $ids);
$resultat_questions = mysqli_query($connexion, "SELECT * FROM questions WHERE id_question IN ($liste_ids)");

while ($q = mysqli_fetch_assoc($resultat_questions)) {
    $id_question = intval($q["id_question"]);
    $choix = intval($reponses[$id_question] ?? 0);
    if ($choix >= 1 && $choix <= 4) {
        mysqli_query($connexion, "INSERT INTO reponses_utilisateur (id_tentative, id_question, reponse_choisie)
            VALUES ($id_tentative, $id_question, $choix)");
        if (!$annulee && $choix === intval($q["bonne_reponse"])) {
            $score += 2;
        }
    }
}

if ($annulee) {
    $score = 0;
    mysqli_query($connexion, "UPDATE tentatives SET score = 0, temps_utilise = $temps_utilise, statut = 'annulee' WHERE id_tentative = $id_tentative");
} else {
    mysqli_query($connexion, "UPDATE tentatives SET score = $score, temps_utilise = $temps_utilise, statut = 'validee' WHERE id_tentative = $id_tentative");
}

maj_statistiques($id_utilisateur);

unset($_SESSION["id_tentative"], $_SESSION["question_ids"], $_SESSION["qcm_start_time"], $_SESSION["triche_count"]);

header("Location: resultat.php?id=$id_tentative");
exit;
?>
