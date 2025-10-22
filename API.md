# 📡 Documentação da API

Este documento descreve todos os endpoints da API do Sistema de Newsletter.

## 🔐 Autenticação

Todas as requisições (exceto login) requerem uma sessão ativa. O sistema usa sessões PHP padrão.

## Endpoints Disponíveis

---

### 1. Login

**POST** `/api/login.php`

Autentica um usuário no sistema.

**Request Body:**
```
email=admin@example.com&senha=password
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Login realizado com sucesso"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Senha incorreta"
}
```

**Exemplo (JavaScript):**
```javascript
const formData = new FormData();
formData.append('email', 'admin@example.com');
formData.append('senha', 'password');

fetch('/api/login.php', {
  method: 'POST',
  body: formData
})
.then(res => res.json())
.then(data => console.log(data));
```

---

### 2. Assinantes

#### Listar Todos os Assinantes

**GET** `/api/assinantes.php`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nome": "João Silva",
      "email": "joao@email.com",
      "listas": [1, 2],
      "data_cadastro": "2025-10-22 10:30:00"
    }
  ]
}
```

#### Adicionar Assinante

**POST** `/api/assinantes.php`

**Request Body:**
```
nome=Maria Santos&email=maria@email.com&listas=[1,2]
```

**Response:**
```json
{
  "success": true,
  "message": "Assinante adicionado com sucesso",
  "data": {
    "id": 2,
    "nome": "Maria Santos",
    "email": "maria@email.com",
    "listas": [1, 2]
  }
}
```

#### Atualizar Assinante

**PUT** `/api/assinantes.php`

**Request Body:**
```
id=1&nome=João Silva Atualizado&email=joao@email.com&listas=[1]
```

**Response:**
```json
{
  "success": true,
  "message": "Assinante atualizado com sucesso"
}
```

#### Excluir Assinante

**DELETE** `/api/assinantes.php`

**Request Body:**
```
id=1
```

**Response:**
```json
{
  "success": true,
  "message": "Assinante removido com sucesso"
}
```

**Exemplo Completo (JavaScript):**
```javascript
// Listar
fetch('/api/assinantes.php')
  .then(res => res.json())
  .then(data => console.log(data.data));

// Adicionar
const formData = new FormData();
formData.append('nome', 'Carlos Silva');
formData.append('email', 'carlos@email.com');
formData.append('listas', JSON.stringify([1, 2]));

fetch('/api/assinantes.php', {
  method: 'POST',
  body: formData
})
.then(res => res.json())
.then(data => console.log(data));

// Atualizar
const params = new URLSearchParams();
params.append('id', 2);
params.append('nome', 'Carlos Silva Atualizado');
params.append('email', 'carlos@email.com');
params.append('listas', JSON.stringify([1]));

fetch('/api/assinantes.php', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  body: params.toString()
})
.then(res => res.json())
.then(data => console.log(data));

// Excluir
fetch('/api/assinantes.php', {
  method: 'DELETE',
  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
  body: 'id=2'
})
.then(res => res.json())
.then(data => console.log(data));
```

---

### 3. Listas

#### Listar Todas as Listas

**GET** `/api/listas.php`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nome": "Clientes",
      "descricao": "Lista de clientes ativos",
      "data_criacao": "2025-10-22 09:00:00",
      "total_assinantes": 5
    }
  ]
}
```

#### Adicionar Lista

**POST** `/api/listas.php`

**Request Body:**
```
nome=Promoções&descricao=Lista para promoções especiais
```

#### Atualizar Lista

**PUT** `/api/listas.php`

**Request Body:**
```
id=1&nome=Clientes VIP&descricao=Clientes especiais
```

#### Excluir Lista

**DELETE** `/api/listas.php`

**Request Body:**
```
id=1
```

---

### 4. Campanhas

#### Listar Todas as Campanhas

