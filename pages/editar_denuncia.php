<?php
require '../config/db.php';
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

    // Atualizar denúncia
    $sql = "UPDATE denuncias SET nome_vitima = :nome_vitima, nome_acusado = :nome_acusado, email_vitima = :email_vitima, email_acusado = :email_acusado, curso_vitima = :curso_vitima, curso_acusado = :curso_acusado, periodo_vitima = :periodo_vitima, periodo_acusado = :periodo_acusado, prioridade = :prioridade, gravidade = :gravidade, ocorrido = :ocorrido WHERE id = :id";
    $stmt = $pdo->prepare($sql);
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
        'id' => $id,
    ]);

    // Processar anexos
    if (!empty($_FILES['anexos']['name'][0])) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        foreach ($_FILES['anexos']['name'] as $key => $name) {
            $tmpName = $_FILES['anexos']['tmp_name'][$key];
            $filePath = $uploadDir . basename($name);
            if (move_uploaded_file($tmpName, $filePath)) {
                $stmt = $pdo->prepare("INSERT INTO anexos (denuncia_id, file_name, file_path) VALUES (:denuncia_id, :file_name, :file_path)");
                $stmt->execute([
                    'denuncia_id' => $id,
                    'file_name' => $name,
                    'file_path' => $filePath,
                ]);
            }
        }
    }

    header("Location: ver_detalhes.php?id=$id");
    exit();
}

