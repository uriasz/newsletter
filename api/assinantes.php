<?php
define('SYSTEM_INIT', true);
require_once __DIR__ . '/../includes/auth.php';
requererLogin();

header('Content-Type: application/json');

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar todos os assinantes
        $assinantes = lerJSON(ASSINANTES_JSON);
        echo json_encode(['success' => true, 'data' => $assinantes]);
        break;
        
    case 'POST':
        // Adicionar novo assinante
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $listas = isset($_POST['listas']) ? json_decode($_POST['listas'], true) : [];
        
        if (empty($nome) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Nome e e-mail são obrigatórios']);
            exit;
        }
        
        $assinantes = lerJSON(ASSINANTES_JSON);
        
        // Verifica se o e-mail já existe
        foreach ($assinantes as $assinante) {
            if ($assinante['email'] === $email) {
                echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado']);
                exit;
            }
        }
        
        $novoAssinante = [
            'id' => gerarNovoID($assinantes),
            'nome' => $nome,
            'email' => $email,
            'listas' => $listas,
            'data_cadastro' => date('Y-m-d H:i:s')
        ];
        
        $assinantes[] = $novoAssinante;
        
        if (escreverJSON(ASSINANTES_JSON, $assinantes)) {
            echo json_encode(['success' => true, 'message' => 'Assinante adicionado com sucesso', 'data' => $novoAssinante]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar assinante']);
        }
        break;
        
    case 'PUT':
        // Atualizar assinante
        parse_str(file_get_contents('php://input'), $_PUT);
        
        $id = $_PUT['id'] ?? 0;
        $nome = $_PUT['nome'] ?? '';
        $email = $_PUT['email'] ?? '';
        $listas = isset($_PUT['listas']) ? json_decode($_PUT['listas'], true) : [];
        
        if (empty($id) || empty($nome) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            exit;
        }
        
        $assinantes = lerJSON(ASSINANTES_JSON);
        
        // Verifica se o e-mail já existe em outro assinante
        foreach ($assinantes as $assinante) {
            if ($assinante['email'] === $email && $assinante['id'] != $id) {
                echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado']);
                exit;
            }
        }
        
        $novosDados = [
            'nome' => $nome,
            'email' => $email,
            'listas' => $listas
        ];
        
        $assinantes = atualizarPorID($assinantes, $id, $novosDados);
        
        if (escreverJSON(ASSINANTES_JSON, $assinantes)) {
            echo json_encode(['success' => true, 'message' => 'Assinante atualizado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar assinante']);
        }
        break;
        
    case 'DELETE':
        // Remover assinante
        parse_str(file_get_contents('php://input'), $_DELETE);
        $id = $_DELETE['id'] ?? 0;
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
            exit;
        }
        
        $assinantes = lerJSON(ASSINANTES_JSON);
        $assinantes = removerPorID($assinantes, $id);
        
        if (escreverJSON(ASSINANTES_JSON, $assinantes)) {
            echo json_encode(['success' => true, 'message' => 'Assinante removido com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover assinante']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Método não permitido']);
        break;
}
