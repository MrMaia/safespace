<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Denúncia - Safe Space</title>
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
                            <h3>Cadastrar Denúncia</h3>
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
                                        <form class="form" method="POST" action="processar_denuncia.php" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="nome-vitima">Nome da Vítima</label>
                                                        <input type="text" id="nome-vitima" class="form-control" name="nome_vitima" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="nome-acusado">Nome do Acusado</label>
                                                        <input type="text" id="nome-acusado" class="form-control" name="nome_acusado" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="email-vitima">E-mail Vítima</label>
                                                        <input type="email" id="email-vitima" class="form-control" name="email_vitima" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="email-acusado">E-mail Acusado</label>
                                                        <input type="email" id="email-acusado" class="form-control" name="email_acusado" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-12">
                                                    <label for="curso-vitima">Curso Vítima</label>
                                                    <div class="input-group mb-3">
                                                        <select class="form-select" id="curso-vitima" name="curso_vitima" required>
                                                            <option disabled="disabled" selected>Selecione...</option>
                                                            <option value="Curso 1">Curso 1</option>
                                                            <option value="Curso 2">Curso 2</option>
                                                            <option value="Curso 3">Curso 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-12">
                                                    <label for="curso-acusado">Curso Acusado</label>
                                                    <div class="input-group mb-3">
                                                        <select class="form-select" id="curso-acusado" name="curso_acusado" required>
                                                            <option disabled="disabled" selected>Selecione...</option>
                                                            <option value="Curso 1">Curso 1</option>
                                                            <option value="Curso 2">Curso 2</option>
                                                            <option value="Curso 3">Curso 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-12">
                                                    <label for="periodo-vitima">Período Vítima</label>
                                                    <div class="input-group mb-3">
                                                        <select class="form-select" id="periodo-vitima" name="periodo_vitima" required>
                                                            <option disabled="disabled" selected>Selecione...</option>
                                                            <option value="Período 1">Período 1</option>
                                                            <option value="Período 2">Período 2</option>
                                                            <option value="Período 3">Período 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-12">
                                                    <label for="periodo-acusado">Período Acusado</label>
                                                    <div class="input-group mb-3">
                                                        <select class="form-select" id="periodo-acusado" name="periodo_acusado" required>
                                                            <option disabled="disabled" selected>Selecione...</option>
                                                            <option value="Período 1">Período 1</option>
                                                            <option value="Período 2">Período 2</option>
                                                            <option value="Período 3">Período 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-12">
                                                    <label for="prioridade">Prioridade</label>
                                                    <div class="input-group mb-3">
                                                        <select class="form-select" id="prioridade" name="prioridade" required>
                                                            <option disabled="disabled" selected>Selecione...</option>
                                                            <option value="Baixa">Baixa</option>
                                                            <option value="Média">Média</option>
                                                            <option value="Alta">Alta</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-12">
                                                    <label for="gravidade">Gravidade</label>
                                                    <div class="input-group mb-3">
                                                        <select class="form-select" id="gravidade" name="gravidade" required>
                                                            <option disabled="disabled" selected>Selecione...</option>
                                                            <option value="Nível 1">Nível 1</option>
                                                            <option value="Nível 2">Nível 2</option>
                                                            <option value="Nível 3">Nível 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <label for="ocorrido" class="form-label">Ocorrido</label>
                                                        <textarea class="form-control" id="ocorrido" name="ocorrido" rows="5" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-12">
                                                    <label for="anexos">Anexos</label>
                                                    <input type="file" id="anexos" name="anexos[]" class="filepond" multiple data-allow-reorder="true" data-max-file-size="3MB" data-max-files="10">
                                                </div>
                                                <div class="col-sm-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Criar</button>
                                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Limpar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic multiple Column Form section end -->
            </div>

            <?php include 'telas/footer.php'; ?>
        </div>
    </div>
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>
    <script src="assets/extensions/choices.js/public/assets/scripts/choices.js"></script>
    <script src="assets/extensions/filepond/filepond.js"></script>
    <script src="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.js"></script>
    <script src="assets/static/js/pages/form-element-select.js"></script>
    <script>
        FilePond.create(document.querySelector('input[type="file"]'));
    </script>
</body>
</html>
