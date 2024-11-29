<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        $erro = "Erro ao registrar usuário: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
        }
        .register-container h1 {
            margin-bottom: 30px;
        }
        .form-group i {
            margin-right: 10px;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1 class="text-center">Registro</h1>
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <div class="form-group">
                <label for="nome"><i class="fas fa-user"></i> Nome</label>
                <input type="text" name="nome" class="form-control" placeholder="Digite seu nome" required>
            </div>
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" placeholder="Digite seu email" required>
            </div>
            <div class="form-group">
                <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Registrar</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
