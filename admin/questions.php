<?php
require_once "../includes/functions.php";
verifier_admin();
$base_path = "../";
$page_titre = "Gestion des questions";

$res = mysqli_query($connexion, "SELECT * FROM questions ORDER BY id_question DESC");
require_once "../includes/header.php";
?>
<div class="card">
    <h2>Gestion des questions</h2>
    <p><a class="btn" href="question_form.php">Ajouter une question</a></p>
    <table>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Catégorie</th>
            <th>Bonne réponse</th>
            <th>Actions</th>
        </tr>
        <?php while ($q = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo intval($q["id_question"]); ?></td>
                <td><?php echo h($q["texte_question"]); ?></td>
                <td><?php echo h($q["categorie"]); ?></td>
                <td><?php echo intval($q["bonne_reponse"]); ?></td>
                <td class="actions">
                    <a class="btn btn-secondary" href="question_form.php?id=<?php echo intval($q["id_question"]); ?>">Modifier</a>
                    <a class="btn btn-danger" onclick="return confirm('Supprimer cette question ?')" href="question_delete.php?id=<?php echo intval($q["id_question"]); ?>">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php require_once "../includes/footer.php"; ?>
