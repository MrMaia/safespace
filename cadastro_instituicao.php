<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Instituição</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mt-5">Cadastro de Instituição</h1>
            <form action="cadastro_instituicao.php" method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                require 'config/db.php';

                $email = $_POST['email'];
                $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
                $usuario = $_POST['usuario'];

                $pdo = getDbConnection();
                if ($pdo) {
                    $sql = "INSERT INTO instituicoes (email, senha, usuario) VALUES (:email, :senha, :usuario)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['email' => $email, 'senha' => $senha, 'usuario' => $usuario]);

                    echo "<p class='alert alert-success mt-3'>Instituição cadastrada com sucesso!</p>";
                }
            }
            ?>
        </div>
    </div>
</div>
<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
