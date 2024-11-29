<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Busca a anotação no banco
    $sql = "SELECT * FROM anotacoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Anotação não encontrada!";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Atualiza a anotação no banco
    $titulo = $_POST['titulo_anotacao'];
    $conteudo = $_POST['conteudo_anotacao'];
    
    $sql = "UPDATE anotacoes SET titulo = ?, conteudo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $titulo, $conteudo, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao atualizar anotação!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Anotação</title>
</head>
<body>
    <form action="editar_anotacao.php?id=<?php echo $id; ?>" method="POST">
        <label for="titulo_anotacao">Título</label>
        <input type="text" name="titulo_anotacao" value="<?php echo htmlspecialchars($row['titulo']); ?>" required>
        <br>
        <label for="conteudo_anotacao">Conteúdo</label>
        <textarea name="conteudo_anotacao" required><?php echo htmlspecialchars($row['conteudo']); ?></textarea>
        <br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>
