<?php
require_once "includes/functions.php";
verifier_connexion();
$page_titre = "Statistiques";
$id = intval($_SESSION["id_utilisateur"]);

maj_statistiques($id);
$res = mysqli_query($connexion, "SELECT * FROM statistiques WHERE id_utilisateur = $id LIMIT 1");
$stats = mysqli_fetch_assoc($res);

require_once "includes/header.php";
?>
<div class="card">
    <h2>Mes statistiques</h2>
    <div class="grid grid-3">
        <div class="stat-card"><span>Total tentatives</span><strong><?php echo intval($stats["total_tentatives"] ?? 0); ?></strong></div>
        <div class="stat-card"><span>Score moyen</span><strong><?php echo number_format(floatval($stats["score_moyen"] ?? 0), 2); ?> / 20</strong></div>
        <div class="stat-card"><span>Bonnes réponses moyennes</span><strong><?php echo number_format(floatval($stats["reponses_correctes_moyenne"] ?? 0), 2); ?> / 10</strong></div>
        <div class="stat-card"><span>Temps total</span><strong><?php echo format_temps($stats["temps_total_utilise"] ?? 0); ?></strong></div>
        <div class="stat-card"><span>Temps moyen</span><strong><?php echo format_temps($stats["temps_moyen"] ?? 0); ?></strong></div>
        <div class="stat-card"><span>Dernière mise à jour</span><strong style="font-size:16px"><?php echo h($stats["date_statistique"] ?? "-"); ?></strong></div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>
