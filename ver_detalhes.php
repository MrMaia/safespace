<?php
require 'config/db.php';
$id = $_GET['id'];
$pdo = getDbConnection();
if ($pdo) {
    $sql = "SELECT * FROM denuncias WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $denuncia = $stmt->fetch(PDO::FETCH_ASSOC);

    // Buscar anexos relacionados à denúncia
    $sql_anexos = "SELECT * FROM anexos WHERE denuncia_id = :id";
    $stmt_anexos = $pdo->prepare($sql_anexos);
    $stmt_anexos->execute(['id' => $id]);
    $anexos = $stmt_anexos->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['concluir'])) {
        $sql = "UPDATE denuncias SET status = 2 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        sendConcluirEmail($denuncia);
        header("Location: ver_detalhes.php?id=$id");
        exit();
    } elseif (isset($_POST['cancelar'])) {
        $sql = "UPDATE denuncias SET status = 3 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        sendCancelEmail($denuncia);
        header("Location: ver_detalhes.php?id=$id");
        exit();
    }
}

function sendConcluirEmail($denuncia) {
    // Configurar cabeçalhos para UTF-8
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@safespace.com\r\n";

    // E-mail padrão para a vítima
    $subject_vitima = "Safespace - Denúncia Concluída";
    $message_vitima = "Sua denúncia foi concluída com sucesso. Agradecemos pela sua paciência.";
    mail($denuncia['email_vitima'], $subject_vitima, $message_vitima, $headers);

    // E-mail padrão para o acusado
    $subject_acusado = "Safespace - Denúncia Concluída";
    $message_acusado = "A denúncia contra você foi concluída. Entre em contato para mais informações.";
    mail($denuncia['email_acusado'], $subject_acusado, $message_acusado, $headers);
}

function sendCancelEmail($denuncia) {
    // Configurar cabeçalhos para UTF-8
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@safespace.com\r\n";

    // E-mail padrão para cancelamento - vítima
    $subject_vitima = "Safespace - Denúncia Cancelada";
    $message_vitima = "Sua denúncia foi cancelada. Entre em contato para mais informações.";
    mail($denuncia['email_vitima'], $subject_vitima, $message_vitima, $headers);

    // E-mail padrão para cancelamento - acusado
    $subject_acusado = "Safespace - Denúncia Cancelada";
    $message_acusado = "A denúncia contra você foi cancelada. Entre em contato para mais informações.";
    mail($denuncia['email_acusado'], $subject_acusado, $message_acusado, $headers);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Denúncia</title>
    <link rel="stylesheet" href="assets/extensions/choices.js/public/assets/styles/choices.css">
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="assets/extensions/filepond/filepond.css">
    <link rel="stylesheet" href="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
    <link rel="stylesheet" href="assets/extensions/toastify-js/src/toastify.css">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'telas/sidebar.php'; ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Detalhes da Denúncia</h3>
                        </div>
                    </div>
                </div>

                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <i class="bi bi-person fs-2"></i> Dados gerais
                                        <div class="float-end">
                                            <form method="POST" style="display:inline;">
                                                <button type="submit" name="concluir" class="btn btn-success">Concluir Denúncia</button>
                                                <button type="submit" name="cancelar" class="btn btn-danger">Cancelar Denúncia</button>
                                                <a href="editar_denuncia.php?id=<?php echo htmlspecialchars($id); ?>" class="btn btn-warning text-white">Editar Denúncia</a>
                                            </form>
                                        </div>
                                    </h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <?php if ($denuncia): ?>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>ID:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['id']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Nome da Vítima:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['nome_vitima']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Email da Vítima:</label>
                                                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($denuncia['email_vitima']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Nome do Acusado:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['nome_acusado']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Email do Acusado:</label>
                                                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($denuncia['email_acusado']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Curso da Vítima:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['curso_vitima']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Curso do Acusado:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['curso_acusado']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Período da Vítima:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['periodo_vitima']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Período do Acusado:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['periodo_acusado']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Prioridade:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['prioridade']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Gravidade:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['gravidade']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-12">
                                                    <div class="form-group">
                                                        <label>Ocorrido:</label>
                                                        <textarea class="form-control" rows="5" readonly><?php echo htmlspecialchars($denuncia['ocorrido']); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label>Status:</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($denuncia['status']); ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-12">
                                                    <div class="form-group">
                                                        <label>Anexos:</label>
                                                        <?php if ($anexos): ?>
                                                            <ul>
                                                                <?php foreach ($anexos as $anexo): ?>
                                                                    <li><a href="uploads/<?php echo htmlspecialchars($anexo['file_name']); ?>" download><?php echo htmlspecialchars($anexo['file_name']); ?></a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php else: ?>
                                                            <p>Não há anexos.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex justify-content-end">
                                                    <a href="cd_clientes.php" class="btn btn-secondary me-1 mb-1">Voltar</a>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <p>Denúncia não encontrada.</p>
                                            <a href="cd_clientes.php" class="btn btn-secondary">Voltar</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include 'telas/footer.php'; ?>
        </div>
    </div>
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>
    <script src="assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
    <script src="assets/static/js/pages/form-element-select.js"></script>
</body>
</html>
