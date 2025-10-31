<?php
include 'includes/header.php';
?>

<div class="card">
    <h2>Bem-vindo ao Sistema Kanban</h2>
    <p>Este sistema permite o gerenciamento de tarefas no formato Kanban, organizando as atividades em três colunas: A Fazer, Fazendo e Pronto.</p>
    
    <div class="quick-stats">
        <h3>Estatísticas Rápidas</h3>
        <?php
        include 'includes/database.php';
        
        // Contar tarefas por status
        $stmt = $pdo->query("SELECT status, COUNT(*) as total FROM tarefas GROUP BY status");
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stats_array = ['a_fazer' => 0, 'fazendo' => 0, 'pronto' => 0];
        foreach ($stats as $stat) {
            $stats_array[$stat['status']] = $stat['total'];
        }
        ?>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1rem;">
            <div class="stat-card" style="background: #e74c3c; color: white; padding: 1rem; border-radius: 6px; text-align: center;">
                <h4>A Fazer</h4>
                <p style="font-size: 2rem; margin: 0;"><?php echo $stats_array['a_fazer']; ?></p>
            </div>
            <div class="stat-card" style="background: #f39c12; color: white; padding: 1rem; border-radius: 6px; text-align: center;">
                <h4>Fazendo</h4>
                <p style="font-size: 2rem; margin: 0;"><?php echo $stats_array['fazendo']; ?></p>
            </div>
            <div class="stat-card" style="background: #27ae60; color: white; padding: 1rem; border-radius: 6px; text-align: center;">
                <h4>Pronto</h4>
                <p style="font-size: 2rem; margin: 0;"><?php echo $stats_array['pronto']; ?></p>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>