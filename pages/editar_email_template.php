<?php
require '../config/db.php';

$pdo = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    try {
        $stmt = $pdo->prepare("UPDATE email_templates SET subject = :subject, body = :body WHERE id = :id");
        $stmt->execute([
            'subject' => $subject,
            'body' => $body,
            'id' => $id,
        ]);
        echo "Template atualizado com sucesso!";
    } catch (PDOException $e) {
        echo 'Erro ao atualizar o template: ' . $e->getMessage();
    }
}

// Busca os templates existentes
try {
    $stmt = $pdo->query("SELECT * FROM email_templates");
    $templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro ao buscar templates: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Templates de E-mail</title>
    <link rel="stylesheet" href="../assets/extensions/choices.js/public/assets/styles/choices.css">
    <link rel="shortcut icon" href="../assets/compiled/svg/favicon.svg" type="image/x-icon">
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
                            <h3>Editar Templates de E-mail</h3>
                        </div>
                    </div>
                </div>

                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="bi bi-envelope fs-2"></i> Detalhes dos Templates</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <?php if ($templates): ?>
                                            <?php foreach ($templates as $template): ?>
                                                <h5>Template: <?php echo htmlspecialchars($template['template_name']); ?></h5>
                                                <form class="form" method="POST" action="editar_email_template.php">
                                                    <input type="hidden" name="id" value="<?php echo $template['id']; ?>">
                                                    <div class="row">
                                                        <div class="col-md-12 col-12">
                                                            <div class="form-group">
                                                                <label for="subject_<?php echo $template['id']; ?>">Assunto</label>
                                                                <input type="text" id="subject_<?php echo $template['id']; ?>" class="form-control" name="subject" value="<?php echo htmlspecialchars($template['subject']); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-12">
                                                            <div class="form-group">
                                                                <label for="body_<?php echo $template['id']; ?>">Corpo do E-mail</label>
                                                                <textarea id="body_<?php echo $template['id']; ?>" class="form-control" name="body" rows="10" required><?php echo htmlspecialchars($template['body']); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 d-flex justify-content-end">
                                                            <button type="submit" class="btn btn-primary me-1 mb-1">Salvar</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <hr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>Nenhum template encontrado.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
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
