<?php
$host = 'localhost';
$dbname = 'kanban_sistema';
$username = 'root';
// Tente estas senhas comuns no XAMPP
$password = ''; // Senha vazia (mais comum)
// $password = 'root'; // Alternativa comum

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexão bem-sucedida!"; // Descomente para testar
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>