**GET** `/api/campanhas.php`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": "camp_abc123",
      "assunto": "Newsletter Semanal",
      "conteudo_html": "<html>...</html>",
      "listas": [1, 2],
      "status": "enviada",
      "data_criacao": "2025-10-22 10:00:00",
      "data_envio": "2025-10-22 11:00:00"
    }
  ]
}
```

#### Criar Campanha (Rascunho)

**POST** `/api/campanhas.php`

**Request Body:**
```
assunto=Novidades da Semana
&conteudoHtml=<html>...</html>
&listas=[1,2]
&enviar=false
```

#### Criar e Enviar Campanha Imediatamente

**POST** `/api/campanhas.php`

**Request Body:**
```
assunto=Promoção Especial
&conteudoHtml=<html>...</html>
&listas=[1,2]
&enviar=true
```

**Response:**
```json
{
  "success": true,
  "message": "Campanha criada e enviada com sucesso! Enviados: 10",
  "data": {
    "id": "camp_xyz789",
    "assunto": "Promoção Especial",
    "status": "enviada"
  }
}
```

#### Atualizar Campanha

**PUT** `/api/campanhas.php`

**Request Body:**
```
id=camp_abc123&assunto=Novo Assunto&conteudoHtml=<html>...</html>&listas=[1]
```

#### Excluir Campanha

**DELETE** `/api/campanhas.php`

**Request Body:**
```
id=camp_abc123
```

**Exemplo (JavaScript):**
```javascript
// Criar e enviar campanha
const formData = new FormData();
formData.append('assunto', 'Teste de Campanha');
formData.append('conteudoHtml', '<h1>Olá, [NOME]!</h1>');
formData.append('listas', JSON.stringify([1]));
formData.append('enviar', 'true');

fetch('/api/campanhas.php', {
  method: 'POST',
  body: formData
})
.then(res => res.json())
.then(data => {
  if (data.success) {
    console.log('Campanha enviada!');
    console.log(data.message); // "Enviados: 10"
  }
});
```

---

### 5. Dashboard

**GET** `/api/dashboard.php`

Retorna estatísticas gerais do sistema.

**Response:**
```json
{
  "success": true,
  "data": {
    "total_assinantes": 25,
    "total_listas": 3,
    "total_campanhas": 5,
    "taxa_abertura_media": 45.8
  }
}
```

---

### 6. Relatórios

**GET** `/api/relatorios.php?campanha_id=camp_abc123`

Retorna dados detalhados de uma campanha específica.

**Response:**
```json
{
  "success": true,
  "data": {
    "total_envios": 10,
    "total_aberturas": 15,
    "aberturas_unicas": 8,
    "taxa_abertura": 80.0,
    "aberturas_detalhadas": [
      {
        "nome": "João Silva",
        "email": "joao@email.com",
        "data_abertura": "2025-10-22 11:05:30",
        "total_aberturas": 2
      }
    ]
  }
}
```

**Exemplo (JavaScript):**
```javascript
fetch('/api/relatorios.php?campanha_id=camp_abc123')
  .then(res => res.json())
  .then(data => {
    console.log('Taxa de abertura:', data.data.taxa_abertura + '%');
    console.log('Quem abriu:', data.data.aberturas_detalhadas);
  });
