<?php
require_once "../includes/functions.php";
verifier_admin();
$base_path = "../";
$page_titre = "Gestion des utilisateurs";

$res = mysqli_query($connexion, "SELECT id_utilisateur, nom, prenom, email, role, statut, date_creation FROM utilisateurs ORDER BY id_utilisateur DESC");
require_once "../includes/header.php";
?>
<div class="card">
    <h2>Gestion des utilisateurs</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php while ($u = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?php echo intval($u["id_utilisateur"]); ?></td>
                <td><?php echo h($u["nom"]); ?></td>
                <td><?php echo h($u["prenom"]); ?></td>
                <td><?php echo h($u["email"]); ?></td>
                <td><?php echo h($u["role"]); ?></td>
                <td><?php echo h($u["statut"]); ?></td>
                <td class="actions">
                    <?php if (intval($u["id_utilisateur"]) !== intval($_SESSION["id_utilisateur"])): ?>
                        <?php if ($u["statut"] === "actif"): ?>
                            <a class="btn btn-warning" href="utilisateur_action.php?action=bloquer&id=<?php echo intval($u["id_utilisateur"]); ?>">Bloquer</a>
                        <?php else: ?>
                            <a class="btn btn-success" href="utilisateur_action.php?action=activer&id=<?php echo intval($u["id_utilisateur"]); ?>">Activer</a>
                        <?php endif; ?>
                        <a class="btn btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')" href="utilisateur_action.php?action=supprimer&id=<?php echo intval($u["id_utilisateur"]); ?>">Supprimer</a>
                    <?php else: ?>
                        <em>Compte actuel</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php require_once "../includes/footer.php"; ?>
