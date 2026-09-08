<?php
require 'config/db.php';

$id = $_GET['id'];
$pdo = getDbConnection();
if ($pdo) {
    $sql = "UPDATE denuncias SET status = 2 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        echo "Erro ao atualizar o status para 'Concluído': " . $e->getMessage();
        exit();
    }
}

header('Location: ver_detalhes.php?id=' . $id);
exit();
?>
