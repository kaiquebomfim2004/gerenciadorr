<?php
include 'db.php';


$sql = "SELECT * FROM tarefas WHERE status='pendente' AND data_conclusao <= NOW() + INTERVAL 1 DAY";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    $to = "usuario@exemplo.com";
    $subject = "Lembrete: Tarefa Pendente";
    $message = "A tarefa '{$row['titulo']}' está pendente e deve ser concluída até {$row['data_conclusao']}.";
    $headers = "From: noreply@seuprojeto.com";

    mail($to, $subject, $message, $headers);
}
?>
