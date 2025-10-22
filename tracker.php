<?php
/**
 * Tracker - Pixel de Rastreamento para Abertura de E-mails
 * Este script registra quando um e-mail é aberto e retorna uma imagem transparente
 */

require_once __DIR__ . '/includes/mailer.php';

// Recebe os parâmetros
$campanhaId = $_GET['cid'] ?? '';
$assinanteId = $_GET['sid'] ?? 0;

// Registra a abertura se os parâmetros forem válidos
if (!empty($campanhaId) && !empty($assinanteId)) {
    registrarAbertura($campanhaId, $assinanteId);
}

// Retorna uma imagem GIF transparente de 1x1 pixel
header('Content-Type: image/gif');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// GIF transparente de 1x1 pixel (43 bytes)
echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
exit;
