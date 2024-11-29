<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tarefas WHERE id=$id";
    $result = $conn->query($sql);
    $tarefa = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    
    $sql = "UPDATE tarefas SET titulo='$titulo', descricao='$descricao' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Tarefa</h1>
        <form action="editar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $tarefa['id']; ?>">
            <div class="form-group">
                <input type="text" name="titulo" class="form-control" value="<?php echo $tarefa['titulo']; ?>" required>
            </div>
            <div class="form-group">
                <textarea name="descricao" class="form-control"><?php echo $tarefa['descricao']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
