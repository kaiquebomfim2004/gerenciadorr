<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM tarefas WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao excluir tarefa: " . $conn->error;
    }
}
?>
