# 📰 Guia de Integração com Sistema de Notícias

## Configuração do Banco de Dados

### 1. Configurar Conexão

Edite o arquivo `includes/db_noticias.php`:

```php
$db_host = 'localhost';      // Host do banco de dados
$db_user = 'root';           // Usuário do MySQL
$db_pass = '';               // Senha do MySQL
$db_name = 'noticias';       // Nome do banco de dados
```

### 2. Estrutura Esperada do Banco de Dados

O sistema espera as seguintes tabelas:

#### Tabela `noticias`
```sql
CREATE TABLE noticias (
    idnoticia INT PRIMARY KEY AUTO_INCREMENT,
    idtipo INT,
    data VARCHAR(10),        -- Formato: dd/mm/yyyy
    hora VARCHAR(5),         -- Formato: HH:mm
    assunto VARCHAR(255),
    descricao TEXT,
    foto VARCHAR(255),
    FOREIGN KEY (idtipo) REFERENCES tiponot(idtipo)
);
```

#### Tabela `tiponot`
```sql
CREATE TABLE tiponot (
    idtipo INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(100)
);
```

## Como Usar

### No Dashboard

1. As notícias mais recentes são exibidas automaticamente no dashboard
2. Mostra as 6 notícias mais recentes do banco de dados
3. Cards com glassmorphism mostram imagem, data e assunto

### Ao Criar Campanhas

1. **Digite uma palavra-chave** no campo de busca
   - Ex: "Programa de Acelera", "Educação", "Saúde"

2. **Clique em "Buscar Notícias"**
   - Sistema busca no banco de dados
   - Mostra até 10 notícias relacionadas

3. **Selecione as notícias desejadas**
   - Marque as checkboxes das notícias que deseja incluir
   - Pode selecionar múltiplas notícias

4. **Escreva o conteúdo principal** no campo HTML

5. **Envie a campanha**
   - As notícias selecionadas serão adicionadas automaticamente ao final do e-mail
   - Cada notícia terá imagem, data, tipo e link

## Formato das Notícias no E-mail

As notícias selecionadas são adicionadas com este layout:

```html
<div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa;">
    <h2 style="color: #6366f1; text-align: center;">📰 Notícias em Destaque</h2>
    <div style="display: grid; gap: 20px;">
        <!-- Para cada notícia -->
        <div style="background: white; border-radius: 8px;">
            <a href="[LINK_DA_NOTICIA]" target="_blank">
                <img src="[IMAGEM]" alt="[ASSUNTO]">
                <div style="padding: 15px;">
                    <div style="color: #6366f1;">
                        DATA: [DATA] | TIPO: [TIPO]
                    </div>
                    <h3>[ASSUNTO]</h3>
                </div>
            </a>
        </div>
    </div>
</div>
```

## API de Notícias

### Endpoint

```
GET /api/noticias.php
```

### Parâmetros

- `palavra_chave` (string): Termo para buscar nas notícias
- `limite` (int, opcional): Número máximo de resultados (padrão: 6)

### Exemplo de Requisição

```javascript
fetch('/api/noticias.php?palavra_chave=educação&limite=10')
    .then(res => res.json())
    .then(data => console.log(data));
```

### Resposta

```json
{
    "success": true,
    "message": "10 notícias encontradas",
    "data": [
        {
            "id": 1,
            "tipo": "Notícia",
            "data": "22/10/2025",
            "assunto": "Programa de Aceleração inicia nova turma",
            "descricao": "Descrição completa...",
            "foto": "foto123.jpg",
            "imagem_url": "/noticias/arquivo/foto123.jpg",
            "link": "https://www.pjf.mg.gov.br/noticias/view.php?modo=link2&idnoticia2=1"
        }
    ]
}
```

## Busca de Notícias

### Critérios de Busca

A busca procura a palavra-chave em:
- Campo `assunto` da notícia
- Campo `descricao` da notícia

### Ordenação

As notícias são ordenadas por:
1. Data e hora mais recentes primeiro
2. ID da notícia (mais recente primeiro)

### Limite

Por padrão, busca até 6 notícias. Pode ser ajustado via parâmetro `limite`.

## Imagens das Notícias

### Caminho das Imagens

- **Com foto:** `/noticias/arquivo/[nome_do_arquivo]`
- **Sem foto:** `/img2/noticias/recente_sem_foto.png` (imagem padrão)

### Requisitos

- As imagens devem estar acessíveis via web
- Caminho relativo ou absoluto funcional
- Imagem padrão deve existir no caminho especificado

## Personalização

### Alterar Palavra-chave Padrão

No dashboard (`index.php`), a busca usa palavra-chave vazia para mostrar todas:

