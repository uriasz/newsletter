<?php
/**
 * Funções de Autenticação e Autorização
 */

require_once __DIR__ . '/config.php';

/**
 * Verifica se o usuário está logado
 * @return bool
 */
function estaLogado() {
    return isset($_SESSION['usuario_id']) && isset($_SESSION['usuario_email']);
}

/**
 * Realiza o login do usuário
 * @param string $email
 * @param string $senha
 * @return bool|string True em caso de sucesso, mensagem de erro caso contrário
 */
function fazerLogin($email, $senha) {
    $usuarios = lerJSON(USUARIOS_JSON);
    
    foreach ($usuarios as $usuario) {
        if ($usuario['email'] === $email) {
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                return true;
            } else {
                return 'Senha incorreta';
            }
        }
    }
    
    return 'Usuário não encontrado';
}

/**
 * Realiza o logout do usuário
 */
function fazerLogout() {
    session_unset();
    session_destroy();
}

/**
 * Redireciona para a página de login se não estiver autenticado
 */
function requererLogin() {
    if (!estaLogado()) {
        header('Location: ' . SITE_URL . '/login.php');
        exit;
    }
}

/**
 * Cria um novo usuário
 * @param string $nome
 * @param string $email
 * @param string $senha
 * @return bool|string True em caso de sucesso, mensagem de erro caso contrário
 */
function criarUsuario($nome, $email, $senha) {
    $usuarios = lerJSON(USUARIOS_JSON);
    
    // Verifica se o e-mail já existe
    foreach ($usuarios as $usuario) {
        if ($usuario['email'] === $email) {
            return 'Este e-mail já está cadastrado';
        }
    }
    
    // Cria o novo usuário
    $novoUsuario = [
        'id' => gerarNovoID($usuarios),
        'nome' => $nome,
        'email' => $email,
        'senha' => password_hash($senha, PASSWORD_HASH_ALGO)
    ];
    
    $usuarios[] = $novoUsuario;
    
    if (escreverJSON(USUARIOS_JSON, $usuarios)) {
        return true;
    } else {
        return 'Erro ao salvar usuário';
    }
}
