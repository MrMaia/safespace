<?php
require '../config/db.php';

$pdo = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_vitima = $_POST['nome_vitima'];
    $nome_acusado = $_POST['nome_acusado'];
    $email_vitima = $_POST['email_vitima'];
    $email_acusado = $_POST['email_acusado'];
    $curso_vitima = $_POST['curso_vitima'];
    $curso_acusado = $_POST['curso_acusado'];
    $periodo_vitima = $_POST['periodo_vitima'];
    $periodo_acusado = $_POST['periodo_acusado'];
    $prioridade = $_POST['prioridade'];
    $gravidade = $_POST['gravidade'];
    $ocorrido = $_POST['ocorrido'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO denuncias (nome_vitima, nome_acusado, email_vitima, email_acusado, curso_vitima, curso_acusado, periodo_vitima, periodo_acusado, prioridade, gravidade, ocorrido, status)
            VALUES (:nome_vitima, :nome_acusado, :email_vitima, :email_acusado, :curso_vitima, :curso_acusado, :periodo_vitima, :periodo_acusado, :prioridade, :gravidade, :ocorrido, 1)
        ");
        $stmt->execute([
            'nome_vitima' => $nome_vitima,
            'nome_acusado' => $nome_acusado,
            'email_vitima' => $email_vitima,
            'email_acusado' => $email_acusado,
            'curso_vitima' => $curso_vitima,
            'curso_acusado' => $curso_acusado,
            'periodo_vitima' => $periodo_vitima,
            'periodo_acusado' => $periodo_acusado,
            'prioridade' => $prioridade,
            'gravidade' => $gravidade,
            'ocorrido' => $ocorrido,
        ]);

        $denuncia_id = $pdo->lastInsertId();

        // Processar anexos
        if (!empty($_FILES['anexos']['name'][0])) {
            $uploads_dir = __DIR__ . '/../uploads/anexos';
            foreach ($_FILES['anexos']['tmp_name'] as $key => $tmp_name) {
                $filename = basename($_FILES['anexos']['name'][$key]);
                $filepath = "$uploads_dir/$filename";

                if (move_uploaded_file($tmp_name, $filepath)) {
                    $stmt = $pdo->prepare("
                        INSERT INTO anexos (denuncia_id, file_path)
                        VALUES (:denuncia_id, :file_path)
                    ");
                    $stmt->execute([
                        'denuncia_id' => $denuncia_id,
                        'file_path' => $filepath,
                    ]);
                }
            }
        }

        // Configurar cabeçalhos para UTF-8
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@safespace.com\r\n";

        // Enviar e-mail para a vítima
        $template_vitima_name = "denuncia_cadastrada_vitima";
        $stmt = $pdo->prepare("SELECT subject, body FROM email_templates WHERE template_name = :template_name");
        $stmt->execute(['template_name' => $template_vitima_name]);
        $template_vitima = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($template_vitima) {
            $subject_vitima = $template_vitima['subject'];
            $message_vitima = str_replace(['{{nome_vitima}}'], [$nome_vitima], $template_vitima['body']);
            mail($email_vitima, $subject_vitima, $message_vitima, $headers);
        }

        // Enviar e-mail para o acusado
        $template_acusado_name = "denuncia_cadastrada_acusado";
        $stmt->execute(['template_name' => $template_acusado_name]);
        $template_acusado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($template_acusado) {
            $subject_acusado = $template_acusado['subject'];
            $message_acusado = str_replace(['{{nome_acusado}}'], [$nome_acusado], $template_acusado['body']);
            mail($email_acusado, $subject_acusado, $message_acusado, $headers);
        }

        header('Location: ../pages/cd_clientes.php');
        exit();
    } catch (PDOException $e) {
        echo 'Erro ao cadastrar denúncia: ' . $e->getMessage();
    }
}
?>
