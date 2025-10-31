<?php
include 'includes/header.php';
include 'includes/database.php';

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    
    // Validação básica
    if (empty($nome) || empty($email)) {
        $mensagem = 'Todos os campos são obrigatórios!';
        $tipo_mensagem = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'E-mail inválido!';
        $tipo_mensagem = 'danger';
    } else {
        try {
            // Verificar se o e-mail já existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $mensagem = 'Este e-mail já está cadastrado!';
                $tipo_mensagem = 'danger';
            } else {
                // Inserir novo usuário
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
                $stmt->execute([$nome, $email]);
                
                $mensagem = 'Cadastro concluído com sucesso!';
                $tipo_mensagem = 'success';
                
                // Limpar o formulário
                $_POST = array();
            }
        } catch (PDOException $e) {
            $mensagem = 'Erro ao cadastrar usuário: ' . $e->getMessage();
            $tipo_mensagem = 'danger';
        }
    }
}
?>

<div class="card">
    <h2>Cadastro de Usuário</h2>
    
    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo_mensagem; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" 
                   value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" 
                   required>
        </div>
        
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                   required>
        </div>
        
        <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
    </form>
</div>

<?php
include 'includes/footer.php';
?>