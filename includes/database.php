<?php
function conectarBanco() {
    $host = 'localhost';
    $dbname = 'kanban_sistema';
    
    $credenciais = [
        ['root', ''],      
        ['root', 'root'],  
        ['root', 'password'], 
    ];
    
    foreach ($credenciais as $credencial) {
        $username = $credencial[0];
        $password = $credencial[1];
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<!-- Conexão bem-sucedida com usuário: $username -->";
            return $pdo;
        } catch(PDOException $e) {
            continue;
        }
    }
    
    die("Erro: Não foi possível conectar ao MySQL. Verifique se:<br>
         1. O MySQL está rodando no XAMPP<br>
         2. As credenciais estão corretas<br>
         3. O banco 'kanban_sistema' existe");
}

$pdo = conectarBanco();
?>