```

---

### 7. Tracker (Pixel de Rastreamento)

**GET** `/tracker.php?cid=camp_abc123&sid=1`

Registra a abertura de um e-mail e retorna um pixel transparente.

**Parâmetros:**
- `cid`: ID da campanha
- `sid`: ID do assinante

**Response:**
Retorna uma imagem GIF de 1x1 pixel (binário).

**Nota:** Este endpoint é chamado automaticamente quando o e-mail é aberto. Não deve ser chamado manualmente pela aplicação.

---

## 🔒 Códigos de Erro Comuns

```json
{
  "success": false,
  "message": "Preencha todos os campos"
}
```

```json
{
  "success": false,
  "message": "Este e-mail já está cadastrado"
}
```

```json
{
  "success": false,
  "message": "Método não permitido"
}
```

```json
{
  "success": false,
  "message": "ID não fornecido"
}
```

---

## 📝 Notas Importantes

### Sessões PHP
O sistema usa sessões PHP para autenticação. Certifique-se de:
1. Fazer login primeiro (`/api/login.php`)
2. Manter os cookies de sessão nas requisições subsequentes

### Content-Type
- **GET/POST com FormData:** Não especifique Content-Type (será definido automaticamente)
- **PUT/DELETE:** Use `Content-Type: application/x-www-form-urlencoded`

### Arrays em JSON
Ao enviar arrays (como listas), converta para JSON:
```javascript
formData.append('listas', JSON.stringify([1, 2, 3]));
```

### Placeholder no HTML
Use `[NOME]` no conteúdo HTML para inserir o nome do assinante:
```html
<h1>Olá, [NOME]!</h1>
```

---

## 🧪 Testando a API

### Usando cURL (Linux/Mac)

```bash
# Login
curl -X POST http://localhost/newsletter-system/api/login.php \
  -d "email=admin@example.com&senha=password" \
  -c cookies.txt

# Listar assinantes (usando cookies da sessão)
curl -X GET http://localhost/newsletter-system/api/assinantes.php \
  -b cookies.txt

# Adicionar assinante
curl -X POST http://localhost/newsletter-system/api/assinantes.php \
  -d "nome=Teste&email=teste@email.com&listas=[1]" \
  -b cookies.txt
```

### Usando Postman

1. Faça login em `/api/login.php` (POST)
2. Postman salvará os cookies automaticamente
3. Use os outros endpoints normalmente

---

## 💡 Exemplo de Integração Completa

```javascript
class NewsletterAPI {
  constructor(baseURL) {
    this.baseURL = baseURL;
  }

  async login(email, senha) {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('senha', senha);
    
    const response = await fetch(`${this.baseURL}/api/login.php`, {
      method: 'POST',
      body: formData
    });
    
    return await response.json();
  }

  async listarAssinantes() {
    const response = await fetch(`${this.baseURL}/api/assinantes.php`);
    return await response.json();
  }

  async adicionarAssinante(nome, email, listas) {
    const formData = new FormData();
    formData.append('nome', nome);
    formData.append('email', email);
    formData.append('listas', JSON.stringify(listas));
    
    const response = await fetch(`${this.baseURL}/api/assinantes.php`, {
      method: 'POST',
      body: formData
    });
    
    return await response.json();
  }

  async enviarCampanha(assunto, conteudoHtml, listas) {
    const formData = new FormData();
    formData.append('assunto', assunto);
    formData.append('conteudoHtml', conteudoHtml);
    formData.append('listas', JSON.stringify(listas));
    formData.append('enviar', 'true');
    
    const response = await fetch(`${this.baseURL}/api/campanhas.php`, {
      method: 'POST',
      body: formData
    });
    
    return await response.json();
  }

  async obterRelatorio(campanhaId) {
    const response = await fetch(
      `${this.baseURL}/api/relatorios.php?campanha_id=${campanhaId}`
    );
    return await response.json();
  }
}

// Uso
const api = new NewsletterAPI('http://localhost/newsletter-system');

async function exemplo() {
  // Login
  await api.login('admin@example.com', 'password');
  
  // Adicionar assinante
  const novoAssinante = await api.adicionarAssinante(
    'João Silva',
    'joao@email.com',
    [1, 2]
  );
  console.log('Assinante criado:', novoAssinante);
  
  // Enviar campanha
  const campanha = await api.enviarCampanha(
    'Teste de Newsletter',
    '<h1>Olá, [NOME]!</h1>',
    [1]
  );
  console.log('Campanha enviada:', campanha);
  
  // Ver relatório
  const relatorio = await api.obterRelatorio(campanha.data.id);
  console.log('Relatório:', relatorio);
}

exemplo();
```

---

**Documentação criada em:** Outubro 2025  
**Versão da API:** 1.0.0
