# 🚀 Guia de Instalação Rápida

## Passo 1: Configurar o Ambiente

### Opção A: Usar XAMPP/WAMP (Windows)
1. Instale o XAMPP: https://www.apachefriends.org/
2. Copie a pasta `newsletter-system` para `C:\xampp\htdocs\`
3. Inicie o Apache no painel do XAMPP
4. Acesse: http://localhost/newsletter-system/

### Opção B: Usar MAMP (Mac)
1. Instale o MAMP: https://www.mamp.info/
2. Copie a pasta para `/Applications/MAMP/htdocs/`
3. Inicie o servidor
4. Acesse: http://localhost:8888/newsletter-system/

### Opção C: Usar PHP Built-in Server (Linux/Mac/Windows)
```bash
cd newsletter-system
php -S localhost:8000
```
Acesse: http://localhost:8000/

## Passo 2: Configurar o Sistema

### 2.1 Editar includes/config.php

```php
// Altere esta linha para a URL correta do seu servidor
define('SITE_URL', 'http://localhost/newsletter-system');

// Configure seu e-mail
define('MAIL_FROM', 'newsletter@seudominio.com');
define('MAIL_FROM_NAME', 'Seu Nome ou Empresa');
```

### 2.2 Verificar Permissões (Linux/Mac)

```bash
chmod -R 775 data/
```

## Passo 3: Primeiro Acesso

1. Acesse a URL configurada
2. Faça login com:
   - **E-mail:** admin@example.com
   - **Senha:** password

## Passo 4: Criar Sua Primeira Lista

1. Clique em **Listas** no menu
2. Clique em **+ Adicionar Lista**
3. Digite um nome (ex: "Clientes")
4. Clique em **Salvar**

## Passo 5: Adicionar Assinantes

1. Clique em **Assinantes** no menu
2. Clique em **+ Adicionar Assinante**
3. Preencha:
   - Nome: "João Silva"
   - E-mail: "seu-email@gmail.com" (use seu e-mail real para teste)
   - Selecione a lista criada
4. Clique em **Salvar**

## Passo 6: Criar e Enviar Campanha

1. Clique em **Campanhas** no menu
2. Clique em **+ Nova Campanha**
3. Preencha:
   - **Assunto:** "Teste de Newsletter"
   - Selecione a lista
   - **Conteúdo HTML:** Use o exemplo do arquivo `template-exemplo.html`
4. Escolha:
   - **Salvar Rascunho** (para testar primeiro) ou
   - **Enviar Agora** (enviará imediatamente)

## Passo 7: Verificar Relatórios

1. Abra o e-mail enviado
2. Volte ao sistema
3. Clique em **Relatórios** > Selecione a campanha
4. Você verá que o e-mail foi aberto!

---

## ⚠️ Problemas Comuns

### "E-mail não está sendo enviado"

**Solução para Desenvolvimento Local:**

#### Windows (XAMPP):
1. Instale o **Fake Sendmail**:
   - Já vem com XAMPP
2. Edite `C:\xampp\php\php.ini`:
   ```ini
   [mail function]
   SMTP=localhost
   smtp_port=25
   sendmail_path = "C:\xampp\sendmail\sendmail.exe -t"
   ```
3. Edite `C:\xampp\sendmail\sendmail.ini`:
   ```ini
   smtp_server=smtp.gmail.com
   smtp_port=587
   auth_username=seu-email@gmail.com
   auth_password=sua-senha-app
   ```

#### Linux/Mac:
Use **MailHog** para capturar e-mails de teste:
```bash
# Instalar MailHog
brew install mailhog  # Mac
# ou baixe em: https://github.com/mailhog/MailHog

# Iniciar
mailhog

# Acesse: http://localhost:8025
```

Configure PHP para usar MailHog:
```ini
sendmail_path = "/usr/local/bin/MailHog sendmail"
```

### "Erro de permissão ao salvar"

```bash
# Linux/Mac
sudo chmod -R 775 data/
sudo chown -R www-data:www-data data/

# Ou dê permissão total (apenas para desenvolvimento)
chmod -R 777 data/
```

### "Rastreamento não funciona"

1. Verifique se `SITE_URL` em `includes/config.php` está correto
2. Teste acessando: `http://localhost/newsletter-system/tracker.php?cid=teste&sid=1`
3. Deve retornar uma imagem (aparecerá em branco)

---

## 🎯 Próximos Passos

1. **Alterar a senha padrão:**
   - Abra `data/usuarios.json`
   - Gere nova senha:
     ```php
     <?php
     echo password_hash('sua-nova-senha', PASSWORD_DEFAULT);
     ?>
     ```
   - Substitua o valor do campo "senha"

2. **Importar assinantes em massa:**
   - Edite diretamente `data/assinantes.json`
   - Siga a estrutura existente

3. **Personalizar templates:**
   - Use o arquivo `template-exemplo.html` como base
   - Copie o HTML para a campanha

4. **Configurar SMTP profissional:**
   - Instale PHPMailer: `composer require phpmailer/phpmailer`
   - Modifique `includes/mailer.php`

---

## 📞 Suporte

Leia o `README.md` completo para:
- Estrutura detalhada do projeto
- Explicação completa do rastreamento
- Migração para banco de dados
- Solução de problemas avançados

**Boa sorte com seu sistema de newsletter! 📧**
