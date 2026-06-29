<?php
require_once "includes/functions.php";
verifier_connexion();
$page_titre = "Tableau de bord";
$id = intval($_SESSION["id_utilisateur"]);

$total = mysqli_fetch_assoc(mysqli_query($connexion, "SELECT COUNT(*) AS total FROM tentatives WHERE id_utilisateur = $id AND statut = 'validee'"))["total"] ?? 0;
$moyenne = mysqli_fetch_assoc(mysqli_query($connexion, "SELECT AVG(score) AS moyenne FROM tentatives WHERE id_utilisateur = $id AND statut = 'validee'"))["moyenne"] ?? 0;
$dernieres = mysqli_query($connexion, "SELECT * FROM tentatives WHERE id_utilisateur = $id ORDER BY date_tentative DESC LIMIT 3");

// Vérifier s'il existe un QCM en cours dans la session.
$qcm_en_cours = false;
if (isset($_SESSION["id_tentative"], $_SESSION["question_ids"], $_SESSION["qcm_start_time"])) {
    $id_tentative_session = intval($_SESSION["id_tentative"]);
    $verif_qcm = mysqli_query($connexion, "SELECT statut FROM tentatives WHERE id_tentative = $id_tentative_session AND id_utilisateur = $id LIMIT 1");
    $tentative_session = mysqli_fetch_assoc($verif_qcm);
    if ($tentative_session && $tentative_session["statut"] === "en_cours") {
        $qcm_en_cours = true;
    }
}

require_once "includes/header.php";
?>
<div class="card">
    <h2>Bonjour, <?php echo h($_SESSION["prenom"]); ?> !</h2>
    <p>Prêt pour un nouveau QCM ?</p>
</div>

<div class="grid grid-2">
    <div class="stat-card">
        <span>Score moyen</span>
        <strong><?php echo number_format(floatval($moyenne), 2); ?> / 20</strong>
    </div>
    <div class="stat-card">
        <span>Total tentatives</span>
        <strong><?php echo intval($total); ?></strong>
    </div>
</div>

<div class="card">
    <h2>Actions rapides</h2>
    <p>
        <?php if ($qcm_en_cours): ?>
            <a class="btn" href="qcm.php">Continuer le QCM en cours</a>
        <?php else: ?>
            <a class="btn" href="qcm_intro.php">Passer un QCM</a>
        <?php endif; ?>
        <a class="btn btn-secondary" href="historique.php">Voir l'historique</a>
        <a class="btn btn-secondary" href="statistiques.php">Voir les statistiques</a>
    </p>
</div>

<div class="card">
    <h2>Dernières tentatives</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Score</th>
            <th>Temps</th>
            <th>Statut</th>
        </tr>
        <?php while ($t = mysqli_fetch_assoc($dernieres)): ?>
            <tr>
                <td><?php echo h($t["date_tentative"]); ?></td>
                <td><?php echo h($t["score"]); ?> / 20</td>
                <td><?php echo format_temps($t["temps_utilise"]); ?></td>
                <td><?php echo h($t["statut"]); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php require_once "includes/footer.php"; ?>
