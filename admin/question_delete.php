<?php
require_once "../includes/functions.php";
verifier_admin();
$id = intval($_GET["id"] ?? 0);
if ($id > 0) {
    mysqli_query($connexion, "DELETE FROM questions WHERE id_question = $id");
}
header("Location: questions.php");
exit;
?>
