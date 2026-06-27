<?php
require_once "includes/functions.php";
verifier_connexion();

$id_utilisateur = intval($_SESSION["id_utilisateur"]);
$limite_secondes = 10 * 60;

// Cette page doit être lancée depuis le bouton "Commencer maintenant".
// Si on arrive ici par l'adresse directement, on revient sur la page de préparation.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: qcm_intro.php");
    exit;
}

// Vérifier que le compte n'est pas bloqué au moment de commencer.
$utilisateur = utilisateur_courant();
if ($utilisateur["statut"] === "bloque") {
    header("Location: dashboard.php?erreur=compte_bloque");
    exit;
}

// Si une tentative est déjà en cours dans la session, on ne crée pas un deuxième QCM.
if (isset($_SESSION["id_tentative"], $_SESSION["question_ids"], $_SESSION["qcm_start_time"])) {
    $id_tentative = intval($_SESSION["id_tentative"]);
    $verif = mysqli_query($connexion, "SELECT statut FROM tentatives WHERE id_tentative = $id_tentative AND id_utilisateur = $id_utilisateur LIMIT 1");
    $tentative = mysqli_fetch_assoc($verif);

    if ($tentative && $tentative["statut"] === "en_cours") {
        $temps_ecoule = time() - intval($_SESSION["qcm_start_time"]);
        if ($temps_ecoule < $limite_secondes) {
            header("Location: qcm.php");
            exit;
        }

        // Si le QCM en cours est expiré, on l'annule.
        mysqli_query($connexion, "UPDATE tentatives SET temps_utilise = $temps_ecoule, statut = 'annulee' WHERE id_tentative = $id_tentative AND id_utilisateur = $id_utilisateur");
    }

    unset($_SESSION["id_tentative"], $_SESSION["question_ids"], $_SESSION["qcm_start_time"], $_SESSION["triche_count"]);
}

// Annuler les anciennes tentatives en cours de cet utilisateur qui ne sont plus dans la session.
// Cela évite d'avoir plusieurs QCM en cours dans la base.
mysqli_query($connexion, "UPDATE tentatives SET statut = 'annulee' WHERE id_utilisateur = $id_utilisateur AND statut = 'en_cours'");

// Sélectionner 10 questions aléatoires.
$questions = mysqli_query($connexion, "SELECT id_question FROM questions ORDER BY RAND() LIMIT 10");
$ids = [];
while ($q = mysqli_fetch_assoc($questions)) {
    $ids[] = intval($q["id_question"]);
}

if (count($ids) < 10) {
    die("Il faut au moins 10 questions dans la base de données.");
}

// Créer la tentative seulement maintenant : le chronomètre commence ici.
mysqli_query($connexion, "INSERT INTO tentatives (id_utilisateur, date_tentative, score, temps_utilise, statut)
    VALUES ($id_utilisateur, NOW(), 0, 0, 'en_cours')");
$id_tentative = mysqli_insert_id($connexion);

$_SESSION["id_tentative"] = $id_tentative;
$_SESSION["question_ids"] = $ids;
$_SESSION["qcm_start_time"] = time();
$_SESSION["triche_count"] = 0;

header("Location: qcm.php");
exit;
?>
