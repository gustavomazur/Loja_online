# Sistema de Múltiplas Imagens por Cor - Loja Oline

## Como Funciona

O sistema agora permite cadastrar produtos com:
1. **Uma imagem principal** (obrigatória) - exibida na loja
2. **Múltiplas imagens por cor** (opcional) - para mostrar variações

## Como Cadastrar um Produto

### 1. Dados Básicos
- **Nome do Produto**: Nome completo do produto
- **Preço**: Valor em reais
- **Categoria**: Tipo do produto (ex: camiseta, perfume, tênis)
- **Imagem Principal**: Uma imagem que será exibida na loja (obrigatória)

### 2. Cores e Imagens por Cor
- **Cores**: Digite as cores separadas por vírgula (ex: rosa, preto, branco)
- **Imagens por Cor**: Para cada cor digitada, aparecerá um campo de upload
- **Múltiplas Imagens**: Em cada campo, você pode selecionar várias imagens (Ctrl+Click)

## Exemplo Prático

**Produto**: Camiseta Básica
**Preço**: R$ 29,90
**Categoria**: camiseta
**Imagem Principal**: foto_frontal_camiseta.jpg
**Cores**: rosa, preto, branco
**Imagens por Cor**:
- Rosa: 3 fotos (frente, costas, detalhe)
- Preto: 3 fotos (frente, costas, detalhe)
- Branco: 2 fotos (frente, costas)

## Como Visualizar as Imagens

1. No painel, na tabela de produtos, veja a coluna "Imagens por Cor"
2. Clique em "Ver X imagens" para ver todas as imagens do produto
3. A página mostrará:
   - Imagem principal
   - Imagens organizadas por cor
   - Nome dos arquivos

## Estrutura do Banco de Dados

### Tabela `produtos` (estrutura atual)
```sql
CREATE TABLE `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem_url` varchar(255) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `estoque` int NOT NULL DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cor` varchar(255) DEFAULT NULL,
  `imagens` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

### Campos Utilizados:
- `id`: ID único do produto
- `nome`: Nome do produto
- `preco`: Preço
- `categoria`: Categoria
- `imagem_url`: Caminho da imagem principal
- `cor`: Lista de cores (texto)
- `imagens`: JSON com múltiplas imagens por cor

### Formato do Campo `imagens` (JSON):
```json
{
  "rosa": [
    "imagens/prod_rosa_123456.jpg",
    "imagens/prod_rosa_789012.jpg",
    "imagens/prod_rosa_345678.jpg"
  ],
  "preto": [
    "imagens/prod_preto_901234.jpg",
    "imagens/prod_preto_567890.jpg"
  ],
  "branco": [
    "imagens/prod_branco_123789.jpg"
  ]
}
```

## Funcionalidades

### ✅ Implementado
- [x] Upload de imagem principal obrigatória
- [x] Upload de múltiplas imagens por cor
- [x] Visualização organizada por cor
- [x] Remoção automática de imagens ao deletar produto
- [x] Interface amigável no painel
- [x] Validação de tipos de imagem
- [x] Nomes únicos para evitar conflitos
- [x] Compatível com estrutura atual do banco

### 🔄 Próximas Melhorias
- [ ] Galeria de imagens na loja para clientes
- [ ] Seleção de cor na página do produto
- [ ] Zoom nas imagens
- [ ] Upload em lote
- [ ] Redimensionamento automático

## Dicas de Uso

1. **Organize as cores**: Use nomes simples e consistentes
2. **Qualidade das imagens**: Use imagens de boa qualidade, mas não muito pesadas
3. **Nomes dos arquivos**: O sistema gera nomes únicos automaticamente
4. **Múltiplas imagens**: Use Ctrl+Click para selecionar várias imagens de uma vez
5. **Cores sem imagens**: Se não tiver imagens para uma cor, deixe o campo em branco

## Solução de Problemas

### Erro no Upload
- Verifique se a pasta `imagens/` tem permissão de escrita
- Confirme se o arquivo é uma imagem válida (jpg, png, gif, webp)
- Verifique o tamanho do arquivo (máximo 5MB)

### Imagens não aparecem
- Verifique se os caminhos estão corretos
- Confirme se as imagens foram salvas na pasta `imagens/`
- Verifique as permissões dos arquivos

### Produto não salva
- Verifique se todos os campos obrigatórios estão preenchidos
- Confirme se a imagem principal foi selecionada
- Verifique se o banco de dados está funcionando

## Comandos SQL Úteis

```sql
-- Ver todos os produtos
SELECT * FROM produtos;

-- Ver produtos com imagens por cor
SELECT id, nome, cor, imagens FROM produtos WHERE imagens IS NOT NULL;

-- Ver estrutura da tabela
DESCRIBE produtos;

-- Ver produtos de uma categoria específica
SELECT * FROM produtos WHERE categoria = 'camiseta';

-- Contar produtos por categoria
SELECT categoria, COUNT(*) as total FROM produtos GROUP BY categoria;
```

## Vantagens da Estrutura Atual

1. **Simplicidade**: Tudo em uma tabela só
2. **Flexibilidade**: Campo JSON permite estruturas complexas
3. **Compatibilidade**: Funciona com seu banco atual
4. **Performance**: Menos joins necessários
5. **Manutenção**: Mais fácil de gerenciar 