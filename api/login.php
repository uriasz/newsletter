<?php
define('SYSTEM_INIT', true);
ob_start(); // Inicia buffer de saída para evitar qualquer saída antes do JSON
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

$jsonResponse = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        
        if (empty($email) || empty($senha)) {
            $jsonResponse = ['success' => false, 'message' => 'Preencha todos os campos'];
        } else {
            $resultado = fazerLogin($email, $senha);
            if ($resultado === true) {
                $jsonResponse = ['success' => true, 'message' => 'Login realizado com sucesso'];
            } else {
                $jsonResponse = ['success' => false, 'message' => $resultado];
            }
        }
    } else {
        $jsonResponse = ['success' => false, 'message' => 'Método não permitido'];
    }
} catch (Throwable $e) {
    $jsonResponse = ['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()];
}

// Limpa qualquer saída inesperada
ob_end_clean();
echo json_encode($jsonResponse);
