<?php
/**
 * Sistema de Envio de E-mails com Rastreamento
 */

require_once __DIR__ . '/config.php';

/**
 * Envia uma campanha para todos os assinantes das listas selecionadas
 * @param string $campanhaId ID da campanha
 * @return array ['success' => bool, 'message' => string]
 */
function enviarCampanha($campanhaId) {
    $campanhas = lerJSON(CAMPANHAS_JSON);
    $campanha = buscarPorID($campanhas, $campanhaId, 'id');
    
    if (!$campanha) {
        return ['success' => false, 'message' => 'Campanha não encontrada'];
    }
    
    // Busca todos os assinantes das listas da campanha
    $assinantes = lerJSON(ASSINANTES_JSON);
    $destinatarios = [];
    
    foreach ($assinantes as $assinante) {
        // Verifica se o assinante está em alguma das listas da campanha
        $estaNaLista = false;
        foreach ($campanha['listas'] as $listaId) {
            if (in_array($listaId, $assinante['listas'])) {
                $estaNaLista = true;
                break;
            }
        }
        
        if ($estaNaLista) {
            $destinatarios[] = $assinante;
        }
    }
    
    if (empty($destinatarios)) {
        return ['success' => false, 'message' => 'Nenhum assinante encontrado nas listas selecionadas'];
    }
    
    $enviados = 0;
    $erros = 0;
    
    foreach ($destinatarios as $assinante) {
        $resultado = enviarEmailComRastreamento(
            $assinante['email'],
            $assinante['nome'],
            $campanha['assunto'],
            $campanha['conteudo_html'],
            $campanhaId,
            $assinante['id']
        );
        
        if ($resultado) {
            $enviados++;
        } else {
            $erros++;
        }
    }
    
    $mensagem = "Enviados: $enviados";
    if ($erros > 0) {
        $mensagem .= " | Erros: $erros";
    }
    
    return ['success' => true, 'message' => $mensagem];
}

/**
 * Envia um e-mail individual com pixel de rastreamento
 * @param string $emailDestino
 * @param string $nomeDestino
 * @param string $assunto
 * @param string $conteudoHtml
 * @param string $campanhaId
 * @param int $assinanteId
 * @return bool
 */
function enviarEmailComRastreamento($emailDestino, $nomeDestino, $assunto, $conteudoHtml, $campanhaId, $assinanteId) {
    // URL do pixel de rastreamento
    $pixelUrl = SITE_URL . "/tracker.php?cid=$campanhaId&sid=$assinanteId";
    
    // Adiciona o pixel de rastreamento ao final do HTML
    $pixelHtml = "<img src=\"$pixelUrl\" width=\"1\" height=\"1\" alt=\"\" style=\"display:block;\" />";
    $conteudoComPixel = $conteudoHtml . $pixelHtml;
    
    // Substitui placeholder de nome, se houver
    $conteudoComPixel = str_replace('[NOME]', $nomeDestino, $conteudoComPixel);
    
    // Headers do e-mail
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
    $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Envia o e-mail usando a função mail() do PHP
    // NOTA: Em produção, considere usar bibliotecas como PHPMailer ou SwiftMailer
    // para melhor compatibilidade e recursos avançados (SMTP, autenticação, etc.)
    
    $resultado = mail($emailDestino, $assunto, $conteudoComPixel, $headers);
    
    // Log de envio (opcional)
    if ($resultado) {
        error_log("E-mail enviado para: $emailDestino - Campanha: $campanhaId");
    } else {
        error_log("Erro ao enviar e-mail para: $emailDestino - Campanha: $campanhaId");
    }
    
    return $resultado;
}

/**
 * Registra uma abertura de e-mail
 * @param string $campanhaId
 * @param int $assinanteId
 * @return bool
 */
function registrarAbertura($campanhaId, $assinanteId) {
    $aberturas = lerJSON(ABERTURAS_LOG_JSON);
    
    // Verifica se já foi registrada essa abertura (evita duplicatas na mesma execução)
    foreach ($aberturas as $abertura) {
        if ($abertura['campanha_id'] == $campanhaId && 
            $abertura['assinante_id'] == $assinanteId) {
            // Já existe registro, mas permitimos múltiplas aberturas
            // para análise mais detalhada
        }
    }
    
    $novaAbertura = [
        'campanha_id' => $campanhaId,
        'assinante_id' => $assinanteId,
        'data_abertura' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    $aberturas[] = $novaAbertura;
    
    return escreverJSON(ABERTURAS_LOG_JSON, $aberturas);
}
