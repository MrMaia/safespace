<?php
require '../config/db.php';
$id = $_GET['id'];
$pdo = getDbConnection();
if ($pdo) {
    $sql = "DELETE FROM denuncias WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}
header('Location: ../pages/cd_clientes.php');
exit();
?>
