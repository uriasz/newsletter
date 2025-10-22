<?php
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
        exit;
    }
    
    $resultado = fazerLogin($email, $senha);
    
    if ($resultado === true) {
        echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => $resultado]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}
