<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/database.php";

function nettoyer($valeur) {
    return trim($valeur ?? "");
}

function securiser($valeur) {
    global $connexion;
    return mysqli_real_escape_string($connexion, nettoyer($valeur));
}

function h($texte) {
    return htmlspecialchars($texte ?? "", ENT_QUOTES, "UTF-8");
}

function est_connecte() {
    return isset($_SESSION["id_utilisateur"]);
}

function est_admin() {
    return est_connecte() && isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
}

function verifier_connexion() {
    if (!est_connecte()) {
        header("Location: connexion.php");
        exit;
    }
}

function verifier_admin() {
    if (!est_admin()) {
        header("Location: ../connexion.php");
        exit;
    }
}

function utilisateur_courant() {
    global $connexion;
    if (!est_connecte()) {
        return null;
    }
    $id = intval($_SESSION["id_utilisateur"]);
    $sql = "SELECT * FROM utilisateurs WHERE id_utilisateur = $id LIMIT 1";
    $resultat = mysqli_query($connexion, $sql);
    return mysqli_fetch_assoc($resultat);
}

function format_temps($secondes) {
    $secondes = intval($secondes);
    $heures = floor($secondes / 3600);
    $minutes = floor(($secondes % 3600) / 60);
    $sec = $secondes % 60;
    return sprintf("%02d:%02d:%02d", $heures, $minutes, $sec);
}

function texte_reponse($question, $numero) {
    $numero = intval($numero);
    if ($numero < 1 || $numero > 4) {
        return "Aucune réponse";
    }
    return $question["reponse" . $numero] ?? "Aucune réponse";
}

function maj_statistiques($id_utilisateur) {
    global $connexion;
    $id_utilisateur = intval($id_utilisateur);

    $sql = "SELECT 
                COUNT(*) AS total_tentatives,
                AVG(score) AS score_moyen,
                SUM(temps_utilise) AS temps_total_utilise,
                AVG(temps_utilise) AS temps_moyen,
                AVG(score / 2) AS reponses_correctes_moyenne
            FROM tentatives
            WHERE id_utilisateur = $id_utilisateur AND statut = 'validee'";
    $resultat = mysqli_query($connexion, $sql);
    $stats = mysqli_fetch_assoc($resultat);

    $total = intval($stats["total_tentatives"] ?? 0);
    $score_moyen = round(floatval($stats["score_moyen"] ?? 0), 2);
    $temps_total = intval($stats["temps_total_utilise"] ?? 0);
    $temps_moyen = intval($stats["temps_moyen"] ?? 0);
    $correctes_moyenne = round(floatval($stats["reponses_correctes_moyenne"] ?? 0), 2);

    $existe = mysqli_query($connexion, "SELECT id_statistique FROM statistiques WHERE id_utilisateur = $id_utilisateur LIMIT 1");

    if (mysqli_num_rows($existe) > 0) {
        $sql_update = "UPDATE statistiques SET
            date_statistique = NOW(),
            total_tentatives = $total,
            score_moyen = $score_moyen,
            temps_total_utilise = $temps_total,
            temps_moyen = $temps_moyen,
            reponses_correctes_moyenne = $correctes_moyenne
            WHERE id_utilisateur = $id_utilisateur";
        mysqli_query($connexion, $sql_update);
    } else {
        $sql_insert = "INSERT INTO statistiques
            (id_utilisateur, date_statistique, total_tentatives, score_moyen, temps_total_utilise, temps_moyen, reponses_correctes_moyenne)
            VALUES
            ($id_utilisateur, NOW(), $total, $score_moyen, $temps_total, $temps_moyen, $correctes_moyenne)";
        mysqli_query($connexion, $sql_insert);
    }
}
?>
