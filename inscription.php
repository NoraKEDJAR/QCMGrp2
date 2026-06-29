<?php
require_once "includes/functions.php";
if (est_connecte()) {
    header("Location: dashboard.php");
    exit;
}

$page_titre = "Inscription";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = securiser($_POST["nom"] ?? "");
    $prenom = securiser($_POST["prenom"] ?? "");
    $email = securiser($_POST["email"] ?? "");
    $mot_de_passe = $_POST["mot_de_passe"] ?? "";
    $confirmation = $_POST["confirmation"] ?? "";

    if ($nom === "" || $prenom === "" || $email === "" || $mot_de_passe === "") {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "L'adresse email n'est pas valide.";
    } elseif (strlen($mot_de_passe) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($mot_de_passe !== $confirmation) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $verif = mysqli_query($connexion, "SELECT id_utilisateur FROM utilisateurs WHERE email = '$email' LIMIT 1");
        if (mysqli_num_rows($verif) > 0) {
            $message = "Cet email est déjà utilisé.";
        } else {
            $hash = mysqli_real_escape_string($connexion, password_hash($mot_de_passe, PASSWORD_DEFAULT));
            $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, statut)
                    VALUES ('$nom', '$prenom', '$email', '$hash', 'user', 'actif')";
            if (mysqli_query($connexion, $sql)) {
                header("Location: connexion.php?inscription=ok");
                exit;
            } else {
                $message = "Erreur lors de l'inscription.";
            }
        }
    }
}

require_once "includes/header.php";
?>
<div class="card form-box">
    <h1>Inscription</h1>
    <?php if ($message): ?><div class="alert alert-error"><?php echo h($message); ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" required>
        </div>
        <div class="form-group">
            <label>Prénom</label>
            <input type="text" name="prenom" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>
        </div>
        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="confirmation" required>
        </div>
        <button class="btn" type="submit">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
</div>
<?php require_once "includes/footer.php"; ?>
