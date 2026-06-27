<?php
require_once "includes/functions.php";
verifier_connexion();

$page_titre = "Préparation du QCM";
$id_utilisateur = intval($_SESSION["id_utilisateur"]);
$limite_secondes = 10 * 60;

// Si une tentative est déjà en cours dans la session, on propose de la continuer.
$qcm_en_cours = false;
$temps_restant = $limite_secondes;

if (isset($_SESSION["id_tentative"], $_SESSION["question_ids"], $_SESSION["qcm_start_time"])) {
    $id_tentative = intval($_SESSION["id_tentative"]);
    $verif = mysqli_query($connexion, "SELECT statut FROM tentatives WHERE id_tentative = $id_tentative AND id_utilisateur = $id_utilisateur LIMIT 1");
    $tentative = mysqli_fetch_assoc($verif);

    if ($tentative && $tentative["statut"] === "en_cours") {
        $temps_ecoule = time() - intval($_SESSION["qcm_start_time"]);
        $temps_restant = max(0, $limite_secondes - $temps_ecoule);
        if ($temps_restant > 0) {
            $qcm_en_cours = true;
        }
    }
}

require_once "includes/header.php";
?>
<div class="card hero-simple">
    <h2>Avant de commencer le QCM</h2>
    <p>Le chronomètre ne démarre pas encore. Il commencera seulement quand tu cliques sur <strong>Commencer maintenant</strong>.</p>
</div>

<?php if ($qcm_en_cours): ?>
    <div class="alert alert-info">
        Tu as déjà un QCM en cours. Tu peux le continuer au lieu d’en créer un deuxième.
        Temps restant : <strong><?php echo format_temps($temps_restant); ?></strong>
    </div>
    <div class="card">
        <a class="btn" href="qcm.php">Continuer le QCM en cours</a>
        <a class="btn btn-secondary" href="dashboard.php">Retour au tableau de bord</a>
    </div>
<?php else: ?>
    <div class="card">
        <h3>Règles du QCM</h3>
        <ul>
            <li>Le QCM contient 10 questions.</li>
            <li>Chaque question possède 4 réponses possibles.</li>
            <li>Une seule réponse est correcte.</li>
            <li>La note finale est sur 20.</li>
            <li>Durée maximum : 10 minutes.</li>
            <li>Le plein écran est demandé pendant le QCM.</li>
        </ul>
    </div>

    <div class="card">
        <form method="post" action="qcm_start.php">
            <button class="btn" type="submit">Commencer maintenant</button>
            <a class="btn btn-secondary" href="dashboard.php">Je ne suis pas encore prêt(e)</a>
        </form>
    </div>
<?php endif; ?>
<?php require_once "includes/footer.php"; ?>