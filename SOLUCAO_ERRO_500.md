# 🚨 Guia de Solução de Erros - Servidor da Prefeitura

## Erro 500 - Internal Server Error

Este erro geralmente ocorre por incompatibilidade do `.htaccess` ou configurações do PHP.

## ✅ PASSOS PARA RESOLVER

### 1. Execute o Diagnóstico
Acesse: `http://seu-servidor/newsletter/diagnostico.php`

Este arquivo verificará:
- Versão do PHP
- Extensões instaladas
- Permissões de diretórios
- Configurações críticas

### 2. Soluções Mais Comuns

#### Opção A: Problema no .htaccess
O `.htaccess` foi simplificado, mas se ainda der erro:

```bash
# Renomeie temporariamente o .htaccess
mv .htaccess .htaccess.old
```

Depois teste acessar: `http://seu-servidor/newsletter/login.php`

**Se funcionar sem o .htaccess:**
- O servidor não suporta todas as diretivas
- Use o `.htaccess` mínimo (veja abaixo)

#### Opção B: Permissões de Arquivo
```bash
# No servidor Linux, execute:
chmod 755 /caminho/para/newsletter
chmod 755 /caminho/para/newsletter/data
chmod 644 /caminho/para/newsletter/*.php
chmod 644 /caminho/para/newsletter/data/*.json
```

#### Opção C: Criar diretório data/
```bash
mkdir -p data
chmod 755 data
```

#### Opção D: Verificar logs do Apache
```bash
# Ver últimas linhas do log de erro
tail -f /var/log/apache2/error.log
# ou
tail -f /var/log/httpd/error_log
```

### 3. .htaccess Mínimo (se o atual não funcionar)

Crie um arquivo `.htaccess` SUPER SIMPLES:

```apache
# Desabilitar listagem
Options -Indexes

# Proteger arquivos JSON (se mod_rewrite disponível)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule \.json$ - [F,L]
</IfModule>
```

### 4. Sem .htaccess (última opção)

Se nada funcionar, **delete o .htaccess** e proteja os arquivos via PHP:

**Adicione no topo de cada arquivo em `data/`:**
```php
<?php
header('HTTP/1.0 403 Forbidden');
exit('Acesso negado');
?>
```

Mas isso não é ideal pois os arquivos são JSON puros.

### 5. Configuração do PHP

Se você tem acesso ao `php.ini`, verifique:

```ini
display_errors = Off  ; em produção
log_errors = On
error_log = /var/log/php_errors.log
session.save_path = /tmp  ; ou outro diretório gravável
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

### 6. Extensões PHP Necessárias

Verifique se estão instaladas:
```bash
php -m | grep -E 'json|session|mysqli|mbstring'
```

Se faltar alguma:
```bash
# Ubuntu/Debian
sudo apt-get install php-json php-mysqli php-mbstring

# CentOS/RHEL
sudo yum install php-json php-mysqli php-mbstring
```

### 7. Testando Passo a Passo

**Teste 1: PHP está funcionando?**
Crie `teste.php`:
```php
<?php
phpinfo();
?>
```
Acesse: `http://seu-servidor/newsletter/teste.php`

**Teste 2: Arquivos estão acessíveis?**
```php
<?php
echo "OK - PHP funcionando!";
echo "<br>Diretório atual: " . __DIR__;
?>
```

**Teste 3: Sessões funcionam?**
```php
<?php
session_start();
$_SESSION['teste'] = 'funcionando';
echo "Sessão OK: " . $_SESSION['teste'];
?>
```

## 🔧 Configuração Específica para Servidor da Prefeitura

### Se o servidor usar mod_security:
```apache
# Adicionar ao .htaccess
<IfModule mod_security.c>
    SecRuleEngine Off
</IfModule>
```

### Se tiver suPHP:
```apache
# Usar permissões diferentes
# Arquivos: 644
# Diretórios: 755
```

### Caminho completo no servidor:
Atualize o arquivo `includes/config.php`:

```php
<?php
// Configuração para servidor da prefeitura
define('SITE_URL', 'https://www.pjf.mg.gov.br/newsletter');
define('BASE_PATH', '/var/www/html/newsletter'); // ajuste conforme necessário

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Segurança
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // se usar HTTPS
ini_set('session.use_only_cookies', 1);
?>
```

## 📋 Checklist Final

- [ ] Executei `diagnostico.php` e verifiquei os resultados
- [ ] PHP 7.0+ está instalado
- [ ] Extensões json, session, mysqli estão ativas
- [ ] Diretório `data/` existe e tem permissão 755
- [ ] Arquivo `.htaccess` é compatível (ou foi removido)
- [ ] `includes/config.php` tem o SITE_URL correto
- [ ] Verifiquei os logs do Apache
- [ ] Testei `login.php` diretamente

## 🆘 Ainda com Erro?

### Colete estas informações:
1. Conteúdo do log de erro do Apache (últimas 20 linhas)
2. Resultado completo de `diagnostico.php`
3. Versão do PHP: `php -v`
4. Versão do Apache: `apache2 -v` ou `httpd -v`
5. Sistema operacional do servidor

### Soluções Alternativas:

**Opção 1: Sem .htaccess**
- Delete o `.htaccess`
- Confie apenas na segurança do PHP
- Proteja o diretório `data/` via configuração do Apache (se tiver acesso)

**Opção 2: Configuração Apache Virtual Host**
Se você tem acesso root, adicione no VirtualHost:

```apache
<Directory "/var/www/html/newsletter/data">
    Require all denied
</Directory>

<Directory "/var/www/html/newsletter/includes">
    Require all denied
</Directory>
```

**Opção 3: Mover arquivos JSON para fora do DocumentRoot**
Mais seguro, mas requer alterar caminhos no código:

```bash
# Mover data para fora
mv /var/www/html/newsletter/data /var/newsletter-data
chmod 755 /var/newsletter-data
```

Depois atualizar em `includes/config.php`:
```php
define('DATA_PATH', '/var/newsletter-data');
```

## 📞 Contato com Administrador

Se precisar falar com o administrador do servidor (webmaster@pjf.mg.gov.br), informe:

> "Preciso instalar um sistema PHP de newsletter. Atualmente está retornando erro 500.
> Necessidades:
> - PHP 7.4+
> - Extensões: json, session, mysqli, mbstring
> - Permissão de escrita no diretório 'data'
> - Suporte a .htaccess ou configuração manual no Apache para proteger arquivos JSON
> 
> Documentação completa em: /newsletter/diagnostico.php"

---

**⚠️ IMPORTANTE: Após resolver o problema, DELETE o arquivo `diagnostico.php` por segurança!**
