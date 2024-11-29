<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data_conclusao = date('Y-m-d H:i:s'); // Data atual
    
    $sql = "UPDATE tarefas SET status='concluida', data_conclusao='$data_conclusao' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao concluir tarefa: " . $conn->error;
    }
}
?>
