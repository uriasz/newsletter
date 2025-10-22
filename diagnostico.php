<?php
/**
 * Script de Diagnóstico do Sistema
 * Use este arquivo para identificar problemas de configuração no servidor
 */

// Exibir todos os erros durante diagnóstico
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico do Sistema - Newsletter</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #ec4899;
            border-bottom: 3px solid #ec4899;
            padding-bottom: 10px;
        }
        h2 {
            color: #333;
            margin-top: 30px;
            border-left: 4px solid #ec4899;
            padding-left: 10px;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .warning {
            background: #fef3c7;
            color: #92400e;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .info {
            background: #e0e7ff;
            color: #3730a3;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
        }
        .status-ok { color: #10b981; font-weight: bold; }
        .status-error { color: #ef4444; font-weight: bold; }
        .status-warning { color: #f59e0b; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico do Sistema Newsletter</h1>
        <p><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
        
        <h2>1. Informações do PHP</h2>
        <table>
            <tr>
                <th>Item</th>
                <th>Valor</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>Versão do PHP</td>
                <td><?php echo PHP_VERSION; ?></td>
                <td class="<?php echo version_compare(PHP_VERSION, '7.0.0', '>=') ? 'status-ok' : 'status-error'; ?>">
                    <?php echo version_compare(PHP_VERSION, '7.0.0', '>=') ? '✓ OK' : '✗ Versão antiga'; ?>
                </td>
            </tr>
            <tr>
                <td>Sistema Operacional</td>
                <td><?php echo PHP_OS; ?></td>
                <td class="status-ok">✓ Info</td>
            </tr>
            <tr>
                <td>Servidor Web</td>
                <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido'; ?></td>
                <td class="status-ok">✓ Info</td>
            </tr>
            <tr>
                <td>Document Root</td>
                <td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Não disponível'; ?></td>
                <td class="status-ok">✓ Info</td>
            </tr>
        </table>

        <h2>2. Extensões PHP Necessárias</h2>
        <table>
            <tr>
                <th>Extensão</th>
                <th>Status</th>
                <th>Observação</th>
            </tr>
            <?php
            $extensoes = [
                'json' => 'Essencial para manipulação de dados',
                'session' => 'Essencial para autenticação',
                'mbstring' => 'Recomendado para suporte UTF-8',
                'mysqli' => 'Necessário para integração com notícias',
                'curl' => 'Recomendado para requisições HTTP',
                'fileinfo' => 'Recomendado para verificação de arquivos'
            ];
            
            foreach ($extensoes as $ext => $desc) {
                $loaded = extension_loaded($ext);
                $class = $loaded ? 'status-ok' : 'status-warning';
                $status = $loaded ? '✓ Instalada' : '✗ Não instalada';
                echo "<tr>";
                echo "<td><strong>$ext</strong></td>";
                echo "<td class='$class'>$status</td>";
                echo "<td>$desc</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <h2>3. Permissões de Diretórios</h2>
        <?php
        $diretorios = ['data', 'includes', 'api', 'assets', 'pages'];
        echo "<table>";
        echo "<tr><th>Diretório</th><th>Existe</th><th>Legível</th><th>Gravável</th></tr>";
        
        foreach ($diretorios as $dir) {
            $existe = is_dir($dir);
            $legivel = is_readable($dir);
            $gravavel = is_writable($dir);
            
            echo "<tr>";
            echo "<td><strong>/$dir</strong></td>";
            echo "<td class='" . ($existe ? 'status-ok' : 'status-error') . "'>" . ($existe ? '✓' : '✗') . "</td>";
            echo "<td class='" . ($legivel ? 'status-ok' : 'status-error') . "'>" . ($legivel ? '✓' : '✗') . "</td>";
            echo "<td class='" . ($gravavel ? 'status-ok' : 'status-warning') . "'>" . ($gravavel ? '✓' : '✗') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>

        <h2>4. Verificação de Arquivos Essenciais</h2>
        <?php
        $arquivos = [
            'includes/config.php',
            'includes/json_handler.php',
            'includes/auth.php',
            'includes/mailer.php',
            'login.php',
            'index.php',
            '.htaccess'
        ];
        
        echo "<table>";
        echo "<tr><th>Arquivo</th><th>Existe</th><th>Legível</th></tr>";
        
        foreach ($arquivos as $arquivo) {
            $existe = file_exists($arquivo);
            $legivel = is_readable($arquivo);
            
            echo "<tr>";
            echo "<td><strong>$arquivo</strong></td>";
            echo "<td class='" . ($existe ? 'status-ok' : 'status-error') . "'>" . ($existe ? '✓' : '✗') . "</td>";
            echo "<td class='" . ($legivel ? 'status-ok' : 'status-error') . "'>" . ($legivel ? '✓' : '✗') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>

        <h2>5. Configurações PHP Importantes</h2>
        <table>
            <tr>
                <th>Configuração</th>
                <th>Valor Atual</th>
                <th>Recomendado</th>
            </tr>
            <tr>
                <td>display_errors</td>
                <td><?php echo ini_get('display_errors') ? 'On' : 'Off'; ?></td>
                <td class="status-warning">Off (em produção)</td>
            </tr>
            <tr>
                <td>error_reporting</td>
                <td><?php echo ini_get('error_reporting'); ?></td>
                <td class="status-ok">Info</td>
            </tr>
            <tr>
                <td>upload_max_filesize</td>
                <td><?php echo ini_get('upload_max_filesize'); ?></td>
                <td class="status-ok">&gt;= 10M</td>
            </tr>
            <tr>
                <td>post_max_size</td>
                <td><?php echo ini_get('post_max_size'); ?></td>
                <td class="status-ok">&gt;= 10M</td>
            </tr>
            <tr>
                <td>max_execution_time</td>
                <td><?php echo ini_get('max_execution_time'); ?>s</td>
                <td class="status-ok">&gt;= 30s</td>
            </tr>
            <tr>
                <td>session.save_path</td>
                <td><?php echo ini_get('session.save_path') ?: 'Default'; ?></td>
                <td class="status-ok">Gravável</td>
            </tr>
        </table>

        <h2>6. Teste de Inclusão de Arquivos</h2>
        <?php
        echo "<div class='info'>";
        echo "<strong>Testando includes...</strong><br>";
        
        // Testar se consegue incluir o json_handler
        if (file_exists('includes/json_handler.php')) {
            try {
                require_once 'includes/json_handler.php';
                echo "✓ includes/json_handler.php carregado com sucesso<br>";
            } catch (Exception $e) {
                echo "✗ Erro ao carregar json_handler.php: " . $e->getMessage() . "<br>";
            }
        } else {
            echo "✗ arquivo includes/json_handler.php não encontrado<br>";
        }
        
        // Testar se consegue incluir config
        if (file_exists('includes/config.php')) {
            try {
                require_once 'includes/config.php';
                echo "✓ includes/config.php carregado com sucesso<br>";
                if (defined('SITE_URL')) {
                    echo "✓ SITE_URL definida: " . SITE_URL . "<br>";
                }
            } catch (Exception $e) {
                echo "✗ Erro ao carregar config.php: " . $e->getMessage() . "<br>";
            }
        } else {
            echo "✗ arquivo includes/config.php não encontrado<br>";
        }
        
        echo "</div>";
        ?>

        <h2>7. Teste de Sessão PHP</h2>
        <?php
        echo "<div class='info'>";
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            echo "✓ Sessão PHP iniciada com sucesso<br>";
            echo "Session ID: " . session_id() . "<br>";
        } catch (Exception $e) {
            echo "✗ Erro ao iniciar sessão: " . $e->getMessage() . "<br>";
        }
        echo "</div>";
        ?>

        <h2>8. Teste de Escrita em Arquivo</h2>
        <?php
        echo "<div class='info'>";
        $testFile = 'data/teste_escrita.txt';
        try {
            if (!is_dir('data')) {
                mkdir('data', 0755, true);
            }
            
            $conteudo = "Teste de escrita - " . date('Y-m-d H:i:s');
            $resultado = file_put_contents($testFile, $conteudo);
            
            if ($resultado !== false) {
                echo "✓ Escrita bem-sucedida em $testFile<br>";
                echo "Bytes escritos: $resultado<br>";
                
                // Tentar ler de volta
                $leitura = file_get_contents($testFile);
                if ($leitura === $conteudo) {
                    echo "✓ Leitura bem-sucedida do arquivo<br>";
                }
                
                // Limpar arquivo de teste
                @unlink($testFile);
            } else {
                echo "✗ Falha ao escrever em $testFile<br>";
                echo "Verifique as permissões do diretório 'data'<br>";
            }
        } catch (Exception $e) {
            echo "✗ Erro: " . $e->getMessage() . "<br>";
        }
        echo "</div>";
        ?>

        <h2>9. Resumo e Recomendações</h2>
        <?php
        $problemas = [];
        
        // Verificar versão PHP
        if (version_compare(PHP_VERSION, '7.0.0', '<')) {
            $problemas[] = "Versão do PHP muito antiga. Atualize para PHP 7.4 ou superior.";
        }
        
        // Verificar extensões críticas
        if (!extension_loaded('json')) {
            $problemas[] = "Extensão JSON não instalada (CRÍTICO).";
        }
        if (!extension_loaded('mysqli')) {
            $problemas[] = "Extensão MySQLi não instalada (necessária para notícias).";
        }
        
        // Verificar diretório data
        if (!is_writable('data')) {
            $problemas[] = "Diretório 'data' não tem permissão de escrita. Execute: chmod 755 data";
        }
        
        if (empty($problemas)) {
            echo "<div class='success'>";
            echo "<strong>✓ Sistema pronto para uso!</strong><br>";
            echo "Não foram detectados problemas críticos.<br>";
            echo "Você pode acessar <a href='login.php'>login.php</a> para começar.";
            echo "</div>";
        } else {
            echo "<div class='error'>";
            echo "<strong>✗ Problemas detectados:</strong><br>";
            echo "<ul>";
            foreach ($problemas as $problema) {
                echo "<li>$problema</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        ?>

        <div class="warning" style="margin-top: 30px;">
            <strong>⚠️ IMPORTANTE:</strong><br>
            Após resolver os problemas, <strong>DELETE este arquivo (diagnostico.php)</strong> por segurança!
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #f9fafb; border-radius: 5px;">
            <h3>📞 Suporte</h3>
            <p>Se o erro persistir, envie as informações desta página para o administrador do sistema.</p>
            <p><strong>Passos seguintes:</strong></p>
            <ol>
                <li>Corrija os problemas identificados acima</li>
                <li>Verifique o arquivo de log do Apache: <code>/var/log/apache2/error.log</code></li>
                <li>Se necessário, renomeie ou remova o arquivo <code>.htaccess</code> temporariamente</li>
                <li>Entre em contato com o administrador do servidor se precisar instalar extensões PHP</li>
            </ol>
        </div>
    </div>
</body>
</html>
