<?php
require_once __DIR__ . '/../includes/auth.php';
requererLogin();

header('Content-Type: application/json');

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        // Listar todas as campanhas
        $campanhas = lerJSON(CAMPANHAS_JSON);
        echo json_encode(['success' => true, 'data' => $campanhas]);
        break;
        
    case 'POST':
        // Criar nova campanha
        $assunto = $_POST['assunto'] ?? '';
        $conteudoHtml = $_POST['conteudoHtml'] ?? '';
        $listas = isset($_POST['listas']) ? json_decode($_POST['listas'], true) : [];
        $enviar = isset($_POST['enviar']) && $_POST['enviar'] === 'true';
        
        if (empty($assunto) || empty($conteudoHtml)) {
            echo json_encode(['success' => false, 'message' => 'Assunto e conteúdo são obrigatórios']);
            exit;
        }
        
        $campanhas = lerJSON(CAMPANHAS_JSON);
        
        $campanhaId = gerarIDUnico('camp_');
        
        $novaCampanha = [
            'id' => $campanhaId,
            'assunto' => $assunto,
            'conteudo_html' => $conteudoHtml,
            'listas' => $listas,
            'status' => $enviar ? 'enviada' : 'rascunho',
            'data_criacao' => date('Y-m-d H:i:s'),
            'data_envio' => $enviar ? date('Y-m-d H:i:s') : null
        ];
        
        $campanhas[] = $novaCampanha;
        
        if (escreverJSON(CAMPANHAS_JSON, $campanhas)) {
            // Se for para enviar, processa o envio
            if ($enviar) {
                require_once __DIR__ . '/../includes/mailer.php';
                $resultado = enviarCampanha($campanhaId);
                
                if ($resultado['success']) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Campanha criada e enviada com sucesso! ' . $resultado['message'],
                        'data' => $novaCampanha
                    ]);
                } else {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Campanha criada mas erro no envio: ' . $resultado['message']
                    ]);
                }
            } else {
                echo json_encode(['success' => true, 'message' => 'Campanha salva como rascunho', 'data' => $novaCampanha]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar campanha']);
        }
        break;
        
    case 'PUT':
        // Atualizar campanha
        parse_str(file_get_contents('php://input'), $_PUT);
        
        $id = $_PUT['id'] ?? '';
        $assunto = $_PUT['assunto'] ?? '';
        $conteudoHtml = $_PUT['conteudoHtml'] ?? '';
        $listas = isset($_PUT['listas']) ? json_decode($_PUT['listas'], true) : [];
        
        if (empty($id) || empty($assunto) || empty($conteudoHtml)) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
            exit;
        }
        
        $campanhas = lerJSON(CAMPANHAS_JSON);
        
        $novosDados = [
            'assunto' => $assunto,
            'conteudo_html' => $conteudoHtml,
            'listas' => $listas
        ];
        
        $campanhas = atualizarPorID($campanhas, $id, $novosDados, 'id');
        
        if (escreverJSON(CAMPANHAS_JSON, $campanhas)) {
            echo json_encode(['success' => true, 'message' => 'Campanha atualizada com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar campanha']);
        }
        break;
        
    case 'DELETE':
        // Remover campanha
        parse_str(file_get_contents('php://input'), $_DELETE);
        $id = $_DELETE['id'] ?? '';
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
            exit;
        }
        
        $campanhas = lerJSON(CAMPANHAS_JSON);
        $campanhas = removerPorID($campanhas, $id, 'id');
        
        if (escreverJSON(CAMPANHAS_JSON, $campanhas)) {
            echo json_encode(['success' => true, 'message' => 'Campanha removida com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover campanha']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Método não permitido']);
        break;
}
