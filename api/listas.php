<?php
require_once __DIR__ . '/../includes/auth.php';
requererLogin();

header('Content-Type: application/json');

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar todas as listas
        $listas = lerJSON(LISTAS_JSON);
        $assinantes = lerJSON(ASSINANTES_JSON);
        
        // Adicionar contagem de assinantes para cada lista
        foreach ($listas as &$lista) {
            $count = 0;
            foreach ($assinantes as $assinante) {
                if (in_array($lista['id'], $assinante['listas'])) {
                    $count++;
                }
            }
            $lista['total_assinantes'] = $count;
        }
        
        echo json_encode(['success' => true, 'data' => $listas]);
        break;
        
    case 'POST':
        // Adicionar nova lista
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        
        if (empty($nome)) {
            echo json_encode(['success' => false, 'message' => 'Nome da lista é obrigatório']);
            exit;
        }
        
        $listas = lerJSON(LISTAS_JSON);
        
        $novaLista = [
            'id' => gerarNovoID($listas),
            'nome' => $nome,
            'descricao' => $descricao,
            'data_criacao' => date('Y-m-d H:i:s')
        ];
        
        $listas[] = $novaLista;
        
        if (escreverJSON(LISTAS_JSON, $listas)) {
            echo json_encode(['success' => true, 'message' => 'Lista criada com sucesso', 'data' => $novaLista]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar lista']);
        }
        break;
        
    case 'PUT':
        // Atualizar lista
        parse_str(file_get_contents('php://input'), $_PUT);
        
        $id = $_PUT['id'] ?? 0;
        $nome = $_PUT['nome'] ?? '';
        $descricao = $_PUT['descricao'] ?? '';
        
        if (empty($id) || empty($nome)) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            exit;
        }
        
        $listas = lerJSON(LISTAS_JSON);
        
        $novosDados = [
            'nome' => $nome,
            'descricao' => $descricao
        ];
        
        $listas = atualizarPorID($listas, $id, $novosDados);
        
        if (escreverJSON(LISTAS_JSON, $listas)) {
            echo json_encode(['success' => true, 'message' => 'Lista atualizada com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar lista']);
        }
        break;
        
    case 'DELETE':
        // Remover lista
        parse_str(file_get_contents('php://input'), $_DELETE);
        $id = $_DELETE['id'] ?? 0;
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
            exit;
        }
        
        // Remove a lista dos assinantes
        $assinantes = lerJSON(ASSINANTES_JSON);
        foreach ($assinantes as &$assinante) {
            $assinante['listas'] = array_values(array_diff($assinante['listas'], [$id]));
        }
        escreverJSON(ASSINANTES_JSON, $assinantes);
        
        // Remove a lista
        $listas = lerJSON(LISTAS_JSON);
        $listas = removerPorID($listas, $id);
        
        if (escreverJSON(LISTAS_JSON, $listas)) {
            echo json_encode(['success' => true, 'message' => 'Lista removida com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover lista']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Método não permitido']);
        break;
}
