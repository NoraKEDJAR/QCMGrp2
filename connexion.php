<?php
require_once "includes/functions.php";
if (est_connecte()) {
    header("Location: dashboard.php");
    exit;
}

$page_titre = "Connexion";
$message = "";

if (isset($_GET["inscription"]) && $_GET["inscription"] === "ok") {
    $message = "Compte créé avec succès. Vous pouvez vous connecter.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = securiser($_POST["email"] ?? "");
    $mot_de_passe = $_POST["mot_de_passe"] ?? "";

    $sql = "SELECT * FROM utilisateurs WHERE email = '$email' LIMIT 1";
    $resultat = mysqli_query($connexion, $sql);

    if ($resultat && mysqli_num_rows($resultat) === 1) {
        $utilisateur = mysqli_fetch_assoc($resultat);
        if ($utilisateur["statut"] === "bloque") {
            $message = "Votre compte est bloqué. Contactez l'administrateur.";
        } elseif (password_verify($mot_de_passe, $utilisateur["mot_de_passe"])) {
            $_SESSION["id_utilisateur"] = $utilisateur["id_utilisateur"];
            $_SESSION["nom"] = $utilisateur["nom"];
            $_SESSION["prenom"] = $utilisateur["prenom"];
            $_SESSION["role"] = $utilisateur["role"];

            if ($utilisateur["role"] === "admin") {
                header("Location: admin/index.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}

require_once "includes/header.php";
?>
<div class="card form-box">
    <h1>Connexion</h1>
    <?php if ($message): ?>
        <div class="alert <?php echo isset($_GET["inscription"]) ? 'alert-success' : 'alert-error'; ?>"><?php echo h($message); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>
        </div>
        <button class="btn" type="submit">Se connecter</button>
    </form>
    <p>Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
    <p class="footer-note">Compte admin de test : admin@qcm.local / admin123</p>
</div>
<?php require_once "includes/footer.php"; ?>