```javascript
fetch('api/noticias.php?palavra_chave=&limite=6')
```

Para definir uma palavra-chave padrão:

```javascript
fetch('api/noticias.php?palavra_chave=Programa de Acelera&limite=6')
```

### Alterar Quantidade de Notícias

No dashboard:
```javascript
// Altere o parâmetro limite
fetch('api/noticias.php?palavra_chave=&limite=12')
```

Na página de campanhas:
```javascript
// Em campanhas.js, função buscarNoticias()
const response = await fetch(`../api/noticias.php?palavra_chave=${...}&limite=20`);
```

### Customizar Layout das Notícias

Edite a função `gerarHTMLNoticias()` em `assets/js/campanhas.js`:

```javascript
function gerarHTMLNoticias(noticiasSelecionadas) {
    // Personalize o HTML aqui
    let html = `
    <div style="seu-estilo-personalizado">
        <h2>Seu Título</h2>
        ...
    </div>
    `;
    return html;
}
```

## Solução de Problemas

### "Erro ao consultar notícias"

**Causa:** Problema na conexão com o banco de dados ou tabelas inexistentes.

**Solução:**
1. Verifique `includes/db_noticias.php`
2. Confirme que o banco existe e está acessível
3. Verifique se as tabelas `noticias` e `tiponot` existem

### "Nenhuma notícia encontrada"

**Causa:** Palavra-chave não retorna resultados ou banco vazio.

**Solução:**
1. Tente uma palavra-chave mais genérica
2. Verifique se há notícias no banco de dados
3. Remova a palavra-chave para buscar todas

### Imagens não aparecem

**Causa:** Caminho das imagens incorreto.

**Solução:**
1. Verifique o caminho em `api/noticias.php`:
   ```php
   $imagem = "/noticias/arquivo/$foto";
   ```
2. Ajuste para o caminho correto do seu servidor
3. Use caminhos absolutos se necessário

### Caracteres especiais com problema

**Causa:** Encoding incorreto.

**Solução:**
1. Verifique charset em `includes/db_noticias.php`:
   ```php
   mysqli_set_charset($conn, "utf8");
   ```
2. Confirme que o banco usa UTF-8

## Segurança

### SQL Injection

⚠️ **IMPORTANTE:** O código atual usa concatenação direta de strings na query SQL.

**Para produção, use prepared statements:**

```php
$stmt = $conn->prepare("
    SELECT * FROM noticias n
    JOIN tiponot t ON n.idtipo = t.idtipo
    WHERE n.assunto LIKE ? OR n.descricao LIKE ?
    ORDER BY STR_TO_DATE(CONCAT(n.data, ' ', n.hora), '%d/%m/%Y %H:%i') DESC
    LIMIT ?
");

$searchTerm = "%$palavraChave%";
$stmt->bind_param("ssi", $searchTerm, $searchTerm, $numero_noticias);
$stmt->execute();
$resultado = $stmt->get_result();
```

## Exemplo Completo de Uso

### 1. Configurar Banco
```sql
-- Criar banco
CREATE DATABASE noticias;
USE noticias;

-- Criar tabelas
CREATE TABLE tiponot (
    idtipo INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(100)
);

CREATE TABLE noticias (
    idnoticia INT PRIMARY KEY AUTO_INCREMENT,
    idtipo INT,
    data VARCHAR(10),
    hora VARCHAR(5),
    assunto VARCHAR(255),
    descricao TEXT,
    foto VARCHAR(255),
    FOREIGN KEY (idtipo) REFERENCES tiponot(idtipo)
);

-- Inserir dados de exemplo
INSERT INTO tiponot (tipo) VALUES ('Notícia'), ('Comunicado'), ('Evento');

INSERT INTO noticias (idtipo, data, hora, assunto, descricao, foto) VALUES
(1, '22/10/2025', '14:30', 'Programa de Aceleração inicia nova turma', 'Descrição...', 'foto1.jpg'),
(1, '21/10/2025', '10:00', 'Nova política de educação aprovada', 'Descrição...', ''),
(2, '20/10/2025', '09:15', 'Comunicado importante aos cidadãos', 'Descrição...', 'foto2.jpg');
```

### 2. Configurar Conexão
```php
// includes/db_noticias.php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'sua_senha';
$db_name = 'noticias';
```

### 3. Usar no Sistema
1. Acesse o dashboard - notícias aparecem automaticamente
2. Vá para Campanhas > Nova Campanha
3. Digite "Programa" no campo de busca
4. Clique em "Buscar Notícias"
5. Selecione as notícias desejadas
6. Escreva o conteúdo da campanha
7. Envie!

---

**Data:** Outubro 2025  
**Versão:** 1.0.0
