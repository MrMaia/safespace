<?php
session_start();

// Verifica se o usuário está logado, caso contrário redireciona para a página de login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Obtém o nome do usuário da sessão
$user_name = htmlspecialchars($_SESSION['user_name']);

require '../config/db.php';

$pdo = getDbConnection();
$casosPorMes = array_fill(1, 12, 0); // Inicializa um array com 12 zeros, um para cada mês

if ($pdo) {
    // Consultar a quantidade de casos registrados por mês
    $stmt = $pdo->query("
        SELECT MONTH(data_criacao) AS mes, COUNT(*) AS total
        FROM denuncias
        GROUP BY MONTH(data_criacao)
        ORDER BY MONTH(data_criacao)
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $casosPorMes[(int)$row['mes']] = (int)$row['total'];
    }
}

$casosRegistrados = array_sum($casosPorMes);
$casosEmProgresso = $casosResolvidos = 0;

if ($pdo) {
    // Contar casos em progresso
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM denuncias WHERE status = 1");
    $casosEmProgresso = $stmt->fetchColumn();

    // Contar casos resolvidos
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM denuncias WHERE status = 2");
    $casosResolvidos = $stmt->fetchColumn();
}

// Obter as 3 últimas denúncias
$ultimasDenuncias = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT id, prioridade FROM denuncias ORDER BY id DESC LIMIT 3");
    $ultimasDenuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Space - Dashboard</title>
    <link rel="shortcut icon" href="../assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/compiled/css/app.css">
    <link rel="stylesheet" href="../assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="../assets/compiled/css/iconly.css">
    <script>
        // Dados fornecidos pelo PHP
        var casosPorMes = <?php echo json_encode(array_values($casosPorMes)); ?>;
        var meses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarLinks = document.querySelectorAll(".sidebar-link, .submenu-link");

            sidebarLinks.forEach(link => {
                if (link.href === window.location.href) {
                    link.parentElement.classList.add("active");
                    // Adicionar a classe active ao item pai se estiver dentro de um submenu
                    let parent = link.closest(".has-sub");
                    if (parent) {
                        parent.classList.add("active");
                    }
                }
            });
        });
    </script>

    <script src="../assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/static/js/pages/dashboard.js"></script>
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
                <h3>Bom dia, <?php echo $user_name; ?></h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="row">
                            <div class="col-6 col-lg-4 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                <div class="stats-icon purple mb-2">
                                                    <i class="iconly-boldShow"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Casos Registrados</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $casosRegistrados; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-4 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                <div class="stats-icon blue mb-2">
                                                    <i class="iconly-boldProfile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Casos em Progresso</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $casosEmProgresso; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-4 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                <div class="stats-icon green mb-2">
                                                    <i class="iconly-boldAdd-User"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">Casos Resolvidos</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $casosResolvidos; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Gráfico de Casos</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="chart-profile-visit"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body py-4 px-4">
                                <div class="d-flex flex-lg-column align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xl">
                                            <img src="../assets/compiled/jpg/1.jpg" alt="Face 1">
                                        </div>
                                        <div class="ms-3 name">
                                            <h5 class="font-bold"><?php echo $user_name; ?></h5>
                                            <h6 class="text-muted mb-0">Administrador</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex pt-4 justify-content-center">
                                        <a href="../actions/logout.php" class="btn btn-danger">Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h4 class="card-title">Últimas Denúncias</h4>
                            </div>
                            <div class="card-content pb-4">
                                <?php foreach ($ultimasDenuncias as $denuncia) : ?>
                                    <div class="recent-message d-flex px-4 py-3 align-items-center border-bottom">
                                        <div class="me-3">
                                            <i class="bi bi-exclamation-circle text-primary fs-2"></i>
                                        </div>
                                        <div class="name">
                                            <h5 class="mb-1">ID: <?php echo htmlspecialchars($denuncia['id']); ?></h5>
                                            <h6 class="text-muted mb-0">Prioridade: <?php echo htmlspecialchars($denuncia['prioridade']); ?></h6>
                                        </div>
                                        <div class="ms-auto">
                                            <a href="ver_detalhes.php?id=<?php echo htmlspecialchars($denuncia['id']); ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="px-4 py-3 text-center">
                                    <a href="cd_clientes.php" class='btn btn-outline-primary btn-xl font-bold'>Ver todas</a>
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
    <script src="../assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/static/js/pages/dashboard.js"></script>
</body>

</html>