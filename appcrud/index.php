<?php
include 'db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtém todas as tarefas
$sql = "SELECT * FROM tarefas";
$result = $conn->query($sql);
if ($result === FALSE) {
    die("Erro na consulta ao banco de dados: " . $conn->error);
}

// Obtém todas as anotações
$sql_anotacoes = "SELECT * FROM anotacoes";
$result_anotacoes = $conn->query($sql_anotacoes);
if ($result_anotacoes === FALSE) {
    die("Erro na consulta ao banco de dados: " . $conn->error);
}

// Verifica se a página foi carregada com um id de anotação para editar
if (isset($_GET['id'])) {
    $id_anotacao = (int)$_GET['id'];  // Forçando o tipo para evitar injeção de SQL
    $sql_anotacao = "SELECT * FROM anotacoes WHERE id = ?";
    $stmt = $conn->prepare($sql_anotacao);
    $stmt->bind_param("i", $id_anotacao);  // "i" representa um parâmetro inteiro
    $stmt->execute();
    $result_anotacao = $stmt->get_result();
    if ($result_anotacao->num_rows > 0) {
        $anotacao_editada = $result_anotacao->fetch_assoc();
    } else {
        echo "Anotação não encontrada!";
    }
    $stmt->close(); // Fechando a consulta preparada
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function filterTasks(status) {
            const tasks = document.querySelectorAll('.task-item');
            tasks.forEach(task => {
                if (status === 'all' || task.classList.contains(status)) {
                    task.style.display = 'block';
                } else {
                    task.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#">Gerenciador</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5 pt-5">
        <h1 class="text-center">Gerenciador de Tarefas</h1>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tarefas-tab" data-toggle="tab" href="#tarefas" role="tab" aria-controls="tarefas" aria-selected="true">Tarefas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="anotacoes-tab" data-toggle="tab" href="#anotacoes" role="tab" aria-controls="anotacoes" aria-selected="false">Anotações</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tarefas" role="tabpanel" aria-labelledby="tarefas-tab">
                <!-- Seção de Tarefas -->
                <form action="adicionar.php" method="POST" class="mb-3">
                    <div class="form-group">
                        <label for="titulo"><i class="fas fa-tasks"></i> Título da tarefa</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Título da tarefa" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao"><i class="fas fa-align-left"></i> Descrição da tarefa</label>
                        <textarea name="descricao" class="form-control" placeholder="Descrição da tarefa"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="data_conclusao"><i class="fas fa-calendar-alt"></i> Data de Conclusão (dd/mm/aaaa)</label>
                        <input type="text" name="data_conclusao" class="form-control" placeholder="dd/mm/aaaa" maxlength="10" oninput="this.value = this.value.replace(/[^0-9\/]/g, '').replace(/(\..*)\./g, '$1');">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Tarefa</button>
                </form>
                <div class="divider"></div>
                <h2 class="text-center">Lista de Tarefas</h2>
                <div class="mb-3">
                    <button class="btn btn-info" onclick="filterTasks('all')"><i class="fas fa-tasks"></i> Todas</button>
                    <button class="btn btn-warning" onclick="filterTasks('pendente')"><i class="fas fa-clock"></i> Pendentes</button>
                    <button class="btn btn-success" onclick="filterTasks('concluida')"><i class="fas fa-check"></i> Concluídas</button>
                </div>
                <ul class="list-group" id="taskList">
                    <?php
                    while($row = $result->fetch_assoc()):
                        $data_conclusao = DateTime::createFromFormat('Y-m-d H:i:s', $row['data_conclusao']);
                        $hoje = new DateTime();
                        $status_class = '';

                        if ($row['status'] == 'pendente') {
                            $status_class = 'pendente';
                        } elseif ($row['status'] == 'concluida') {
                            $status_class = 'concluida';
                        }

                        // Corrigir a verificação de 'descricao' com isset()
                        $descricao = isset($row['descricao']) ? htmlspecialchars($row['descricao']) : 'Sem descrição';
                    ?>
                        <li class="list-group-item task-item <?php echo htmlspecialchars($status_class); ?>">
                            <span class="task-title <?php echo $status_class == 'concluida' ? 'text-success' : 'text-warning'; ?>">
                                <?php echo htmlspecialchars($row['titulo']); ?>
                            </span> - 
                            <?php echo $descricao; ?>
                            <div class="float-right">
                                <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                                <a href="concluir.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Concluir</a>
                                <a href="excluir.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Excluir</a>
                            </div>
                            <div class="task-date">
                                <small><i class="fas fa-calendar-alt"></i> 
                                <?php 
                                    if ($row['status'] == 'concluida') {
                                        echo "Concluída em: " . date('d/m/Y H:i', strtotime($row['data_conclusao']));
                                    } else {
                                        echo "Concluir até: " . date('d/m/Y', strtotime($row['data_conclusao']));
                                    }
                                ?>
                                </small>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <!-- Seção de Anotações -->
            <div class="tab-pane fade" id="anotacoes" role="tabpanel" aria-labelledby="anotacoes-tab">
                <!-- Formulário de Anotações -->
                <form action="adicionar_anotacao.php" method="POST" class="mb-3">
                    <div class="form-group">
                        <label for="titulo_anotacao"><i class="fas fa-sticky-note"></i> Título da Anotação</label>
                        <input type="text" name="titulo_anotacao" class="form-control" placeholder="Título da anotação" required>
                    </div>
                    <div class="form-group">
                        <label for="conteudo_anotacao"><i class="fas fa-align-left"></i> Conteúdo da Anotação</label>
                        <textarea name="conteudo_anotacao" class="form-control" placeholder="Conteúdo da anotação" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Adicionar Anotação</button>
                </form>
                <h2 class="text-center">Lista de Anotações</h2>
                <ul class="list-group">
                    <?php while($row_anotacao = $result_anotacoes->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <span class="task-title"><?php echo htmlspecialchars($row_anotacao['titulo']); ?></span> - 
                            <span class="task-content"><?php echo htmlspecialchars($row_anotacao['conteudo']); ?></span>
                            <div class="float-right">
                                <a href="editar_anotacao.php?id=<?php echo $row_anotacao['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                                <a href="excluir_anotacao.php?id=<?php echo $row_anotacao['id']; ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Excluir</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
