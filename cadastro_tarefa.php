<?php
include 'includes/header.php';
include 'includes/database.php';

$mensagem = '';
$tipo_mensagem = '';

$usuarios = $pdo->query("SELECT id, nome FROM usuarios ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $descricao = trim($_POST['descricao']);
    $setor = trim($_POST['setor']);
    $prioridade = $_POST['prioridade'];
    
    if (empty($descricao) || empty($setor) || empty($usuario_id)) {
        $mensagem = 'Todos os campos são obrigatórios!';
        $tipo_mensagem = 'danger';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO tarefas (usuario_id, descricao, setor, prioridade, data_cadastro, status) VALUES (?, ?, ?, ?, NOW(), 'a_fazer')");
            $stmt->execute([$usuario_id, $descricao, $setor, $prioridade]);
            
            $mensagem = 'Tarefa cadastrada com sucesso!';
            $tipo_mensagem = 'success';
            
            $_POST = array();
        } catch (PDOException $e) {
            $mensagem = 'Erro ao cadastrar tarefa: ' . $e->getMessage();
            $tipo_mensagem = 'danger';
        }
    }
}
?>

<div class="card">
    <h2>Cadastro de Tarefa</h2>
    
    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo_mensagem; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="usuario_id">Usuário:</label>
            <select id="usuario_id" name="usuario_id" class="form-control" required>
                <option value="">Selecione um usuário</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?php echo $usuario['id']; ?>" 
                            <?php echo (isset($_POST['usuario_id']) && $_POST['usuario_id'] == $usuario['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($usuario['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="descricao">Descrição da Tarefa:</label>
            <textarea id="descricao" name="descricao" class="form-control" rows="4" required><?php echo isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="setor">Setor:</label>
            <input type="text" id="setor" name="setor" class="form-control" 
                   value="<?php echo isset($_POST['setor']) ? htmlspecialchars($_POST['setor']) : ''; ?>" 
                   required>
        </div>
        
        <div class="form-group">
            <label for="prioridade">Prioridade:</label>
            <select id="prioridade" name="prioridade" class="form-control" required>
                <option value="baixa" <?php echo (isset($_POST['prioridade']) && $_POST['prioridade'] == 'baixa') ? 'selected' : ''; ?>>Baixa</option>
                <option value="media" <?php echo (isset($_POST['prioridade']) && $_POST['prioridade'] == 'media') ? 'selected' : ''; ?>>Média</option>
                <option value="alta" <?php echo (isset($_POST['prioridade']) && $_POST['prioridade'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Cadastrar Tarefa</button>
    </form>
</div>

<?php
include 'includes/footer.php';
?>