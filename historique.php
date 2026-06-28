<?php
require_once "includes/functions.php";
verifier_connexion();
$page_titre = "Historique";
$id = intval($_SESSION["id_utilisateur"]);

$res = mysqli_query($connexion, "SELECT * FROM tentatives WHERE id_utilisateur = $id ORDER BY date_tentative DESC");
$moy = mysqli_fetch_assoc(mysqli_query($connexion, "SELECT AVG(score) AS moyenne FROM tentatives WHERE id_utilisateur = $id AND statut = 'validee'"))["moyenne"] ?? 0;

require_once "includes/header.php";
?>
<div class="card">
    <h2>Historique des tentatives</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Score</th>
            <th>Temps utilisé</th>
            <th>Statut</th>
            <th>Détails</th>
        </tr>
        <?php $i = 1; while ($t = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo h($t["date_tentative"]); ?></td>
                <td><?php echo h($t["score"]); ?> / 20</td>
                <td><?php echo format_temps($t["temps_utilise"]); ?></td>
                <td><?php echo h($t["statut"]); ?></td>
                <td><a href="resultat.php?id=<?php echo intval($t["id_tentative"]); ?>">Voir</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <p><strong>Moyenne générale :</strong> <?php echo number_format(floatval($moy), 2); ?> / 20</p>
</div>
<?php require_once "includes/footer.php"; ?>
