<?php
require_once "../includes/functions.php";
verifier_admin();
$base_path = "../";
$page_titre = "Question";

$id = intval($_GET["id"] ?? 0);
$question = [
    "id_question" => 0,
    "texte_question" => "",
    "reponse1" => "",
    "reponse2" => "",
    "reponse3" => "",
    "reponse4" => "",
    "bonne_reponse" => 1,
    "categorie" => ""
];

if ($id > 0) {
    $res = mysqli_query($connexion, "SELECT * FROM questions WHERE id_question = $id LIMIT 1");
    $question = mysqli_fetch_assoc($res) ?: $question;
}

require_once "../includes/header.php";
?>
<div class="card form-box" style="max-width:800px">
    <h2><?php echo $id > 0 ? "Modifier" : "Ajouter"; ?> une question</h2>
    <form method="post" action="question_save.php">
        <input type="hidden" name="id_question" value="<?php echo intval($question["id_question"]); ?>">
        <div class="form-group">
            <label>Texte de la question</label>
            <textarea name="texte_question" required><?php echo h($question["texte_question"]); ?></textarea>
        </div>
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="form-group">
                <label>Réponse <?php echo $i; ?></label>
                <input type="text" name="reponse<?php echo $i; ?>" value="<?php echo h($question["reponse" . $i]); ?>" required>
            </div>
        <?php endfor; ?>
        <div class="form-group">
            <label>Bonne réponse</label>
            <select name="bonne_reponse" required>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo intval($question["bonne_reponse"]) === $i ? "selected" : ""; ?>>Réponse <?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Catégorie</label>
            <input type="text" name="categorie" value="<?php echo h($question["categorie"]); ?>">
        </div>
        <button class="btn" type="submit">Enregistrer</button>
        <a class="btn btn-secondary" href="questions.php">Retour</a>
    </form>
</div>
<?php require_once "../includes/footer.php"; ?>
