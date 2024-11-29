<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo_anotacao = $_POST['titulo_anotacao'];
    $conteudo_anotacao = $_POST['conteudo_anotacao'];

    // Verifica se é uma atualização ou uma inserção
    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Atualiza a anotação
        $id_anotacao = $_POST['id'];
        $sql = "UPDATE anotacoes SET titulo = '$titulo_anotacao', conteudo = '$conteudo_anotacao' WHERE id = $id_anotacao";
    } else {
        // Adiciona uma nova anotação
        $sql = "INSERT INTO anotacoes (titulo, conteudo) VALUES ('$titulo_anotacao', '$conteudo_anotacao')";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>
