<?php
require 'config/db.php';
$id = $_GET['id'];
$pdo = getDbConnection();
if ($pdo) {
    $sql = "DELETE FROM cursos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}
header('Location: cd_cursos.php');
exit();
?>
