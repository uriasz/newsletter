<?php
/**
 * API para buscar notícias do banco de dados
 */

require_once __DIR__ . '/../includes/auth.php';
requererLogin();

header('Content-Type: application/json');

// Inclui configuração do banco de dados
include(__DIR__ . '/../includes/db_noticias.php');

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    $numero_noticias = $_GET['limite'] ?? 6;
    $palavraChave = $_GET['palavra_chave'] ?? '';
    
    $busca = "
        SELECT * FROM noticias n
        JOIN tiponot t ON n.idtipo = t.idtipo
        WHERE (
            n.assunto LIKE '%$palavraChave%'
            OR n.descricao LIKE '%$palavraChave%'
        )
        ORDER BY 
            STR_TO_DATE(CONCAT(n.data, ' ', n.hora), '%d/%m/%Y %H:%i') DESC,
            n.idnoticia DESC
        LIMIT 0,$numero_noticias
    ";
    
    $resultado = mysqli_query($conn, $busca);
    
    if (!$resultado) {
        echo json_encode([
            'success' => false, 
            'message' => 'Erro ao consultar notícias',
            'data' => []
        ]);
        exit;
    }
    
    $noticias = [];
    
    while ($dados = mysqli_fetch_array($resultado)) {
        $idnoticia = $dados["idnoticia"];
        $tipo = $dados["tipo"];
        $data = $dados["data"];
        $assunto = $dados["assunto"];
        $foto = $dados["foto"];
        $descricao = isset($dados["descricao"]) ? $dados["descricao"] : '';
        
        // Define imagem da notícia
        if (!empty($foto)) {
            $imagem = "/noticias/arquivo/$foto";
        } else {
            $imagem = "/img2/noticias/recente_sem_foto.png";
        }
        
        $noticias[] = [
            'id' => $idnoticia,
            'tipo' => $tipo,
            'data' => $data,
            'assunto' => $assunto,
            'descricao' => $descricao,
            'foto' => $foto,
            'imagem_url' => $imagem,
            'link' => "https://www.pjf.mg.gov.br/noticias/view.php?modo=link2&idnoticia2=$idnoticia"
        ];
    }
    
    echo json_encode([
        'success' => true,
        'message' => count($noticias) . ' notícias encontradas',
        'data' => $noticias
    ]);
    
    mysqli_close($conn);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido'
    ]);
}
