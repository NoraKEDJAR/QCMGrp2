<?php
require_once "includes/functions.php";
verifier_connexion();

if (!isset($_SESSION["id_tentative"], $_SESSION["question_ids"], $_SESSION["qcm_start_time"])) {
    header("Location: dashboard.php");
    exit;
}

$page_titre = "Passer un QCM";
$limite_secondes = 10 * 60;
$temps_ecoule = time() - intval($_SESSION["qcm_start_time"]);
$temps_restant = max(0, $limite_secondes - $temps_ecoule);

$id_tentative = intval($_SESSION["id_tentative"]);
$id_utilisateur = intval($_SESSION["id_utilisateur"]);

// Si le temps est déjà dépassé, la tentative est annulée.
if ($temps_restant <= 0) {
    mysqli_query($connexion, "UPDATE tentatives SET temps_utilise = $temps_ecoule, score = 0, statut = 'annulee' WHERE id_tentative = $id_tentative AND id_utilisateur = $id_utilisateur");
    unset($_SESSION["id_tentative"], $_SESSION["question_ids"], $_SESSION["qcm_start_time"], $_SESSION["triche_count"]);
    header("Location: resultat.php?id=$id_tentative");
    exit;
}

$ids = array_map("intval", $_SESSION["question_ids"]);
$liste_ids = implode(",", $ids);
$sql = "SELECT * FROM questions WHERE id_question IN ($liste_ids) ORDER BY FIELD(id_question, $liste_ids)";
$resultat = mysqli_query($connexion, $sql);

require_once "includes/header.php";
?>
<div class="timer">
    Temps restant : <span id="timer">10:00</span>
    <span id="warning" style="margin-left:20px;color:#dc3545;"></span>
</div>

<div class="card">
    <h2>QCM de 10 questions</h2>
    <p>Le mode plein écran est obligatoire. Si vous changez d'onglet ou quittez le plein écran plusieurs fois, la tentative sera annulée.</p>
    <button type="button" class="btn" id="btnFullscreen">Activer le plein écran</button>
</div>

<form method="post" action="qcm_submit.php" id="formQcm">
    <input type="hidden" name="triche_detectee" id="triche_detectee" value="0">
    <?php $numero = 1; while ($q = mysqli_fetch_assoc($resultat)): ?>
        <div class="question-box">
            <h3>Question <?php echo $numero; ?> / 10</h3>
            <p><strong><?php echo h($q["texte_question"]); ?></strong></p>
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <label class="reponse-option">
                    <input type="radio" name="reponse[<?php echo intval($q["id_question"]); ?>]" value="<?php echo $i; ?>" required>
                    <?php echo h($q["reponse" . $i]); ?>
                </label>
            <?php endfor; ?>
        </div>
    <?php $numero++; endwhile; ?>
    <button class="btn" type="submit">Valider mes réponses</button>
</form>
<script>
    window.tempsQcmRestant = <?php echo intval($temps_restant); ?>;
</script>
<script src="assets/js/anti_triche.js"></script>
<?php require_once "includes/footer.php"; ?>
