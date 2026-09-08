<?php
session_start();
require 'config/db.php';

$login_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = trim($_POST["senha"]);

    if (empty($email) || empty($senha)) {
        $login_err = "Por favor, preencha todos os campos.";
    } else {
        $pdo = getDbConnection();
        if ($pdo) {
            $sql = "SELECT id, usuario, senha FROM instituicoes WHERE email = :email";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() == 1) {
                        if ($row = $stmt->fetch()) {
                            $id = $row["id"];
                            $usuario = $row["usuario"];
                            $hashed_password = $row["senha"];
                            if (password_verify($senha, $hashed_password)) {
                                session_start();
                                $_SESSION["user_id"] = $id;
                                $_SESSION["user_name"] = $usuario;
                                header("location: dashboard.php");
                            } else {
                                $login_err = "Senha inválida.";
                            }
                        }
                    } else {
                        $login_err = "Nenhuma conta encontrada com esse e-mail.";
                    }
                } else {
                    $login_err = "Algo deu errado. Por favor, tente novamente mais tarde.";
                }
                unset($stmt);
            }
        }
        unset($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Space - Login</title>
    <link rel="shortcut icon" href="./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/compiled/css/app.css">
    <link rel="stylesheet" href="./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="./assets/compiled/css/iconly.css">
    <link rel="stylesheet" href="./assets/login.css">
</head>

<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg" class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <?php if (!empty($login_err)): ?>
                            <div class="alert alert-danger"><?php echo $login_err; ?></div>
                        <?php endif; ?>

                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example13">E-mail</label>
                            <input type="email" id="form1Example13" class="form-control form-control-lg" name="email" required/>
                        </div>

                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="form1Example23">Senha</label>
                            <input type="password" id="form1Example23" class="form-control form-control-lg" name="senha" required/>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <!-- Checkbox -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
                                <label class="form-check-label" for="form1Example3"> Lembre de mim </label>
                            </div>
                            <a href="#!">Esqueceu a senha?</a>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-lg btn-block w-100">Entrar</button>

                        <div class="divider d-flex align-items-center my-4">
                            <p class="text-center fw-bold mx-3 mb-0 text-muted">OU</p>
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <a class="btn btn-primary btn-lg btn-block w-100" style="background-color: #3b5998" href="#!" role="button">
                                <i class="fab fa-facebook-f me-2"></i>Continue via Facebook
                            </a>
                            <a class="btn btn-primary btn-lg btn-block w-100" style="background-color: #55acee" href="#!" role="button">
                                <i class="fab fa-twitter me-2"></i>Continue via Twitter</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
