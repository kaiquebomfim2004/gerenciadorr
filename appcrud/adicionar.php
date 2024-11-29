<?php
include 'db.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $data_conclusao = mysqli_real_escape_string($conn, $_POST['data_conclusao']);
    
    // Validando se a data de conclusão foi inserida
    if (empty($titulo) || empty($descricao) || empty($data_conclusao)) {
        $error_message = "Por favor, preencha todos os campos.";
    } else {
        // Insere a nova tarefa no banco de dados com status 'pendente'
        $sql = "INSERT INTO tarefas (titulo, descricao, data_conclusao, status) 
                VALUES ('$titulo', '$descricao', STR_TO_DATE('$data_conclusao', '%d/%m/%Y'), 'pendente')";
        
        if ($conn->query($sql) === TRUE) {
            // Redireciona para a página principal após a inserção
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Erro ao adicionar tarefa: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Tarefa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Adicionar Nova Tarefa</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="adicionar.php" method="POST">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" class="form-control" placeholder="Título da tarefa" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" class="form-control" placeholder="Descrição da tarefa" required></textarea>
            </div>
            <div class="form-group">
                <label for="data_conclusao">Data de Conclusão</label>
                <input type="text" name="data_conclusao" class="form-control" placeholder="dd/mm/aaaa" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Tarefa</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