// Remover anexo
if (isset($_GET['remove_anexo'])) {
    $anexo_id = $_GET['remove_anexo'];
    $stmt = $pdo->prepare("SELECT file_path FROM anexos WHERE id = :id");
    $stmt->execute(['id' => $anexo_id]);
    $anexo = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($anexo) {
        unlink($anexo['file_path']);
        $stmt = $pdo->prepare("DELETE FROM anexos WHERE id = :id");
        $stmt->execute(['id' => $anexo_id]);
    }
    header("Location: editar_denuncia.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Denúncia - Safe Space</title>
    <link rel="stylesheet" href="../assets/extensions/choices.js/public/assets/styles/choices.css">
    <link rel="shortcut icon" href="../assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/extensions/filepond/filepond.css">
    <link rel="stylesheet" href="../assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
    <link rel="stylesheet" href="../assets/extensions/toastify-js/src/toastify.css">
    <link rel="stylesheet" href="../assets/compiled/css/app.css">
    <link rel="stylesheet" href="../assets/compiled/css/app-dark.css">
</head>

<body>
    <script src="../assets/static/js/initTheme.js"></script>
    <div id="app">
        <?php include '../includes/sidebar.php'; ?>
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
                            <h3>Editar Denúncia</h3>
                        </div>
                    </div>
                </div>

                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="bi bi-person fs-2"></i> Dados gerais</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <?php if ($denuncia): ?>
                                            <form class="form" method="POST" action="editar_denuncia.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="nome-vitima">Nome da Vítima</label>
                                                            <input type="text" id="nome-vitima" class="form-control" name="nome_vitima" value="<?php echo htmlspecialchars($denuncia['nome_vitima']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="nome-acusado">Nome do Acusado</label>
                                                            <input type="text" id="nome-acusado" class="form-control" name="nome_acusado" value="<?php echo htmlspecialchars($denuncia['nome_acusado']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="email-vitima">E-mail Vítima</label>
                                                            <input type="email" id="email-vitima" class="form-control" name="email_vitima" value="<?php echo htmlspecialchars($denuncia['email_vitima']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="email-acusado">E-mail Acusado</label>
                                                            <input type="email" id="email-acusado" class="form-control" name="email_acusado" value="<?php echo htmlspecialchars($denuncia['email_acusado']); ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-12">
                                                        <label for="curso-vitima">Curso Vítima</label>
                                                        <div class="input-group mb-3">
                                                            <select class="form-select" id="curso-vitima" name="curso_vitima" required>
                                                                <option disabled="disabled" selected>Selecione...</option>
                                                                <option value="Curso 1" <?php echo $denuncia['curso_vitima'] === 'Curso 1' ? 'selected' : ''; ?>>Curso 1</option>
                                                                <option value="Curso 2" <?php echo $denuncia['curso_vitima'] === 'Curso 2' ? 'selected' : ''; ?>>Curso 2</option>
                                                                <option value="Curso 3" <?php echo $denuncia['curso_vitima'] === 'Curso 3' ? 'selected' : ''; ?>>Curso 3</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-12">
                                                        <label for="curso-acusado">Curso Acusado</label>
                                                        <div class="input-group mb-3">
                                                            <select class="form-select" id="curso-acusado" name="curso_acusado" required>
                                                                <option disabled="disabled" selected>Selecione...</option>
                                                                <option value="Curso 1" <?php echo $denuncia['curso_acusado'] === 'Curso 1' ? 'selected' : ''; ?>>Curso 1</option>
                                                                <option value="Curso 2" <?php echo $denuncia['curso_acusado'] === 'Curso 2' ? 'selected' : ''; ?>>Curso 2</option>
                                                                <option value="Curso 3" <?php echo $denuncia['curso_acusado'] === 'Curso 3' ? 'selected' : ''; ?>>Curso 3</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-12">
                                                        <label for="periodo-vitima">Período Vítima</label>
                                                        <div class="input-group mb-3">
                                                            <select class="form-select" id="periodo-vitima" name="periodo_vitima" required>
                                                                <option disabled="disabled" selected>Selecione...</option>
                                                                <option value="Período 1" <?php echo $denuncia['periodo_vitima'] === 'Período 1' ? 'selected' : ''; ?>>Período 1</option>
                                                                <option value="Período 2" <?php echo $denuncia['periodo_vitima'] === 'Período 2' ? 'selected' : ''; ?>>Período 2</option>
                                                                <option value="Período 3" <?php echo $denuncia['periodo_vitima'] === 'Período 3' ? 'selected' : ''; ?>>Período 3</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-12">
                                                        <label for="periodo-acusado">Período Acusado</label>
                                                        <div class="input-group mb-3">
                                                            <select class="form-select" id="periodo-acusado" name="periodo_acusado" required>
                                                                <option disabled="disabled" selected>Selecione...</option>
                                                                <option value="Período 1" <?php echo $denuncia['periodo_acusado'] === 'Período 1' ? 'selected' : ''; ?>>Período 1</option>
                                                                <option value="Período 2" <?php echo $denuncia['periodo_acusado'] === 'Período 2' ? 'selected' : ''; ?>>Período 2</option>
                                                                <option value="Período 3" <?php echo $denuncia['periodo_acusado'] === 'Período 3' ? 'selected' : ''; ?>>Período 3</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-12">
                                                        <label for="prioridade">Prioridade</label>
                                                        <div class="input-group mb-3">
                                                            <select class="form-select" id="prioridade" name="prioridade" required>
                                                                <option disabled="disabled" selected>Selecione...</option>
                                                                <option value="Baixa" <?php echo $denuncia['prioridade'] === 'Baixa' ? 'selected' : ''; ?>>Baixa</option>
                                                                <option value="Média" <?php echo $denuncia['prioridade'] === 'Média' ? 'selected' : ''; ?>>Média</option>
                                                                <option value="Alta" <?php echo $denuncia['prioridade'] === 'Alta' ? 'selected' : ''; ?>>Alta</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-12">
                                                        <label for="gravidade">Gravidade</label>
                                                        <div class="input-group mb-3">
                                                            <select class="form-select" id="gravidade" name="gravidade" required>
                                                                <option disabled="disabled" selected>Selecione...</option>
                                                                <option value="Nível 1" <?php echo $denuncia['gravidade'] === 'Nível 1' ? 'selected' : ''; ?>>Nível 1</option>
                                                                <option value="Nível 2" <?php echo $denuncia['gravidade'] === 'Nível 2' ? 'selected' : ''; ?>>Nível 2</option>
                                                                <option value="Nível 3" <?php echo $denuncia['gravidade'] === 'Nível 3' ? 'selected' : ''; ?>>Nível 3</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group mb-3">
                                                            <label for="ocorrido" class="form-label">Ocorrido</label>
                                                            <textarea class="form-control" id="ocorrido" name="ocorrido" rows="5" required><?php echo htmlspecialchars($denuncia['ocorrido']); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-12">
                                                        <label for="anexos">Anexos</label>
                                                        <input type="file" id="anexos" class="form-control" name="anexos[]" multiple>
                                                        <?php if ($anexos): ?>
                                                            <ul>
                                                                <?php foreach ($anexos as $anexo): ?>
                                                                    <li>
                                                                        <a href="../uploads/<?php echo htmlspecialchars($anexo['file_name']); ?>" download><?php echo htmlspecialchars($anexo['file_name']); ?></a>
                                                                        <a href="editar_denuncia.php?id=<?php echo $id; ?>&remove_anexo=<?php echo $anexo['id']; ?>" class="btn btn-danger btn-sm">Remover</a>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php else: ?>
                                                            <p>Não há anexos.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-sm-12 d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-primary me-1 mb-1">Salvar</button>
                                                        <a href="ver_detalhes.php?id=<?php echo $id; ?>" class="btn btn-light-secondary me-1 mb-1">Cancelar</a>
                                                    </div>
                                                </div>
                                            </form>
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
                <!-- Basic multiple Column Form section end -->
            </div>

            <?php include '../includes/footer.php'; ?>
        </div>
    </div>
    <script src="../assets/static/js/components/dark.js"></script>
    <script src="../assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../assets/compiled/js/app.js"></script>
    <script src="../assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
    <script src="../assets/static/js/pages/form-element-select.js"></script>
</body>
</html>
