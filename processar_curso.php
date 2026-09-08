<?php
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_curso = $_POST['nome_curso'];

    // Obter conexão com o banco de dados
    $conn = getDbConnection();

    if ($conn) {
        // Inserir no banco de dados
        $stmt = $conn->prepare("INSERT INTO cursos (nome_curso) VALUES (:nome_curso)");
        $stmt->bindParam(':nome_curso', $nome_curso);

        if ($stmt->execute()) {
            header("Location: form_cursos.php?status=success");
            exit();
        } else {
            header("Location: form_cursos.php?status=error");
            exit();
        }
    } else {
        header("Location: form_cursos.php?status=error");
        exit();
    }
}
?>
