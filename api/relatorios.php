<?php
define('SYSTEM_INIT', true);
require_once __DIR__ . '/../includes/auth.php';
requererLogin();

header('Content-Type: application/json');

$campanhaId = $_GET['campanha_id'] ?? '';

if (empty($campanhaId)) {
    echo json_encode(['success' => false, 'message' => 'ID da campanha não fornecido']);
    exit;
}

// Busca a campanha
$campanhas = lerJSON(CAMPANHAS_JSON);
$campanha = buscarPorID($campanhas, $campanhaId, 'id');

if (!$campanha) {
    echo json_encode(['success' => false, 'message' => 'Campanha não encontrada']);
    exit;
}

// Busca todos os assinantes que deveriam receber a campanha
$assinantes = lerJSON(ASSINANTES_JSON);
$destinatarios = [];

foreach ($assinantes as $assinante) {
    foreach ($campanha['listas'] as $listaId) {
        if (in_array($listaId, $assinante['listas'])) {
            $destinatarios[$assinante['id']] = $assinante;
            break;
        }
    }
}

$totalEnvios = count($destinatarios);

// Busca todas as aberturas da campanha
$aberturas = lerJSON(ABERTURAS_LOG_JSON);
$aberturasFiltradasLista = [];

foreach ($aberturas as $abertura) {
    if ($abertura['campanha_id'] == $campanhaId) {
        $aberturasFiltradasLista[] = $abertura;
    }
}

$totalAberturas = count($aberturasFiltradasLista);

// Agrupa aberturas por assinante
$aberturasAgrupadas = [];
foreach ($aberturasFiltradasLista as $abertura) {
    $assinanteId = $abertura['assinante_id'];
    
    if (!isset($aberturasAgrupadas[$assinanteId])) {
        $aberturasAgrupadas[$assinanteId] = [
            'assinante' => $destinatarios[$assinanteId] ?? null,
            'primeira_abertura' => $abertura['data_abertura'],
            'total_aberturas' => 0
        ];
    }
    
    $aberturasAgrupadas[$assinanteId]['total_aberturas']++;
}

$aberturasUnicas = count($aberturasAgrupadas);
$taxaAbertura = $totalEnvios > 0 ? round(($aberturasUnicas / $totalEnvios) * 100, 2) : 0;

// Prepara dados para exibição
$aberturasDetalhadas = [];
foreach ($aberturasAgrupadas as $dados) {
    if ($dados['assinante']) {
        $aberturasDetalhadas[] = [
            'nome' => $dados['assinante']['nome'],
            'email' => $dados['assinante']['email'],
            'data_abertura' => $dados['primeira_abertura'],
            'total_aberturas' => $dados['total_aberturas']
        ];
    }
}

// Ordena por data de abertura (mais recente primeiro)
usort($aberturasDetalhadas, function($a, $b) {
    return strtotime($b['data_abertura']) - strtotime($a['data_abertura']);
});

echo json_encode([
    'success' => true,
    'data' => [
        'total_envios' => $totalEnvios,
        'total_aberturas' => $totalAberturas,
        'aberturas_unicas' => $aberturasUnicas,
        'taxa_abertura' => $taxaAbertura,
        'aberturas_detalhadas' => $aberturasDetalhadas
    ]
]);
