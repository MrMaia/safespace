<?php
// Inclui o arquivo de conexão com o banco de dados
include 'config/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_vitima = $_POST['campo-vitima'];
    $nome_acusado = $_POST['campo-acusado'];
    $email_vitima = $_POST['campo-email-vitima'];
    $email_acusado = $_POST['campo-email-acusado'];
    $curso_vitima = $_POST['campo-curso-vitima'];
    $curso_acusado = $_POST['campo-curso-acusado'];
    $periodo_vitima = $_POST['campo-periodo-vitima'];
    $periodo_acusado = $_POST['campo-periodo-acusado'];
    $prioridade = $_POST['campo-prioridade'];
    $ocorrido = $_POST['area-ocorrido'];

    // Prepara e executa a query de inserção
    $stmt = $conn->prepare("INSERT INTO denuncias (nome_vitima, nome_acusado, email_vitima, email_acusado, curso_vitima, curso_acusado, periodo_vitima, periodo_acusado, prioridade, ocorrido, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $usuario_id = 1; // Supondo que você esteja associando a denúncia a um usuário específico. Você pode adaptar conforme necessário.
    $stmt->bind_param("ssssssssssi", $nome_vitima, $nome_acusado, $email_vitima, $email_acusado, $curso_vitima, $curso_acusado, $periodo_vitima, $periodo_acusado, $prioridade, $ocorrido, $usuario_id);

    if ($stmt->execute()) {
        // Redireciona para dashboard.php
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
