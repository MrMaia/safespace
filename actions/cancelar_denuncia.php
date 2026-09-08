<?php
require '../config/db.php';

$id = $_GET['id'];
$pdo = getDbConnection();
if ($pdo) {
    $sql = "UPDATE denuncias SET status = 3 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        echo "Erro ao atualizar o status para 'Cancelado': " . $e->getMessage();
        exit();
    }
}

header('Location: ../pages/ver_detalhes.php?id=' . $id);
exit();
?>
