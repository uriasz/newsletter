<?php
/**
 * JSON Handler - Funções para leitura e escrita segura de arquivos JSON
 * Implementa flock() para prevenir corrupção de dados em acessos concorrentes
 */

// Bloquear acesso direto
if (!defined('SYSTEM_INIT')) {
    header('HTTP/1.0 403 Forbidden');
    exit('Acesso direto não permitido');
}

/**
 * Lê um arquivo JSON e retorna um array PHP
 * @param string $arquivo Caminho do arquivo JSON
 * @return array Array com os dados do JSON ou array vazio em caso de erro
 */
function lerJSON($arquivo) {
    // Verifica se o arquivo existe
    if (!file_exists($arquivo)) {
        return [];
    }
    
    // Abre o arquivo para leitura
    $file = fopen($arquivo, 'r');
    
    if ($file === false) {
        error_log("Erro ao abrir arquivo para leitura: $arquivo");
        return [];
    }
    
    // Aplica trava compartilhada (LOCK_SH) para leitura
    if (flock($file, LOCK_SH)) {
        // Lê o conteúdo do arquivo
        $conteudo = fread($file, filesize($arquivo) ?: 1);
        
        // Libera a trava
        flock($file, LOCK_UN);
        fclose($file);
        
        // Decodifica o JSON
        $dados = json_decode($conteudo, true);
        
        // Retorna array vazio se houver erro na decodificação
        return $dados === null ? [] : $dados;
    } else {
        fclose($file);
        error_log("Não foi possível travar o arquivo para leitura: $arquivo");
        return [];
    }
}

/**
 * Escreve um array PHP em um arquivo JSON com trava exclusiva
 * @param string $arquivo Caminho do arquivo JSON
 * @param array $dados Array com os dados a serem escritos
 * @return bool True em caso de sucesso, False em caso de erro
 */
function escreverJSON($arquivo, $dados) {
    // Garante que o diretório existe
    $dir = dirname($arquivo);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Abre o arquivo para escrita (cria se não existir)
    $file = fopen($arquivo, 'w');
    
    if ($file === false) {
        error_log("Erro ao abrir arquivo para escrita: $arquivo");
        return false;
    }
    
    // Aplica trava exclusiva (LOCK_EX) para escrita
    if (flock($file, LOCK_EX)) {
        // Codifica os dados em JSON com formatação legível
        $json = json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // Escreve no arquivo
        fwrite($file, $json);
        
        // Libera a trava
        flock($file, LOCK_UN);
        fclose($file);
        
        return true;
    } else {
        fclose($file);
        error_log("Não foi possível travar o arquivo para escrita: $arquivo");
        return false;
    }
}

/**
 * Gera um novo ID único para registros
 * @param array $dados Array de registros existentes
 * @param string $campo Nome do campo ID (padrão: 'id')
 * @return int Próximo ID disponível
 */
function gerarNovoID($dados, $campo = 'id') {
    if (empty($dados)) {
        return 1;
    }
    
    $ids = array_column($dados, $campo);
    return !empty($ids) ? max($ids) + 1 : 1;
}

/**
 * Gera um ID único alfanumérico
 * @param string $prefixo Prefixo para o ID (opcional)
 * @return string ID único
 */
function gerarIDUnico($prefixo = '') {
    return $prefixo . uniqid() . '_' . bin2hex(random_bytes(4));
}

/**
 * Busca um registro por ID
 * @param array $dados Array de registros
 * @param mixed $id ID do registro a buscar
 * @param string $campo Nome do campo ID (padrão: 'id')
 * @return array|null Registro encontrado ou null
 */
function buscarPorID($dados, $id, $campo = 'id') {
    foreach ($dados as $registro) {
        if (isset($registro[$campo]) && $registro[$campo] == $id) {
            return $registro;
        }
    }
    return null;
}

/**
 * Remove um registro por ID
 * @param array $dados Array de registros
 * @param mixed $id ID do registro a remover
 * @param string $campo Nome do campo ID (padrão: 'id')
 * @return array Array sem o registro removido
 */
function removerPorID($dados, $id, $campo = 'id') {
    return array_values(array_filter($dados, function($registro) use ($id, $campo) {
        return $registro[$campo] != $id;
    }));
}

/**
 * Atualiza um registro por ID
 * @param array $dados Array de registros
 * @param mixed $id ID do registro a atualizar
 * @param array $novosDados Novos dados do registro
 * @param string $campo Nome do campo ID (padrão: 'id')
 * @return array Array com o registro atualizado
 */
function atualizarPorID($dados, $id, $novosDados, $campo = 'id') {
    foreach ($dados as $key => $registro) {
        if (isset($registro[$campo]) && $registro[$campo] == $id) {
            $dados[$key] = array_merge($registro, $novosDados);
            break;
        }
    }
    return $dados;
}
