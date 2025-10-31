<?php
include 'includes/header.php';
include 'includes/database.php';

$mensagem = '';
$tipo_mensagem = '';

if (isset($_GET['excluir'])) {
    $tarefa_id = $_GET['excluir'];
    
    if (isset($_GET['confirmar']) && $_GET['confirmar'] == 'true') {
        try {
            $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ?");
            $stmt->execute([$tarefa_id]);
            
            $mensagem = 'Tarefa excluída com sucesso!';
            $tipo_mensagem = 'success';
        } catch (PDOException $e) {
            $mensagem = 'Erro ao excluir tarefa: ' . $e->getMessage();
            $tipo_mensagem = 'danger';
        }
    } else {
        echo "<script>
            if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
                window.location.href = 'gerenciar_tarefas.php?excluir=$tarefa_id&confirmar=true';
            } else {
                window.location.href = 'gerenciar_tarefas.php';
            }
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar_status'])) {
    $tarefa_id = $_POST['tarefa_id'];
    $novo_status = $_POST['novo_status'];
    
    try {
        $stmt = $pdo->prepare("UPDATE tarefas SET status = ? WHERE id = ?");
        $stmt->execute([$novo_status, $tarefa_id]);
        
        $mensagem = 'Status atualizado com sucesso!';
        $tipo_mensagem = 'success';
    } catch (PDOException $e) {
        $mensagem = 'Erro ao atualizar status: ' . $e->getMessage();
        $tipo_mensagem = 'danger';
    }
}

$tarefas = $pdo->query("
    SELECT t.*, u.nome as usuario_nome 
    FROM tarefas t 
    JOIN usuarios u ON t.usuario_id = u.id 
    ORDER BY t.data_cadastro DESC
")->fetchAll(PDO::FETCH_ASSOC);

$tarefas_a_fazer = array_filter($tarefas, function($tarefa) {
    return $tarefa['status'] == 'a_fazer';
});

$tarefas_fazendo = array_filter($tarefas, function($tarefa) {
    return $tarefa['status'] == 'fazendo';
});

$tarefas_pronto = array_filter($tarefas, function($tarefa) {
    return $tarefa['status'] == 'pronto';
});
?>

<div class="card">
    <h2>Gerenciamento de Tarefas</h2>
    
    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo_mensagem; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>
</div>

<div class="kanban-board">
    <div class="kanban-column column-a-fazer">
        <h3>A Fazer</h3>
        <?php foreach ($tarefas_a_fazer as $tarefa): ?>
            <div class="tarefa-card tarefa-<?php echo $tarefa['prioridade']; ?>">
                <div class="tarefa-header">
                    <strong><?php echo htmlspecialchars($tarefa['setor']); ?></strong>
                    <span class="tarefa-prioridade prioridade-<?php echo $tarefa['prioridade']; ?>">
                        <?php echo ucfirst($tarefa['prioridade']); ?>
                    </span>
                </div>
                <p><?php echo htmlspecialchars($tarefa['descricao']); ?></p>
                <small><strong>Responsável:</strong> <?php echo htmlspecialchars($tarefa['usuario_nome']); ?></small>
                <small><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($tarefa['data_cadastro'])); ?></small>
                
                <form method="POST" action="" class="status-form">
                    <input type="hidden" name="tarefa_id" value="<?php echo $tarefa['id']; ?>">
                    <select name="novo_status" class="status-select" onchange="this.form.submit()">
                        <option value="a_fazer" selected>A Fazer</option>
                        <option value="fazendo">Fazendo</option>
                        <option value="pronto">Pronto</option>
                    </select>
                    <input type="hidden" name="atualizar_status" value="1">
                </form>
                
                <div class="tarefa-actions">
                    <a href="cadastro_tarefa.php?editar=<?php echo $tarefa['id']; ?>" class="btn btn-warning">Editar</a>
                    <a href="gerenciar_tarefas.php?excluir=<?php echo $tarefa['id']; ?>" class="btn btn-danger">Excluir</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="kanban-column column-fazendo">
        <h3>Fazendo</h3>
        <?php foreach ($tarefas_fazendo as $tarefa): ?>
            <div class="tarefa-card tarefa-<?php echo $tarefa['prioridade']; ?>">
                <div class="tarefa-header">
                    <strong><?php echo htmlspecialchars($tarefa['setor']); ?></strong>
                    <span class="tarefa-prioridade prioridade-<?php echo $tarefa['prioridade']; ?>">
                        <?php echo ucfirst($tarefa['prioridade']); ?>
                    </span>
                </div>
                <p><?php echo htmlspecialchars($tarefa['descricao']); ?></p>
                <small><strong>Responsável:</strong> <?php echo htmlspecialchars($tarefa['usuario_nome']); ?></small>
                <small><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($tarefa['data_cadastro'])); ?></small>
                
                <form method="POST" action="" class="status-form">
                    <input type="hidden" name="tarefa_id" value="<?php echo $tarefa['id']; ?>">
                    <select name="novo_status" class="status-select" onchange="this.form.submit()">
                        <option value="a_fazer">A Fazer</option>
                        <option value="fazendo" selected>Fazendo</option>
                        <option value="pronto">Pronto</option>
                    </select>
                    <input type="hidden" name="atualizar_status" value="1">
                </form>
                
                <div class="tarefa-actions">
                    <a href="cadastro_tarefa.php?editar=<?php echo $tarefa['id']; ?>" class="btn btn-warning">Editar</a>
                    <a href="gerenciar_tarefas.php?excluir=<?php echo $tarefa['id']; ?>" class="btn btn-danger">Excluir</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="kanban-column column-pronto">
        <h3>Pronto</h3>
        <?php foreach ($tarefas_pronto as $tarefa): ?>
            <div class="tarefa-card tarefa-<?php echo $tarefa['prioridade']; ?>">
                <div class="tarefa-header">
                    <strong><?php echo htmlspecialchars($tarefa['setor']); ?></strong>
                    <span class="tarefa-prioridade prioridade-<?php echo $tarefa['prioridade']; ?>">
                        <?php echo ucfirst($tarefa['prioridade']); ?>
                    </span>
                </div>
                <p><?php echo htmlspecialchars($tarefa['descricao']); ?></p>
                <small><strong>Responsável:</strong> <?php echo htmlspecialchars($tarefa['usuario_nome']); ?></small>
                <small><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($tarefa['data_cadastro'])); ?></small>
                
                <form method="POST" action="" class="status-form">
                    <input type="hidden" name="tarefa_id" value="<?php echo $tarefa['id']; ?>">
                    <select name="novo_status" class="status-select" onchange="this.form.submit()">
                        <option value="a_fazer">A Fazer</option>
                        <option value="fazendo">Fazendo</option>
                        <option value="pronto" selected>Pronto</option>
                    </select>
                    <input type="hidden" name="atualizar_status" value="1">
                </form>
                
                <div class="tarefa-actions">
                    <a href="cadastro_tarefa.php?editar=<?php echo $tarefa['id']; ?>" class="btn btn-warning">Editar</a>
                    <a href="gerenciar_tarefas.php?excluir=<?php echo $tarefa['id']; ?>" class="btn btn-danger">Excluir</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
include 'includes/footer.php';
?>