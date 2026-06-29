<?php
require_once "includes/functions.php";
if (est_connecte()) {
    header("Location: dashboard.php");
    exit;
}
$page_titre = "Accueil";
require_once "includes/header.php";
?>
<section class="card hero">
    <h1>QCM Platform</h1>
    <p>Application web de génération et de passage de QCM.</p>
    <p>Répondez à 10 questions aléatoires, obtenez une note sur 20 et consultez votre historique.</p>
    <p>
        <a class="btn" href="inscription.php">Créer un compte</a>
        <a class="btn btn-secondary" href="connexion.php">Se connecter</a>
    </p>
</section>
<?php require_once "includes/footer.php"; ?>
