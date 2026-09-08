<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Space - Dashboard</title>
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">
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
                            <h3>Denúncias</h3>
                            <p class="text-subtitle text-muted">Powerful interactive tables with datatables (jQuery required).</p>
                        </div>
                    </div>
                </div>

                <!-- Basic Tables start -->
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <a href="form_clientes.php" class="btn icon icon-left btn-success"><i data-feather="user-plus"></i> Criar Denúncia</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="table1">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome da Vítima</th>
                                            <th>Email Vítima</th>
                                            <th>Nome do Acusado</th>
                                            <th>Email Acusado</th>
                                            <th>Status</th>
                                            <th>Prioridade</th>
                                            <th>Gravidade</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        require 'config/db.php';
                                        $pdo = getDbConnection();
                                        if ($pdo) {
                                            $sql = "SELECT id, nome_vitima, email_vitima, nome_acusado, email_acusado, status, prioridade, gravidade FROM denuncias";
                                            $stmt = $pdo->query($sql);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $statusText = '';
                                                $badgeClass = '';
                                                switch ($row['status']) {
                                                    case 1:
                                                        $statusText = 'Em Progresso';
                                                        $badgeClass = 'warning';
                                                        break;
                                                    case 2:
                                                        $statusText = 'Concluído';
                                                        $badgeClass = 'success';
                                                        break;
                                                    case 3:
                                                        $statusText = 'Cancelado';
                                                        $badgeClass = 'danger';
                                                        break;
                                                }
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nome_vitima']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['email_vitima']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nome_acusado']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['email_acusado']) . "</td>";
                                                echo "<td><span class='badge bg-{$badgeClass}'>" . htmlspecialchars($statusText) . "</span></td>";
                                                echo "<td>" . htmlspecialchars($row['prioridade']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['gravidade']) . "</td>";
                                                echo "<td>";
                                                echo "<a href='ver_detalhes.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary btn-sm'>Ver Detalhes</a> ";
                                                echo "<a href='apagar_denuncia.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja apagar esta denúncia?\")'>Apagar</a>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='9'>Erro ao conectar ao banco de dados.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Tables end -->

            </div>

            <?php include 'telas/footer.php'; ?>
        </div>
    </div>
    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/compiled/js/app.js"></script>
    <script src="assets/extensions/jquery/jquery.min.js"></script>
    <script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/static/js/pages/datatables.js"></script>
</body>

</html>
