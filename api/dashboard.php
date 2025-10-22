<?php
define('SYSTEM_INIT', true);

header('Content-Type: application/json');

// Busca estatísticas gerais
$assinantes = lerJSON(ASSINANTES_JSON);
$listas = lerJSON(LISTAS_JSON);
$campanhas = lerJSON(CAMPANHAS_JSON);
$aberturas = lerJSON(ABERTURAS_LOG_JSON);

$totalAssinantes = count($assinantes);
$totalListas = count($listas);

// Conta apenas campanhas enviadas
$totalCampanhasEnviadas = 0;
foreach ($campanhas as $campanha) {
    if ($campanha['status'] === 'enviada') {
        $totalCampanhasEnviadas++;
    }
}

// Calcula taxa de abertura média
$taxaAberturaMedia = 0;
$campanhasComEnvios = 0;

foreach ($campanhas as $campanha) {
    if ($campanha['status'] !== 'enviada') continue;
    
    // Conta destinatários
    $destinatarios = 0;
    foreach ($assinantes as $assinante) {
        foreach ($campanha['listas'] as $listaId) {
            if (in_array($listaId, $assinante['listas'])) {
                $destinatarios++;
                break;
            }
        }
    }
    
    // Conta aberturas únicas
    $aberturasUnicas = [];
    foreach ($aberturas as $abertura) {
        if ($abertura['campanha_id'] == $campanha['id']) {
            $aberturasUnicas[$abertura['assinante_id']] = true;
        }
    }
    
    if ($destinatarios > 0) {
        $taxaAberturaMedia += (count($aberturasUnicas) / $destinatarios) * 100;
        $campanhasComEnvios++;
    }
}

if ($campanhasComEnvios > 0) {
    $taxaAberturaMedia = round($taxaAberturaMedia / $campanhasComEnvios, 2);
}

echo json_encode([
    'success' => true,
    'data' => [
        'total_assinantes' => $totalAssinantes,
        'total_listas' => $totalListas,
        'total_campanhas' => $totalCampanhasEnviadas,
        'taxa_abertura_media' => $taxaAberturaMedia
    ]
]);
