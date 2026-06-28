<?php
require_once "includes/functions.php";
verifier_connexion();
$page_titre = "Résultat du QCM";

$id_tentative = intval($_GET["id"] ?? 0);
$id_utilisateur = intval($_SESSION["id_utilisateur"]);

$where_user = est_admin() ? "" : "AND t.id_utilisateur = $id_utilisateur";
$sql_t = "SELECT t.*, u.nom, u.prenom FROM tentatives t
          JOIN utilisateurs u ON u.id_utilisateur = t.id_utilisateur
          WHERE t.id_tentative = $id_tentative $where_user LIMIT 1";
$res_t = mysqli_query($connexion, $sql_t);
$tentative = mysqli_fetch_assoc($res_t);

if (!$tentative) {
    die("Tentative introuvable.");
}

$sql_r = "SELECT r.*, q.* FROM reponses_utilisateur r
          JOIN questions q ON q.id_question = r.id_question
          WHERE r.id_tentative = $id_tentative
          ORDER BY r.id_reponse ASC";
$res_r = mysqli_query($connexion, $sql_r);

require_once "includes/header.php";
?>
<div class="card">
    <h2>QCM terminé</h2>
    <?php if ($tentative["statut"] === "annulee"): ?>
        <div class="alert alert-error">Tentative annulée à cause du temps dépassé ou d'un avertissement anti-triche.</div>
    <?php endif; ?>
    <div class="grid grid-3">
        <div class="stat-card"><span>Score obtenu</span><strong><?php echo h($tentative["score"]); ?> / 20</strong></div>
        <div class="stat-card"><span>Temps utilisé</span><strong><?php echo format_temps($tentative["temps_utilise"]); ?></strong></div>
        <div class="stat-card"><span>Bonnes réponses</span><strong><?php echo intval($tentative["score"] / 2); ?> / 10</strong></div>
    </div>
</div>

<div class="card">
    <h2>Détail des réponses</h2>
    <?php while ($r = mysqli_fetch_assoc($res_r)): ?>
        <?php $est_correcte = intval($r["reponse_choisie"]) === intval($r["bonne_reponse"]); ?>
        <div class="question-box">
            <p><strong><?php echo h($r["texte_question"]); ?></strong></p>
            <p>Votre réponse : <?php echo h(texte_reponse($r, $r["reponse_choisie"])); ?></p>
            <?php if (!$est_correcte): ?>
                <p>Bonne réponse : <strong><?php echo h(texte_reponse($r, $r["bonne_reponse"])); ?></strong></p>
                <span class="badge badge-ko">Incorrect</span>
            <?php else: ?>
                <span class="badge badge-ok">Correct</span>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
    <p>
        <a class="btn" href="dashboard.php">Retour au tableau de bord</a>
        <a class="btn btn-secondary" href="historique.php">Voir l'historique</a>
    </p>
</div>
<?php require_once "includes/footer.php"; ?>
