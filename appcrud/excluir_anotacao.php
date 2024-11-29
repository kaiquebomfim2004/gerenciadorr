<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Exclui a anotação do banco
    $sql = "DELETE FROM anotacoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao excluir anotação!";
    }
} else {
    echo "ID não fornecido!";
}
?>
