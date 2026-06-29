<?php
if (!isset($base_path)) {
    $base_path = "";
}
if (!isset($page_titre)) {
    $page_titre = "QCM Platform";
}
$utilisateur_header = utilisateur_courant();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($page_titre); ?></title>
    <link rel="stylesheet" href="<?php echo h($base_path); ?>assets/css/style.css">
</head>
<body>
<div class="layout">
    <?php if (est_connecte()): ?>
        <aside class="sidebar">
            <div class="logo">QCM Platform</div>
            <nav>
                <?php if (est_admin()): ?>
                    <a href="<?php echo h($base_path); ?>admin/index.php">Admin</a>
                    <a href="<?php echo h($base_path); ?>admin/utilisateurs.php">Utilisateurs</a>
                    <a href="<?php echo h($base_path); ?>admin/questions.php">Questions</a>
                    <a href="<?php echo h($base_path); ?>dashboard.php">Espace utilisateur</a>
                <?php else: ?>
                    <a href="<?php echo h($base_path); ?>dashboard.php">Tableau de bord</a>
                    <a href="<?php echo h($base_path); ?>qcm_start.php">Passer un QCM</a>
                    <a href="<?php echo h($base_path); ?>historique.php">Historique</a>
                    <a href="<?php echo h($base_path); ?>statistiques.php">Statistiques</a>
                <?php endif; ?>
                <a href="<?php echo h($base_path); ?>logout.php">Déconnexion</a>
            </nav>
        </aside>
    <?php endif; ?>
    <main class="main <?php echo est_connecte() ? '' : 'main-full'; ?>">
        <?php if (est_connecte()): ?>
            <header class="topbar">
                <h1><?php echo h($page_titre); ?></h1>
                <span><?php echo h($utilisateur_header["prenom"] ?? "Utilisateur"); ?></span>
            </header>
        <?php endif; ?>
