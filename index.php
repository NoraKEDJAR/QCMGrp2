<?php
require_once "../includes/functions.php";
verifier_admin();
$base_path = "../";
$page_titre = "Administration";

$nb_users = mysqli_fetch_assoc(mysqli_query($connexion, "SELECT COUNT(*) AS total FROM utilisateurs"))["total"] ?? 0;
$nb_questions = mysqli_fetch_assoc(mysqli_query($connexion, "SELECT COUNT(*) AS total FROM questions"))["total"] ?? 0;
$nb_tentatives = mysqli_fetch_assoc(mysqli_query($connexion, "SELECT COUNT(*) AS total FROM tentatives"))["total"] ?? 0;
$nb_bloques = mysqli_fetch_assoc(mysqli_query($connexion, "SELECT COUNT(*) AS total FROM utilisateurs WHERE statut = 'bloque'"))["total"] ?? 0;

require_once "../includes/header.php";
?>
<div class="card">
    <h2>Tableau de bord administrateur</h2>
    <p>Bienvenue dans l'espace d'administration.</p>
</div>
<div class="grid grid-4">
    <div class="stat-card"><span>Utilisateurs</span><strong><?php echo intval($nb_users); ?></strong></div>
    <div class="stat-card"><span>Questions</span><strong><?php echo intval($nb_questions); ?></strong></div>
    <div class="stat-card"><span>Tentatives</span><strong><?php echo intval($nb_tentatives); ?></strong></div>
    <div class="stat-card"><span>Comptes bloqués</span><strong><?php echo intval($nb_bloques); ?></strong></div>
</div>
<div class="card">
    <h2>Actions</h2>
    <a class="btn" href="utilisateurs.php">Gérer les utilisateurs</a>
    <a class="btn btn-secondary" href="questions.php">Gérer les questions</a>
</div>
<?php require_once "../includes/footer.php"; ?>
