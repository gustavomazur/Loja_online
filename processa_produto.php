<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
        // Dados básicos do produto
        $nome = trim($_POST['nome']);
        $preco = floatval($_POST['preco']);
        $categoria = trim($_POST['categoria']);
        $cores = trim($_POST['cores'] ?? '');
        $tamanhos = trim($_POST['tamanhos'] ?? '');
        $estoque = intval($_POST['estoque'] ?? 0);

        // DEBUG DO UPLOAD - Imagem principal
        if (isset($_FILES['imagem'])) {
            if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
                die('Erro real do upload da imagem principal: ' . $_FILES['imagem']['error']);
            }
        }

        // Processamento da imagem principal (obrigatória)
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imagemTmp = $_FILES['imagem']['tmp_name'];
            $imagemNome = basename($_FILES['imagem']['name']);
            $extensao = strtolower(pathinfo($imagemNome, PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (!in_array($extensao, $permitidas)) {
                header('Location: painel.php?msg=Tipo de imagem não permitido.');
                exit();
            }
            
            // Gera nome único para a imagem principal
            $novoNome = uniqid('prod_', true) . '.' . $extensao;
            $destino = 'imagens/' . $novoNome;
            
            if (!move_uploaded_file($imagemTmp, $destino)) {
                header('Location: painel.php?msg=Erro ao salvar imagem principal.');
                exit();
            }
            $imagem_url = $destino;
        } else {
            header('Location: painel.php?msg=Imagem principal é obrigatória.');
            exit();
        }

        // Processamento das imagens por cor (opcional)
        $imagens_por_cor = [];
        if (!empty($cores)) {
            $cores_array = array_map('trim', explode(',', $cores));
            
            foreach ($cores_array as $cor) {
                $cor_limpa = str_replace(' ', '_', $cor);
                $chave_arquivos = "imagens_{$cor_limpa}";
                
                if (isset($_FILES[$chave_arquivos]) && is_array($_FILES[$chave_arquivos]['name'])) {
                    $imagens_cor = [];
                    
                    for ($i = 0; $i < count($_FILES[$chave_arquivos]['name']); $i++) {
                        if ($_FILES[$chave_arquivos]['error'][$i] === UPLOAD_ERR_OK) {
                            $imagemTmp = $_FILES[$chave_arquivos]['tmp_name'][$i];
                            $imagemNome = basename($_FILES[$chave_arquivos]['name'][$i]);
                            $extensao = strtolower(pathinfo($imagemNome, PATHINFO_EXTENSION));
                            
                            if (in_array($extensao, $permitidas)) {
                                // Gera nome único para cada imagem
                                $novoNome = uniqid("prod_{$cor_limpa}_", true) . '.' . $extensao;
                                $destino = 'imagens/' . $novoNome;
                                
                                if (move_uploaded_file($imagemTmp, $destino)) {
                                    $imagens_cor[] = $destino;
                                }
                            }
                        }
                    }
                    
                    if (!empty($imagens_cor)) {
                        $imagens_por_cor[$cor] = $imagens_cor;
                    }
                }
            }
        }

        try {
            // Converte as imagens por cor para JSON para salvar no campo 'imagens'
            $imagens_json = !empty($imagens_por_cor) ? json_encode($imagens_por_cor, JSON_UNESCAPED_UNICODE) : null;
            
            // Salva o produto usando a estrutura atual do banco
            $sql = "INSERT INTO produtos (nome, preco, categoria, imagem_url, cor, imagens, tamanhos, estoque) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nome, $preco, $categoria, $imagem_url, $cores, $imagens_json, $tamanhos, $estoque]);
            
            header('Location: painel.php?msg=Produto cadastrado com sucesso!');
            exit();
            
        } catch (PDOException $e) {
            header('Location: painel.php?msg=Erro ao cadastrar produto: ' . urlencode($e->getMessage()));
            exit();
        }
        
    } elseif (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
        // Código para editar produto
        $id = intval($_POST['id']);
        $nome = trim($_POST['nome']);
        $preco = floatval($_POST['preco']);
        $categoria = trim($_POST['categoria']);
        $cor = trim($_POST['cor'] ?? '');
        $tamanhos = trim($_POST['tamanhos'] ?? '');
        $estoque = intval($_POST['estoque'] ?? 0);

        try {
            // Verifica se foi enviada uma nova imagem
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                // Processa nova imagem
                $imagemTmp = $_FILES['imagem']['tmp_name'];
                $imagemNome = basename($_FILES['imagem']['name']);
                $extensao = strtolower(pathinfo($imagemNome, PATHINFO_EXTENSION));
                $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($extensao, $permitidas)) {
                    header('Location: editar_produto.php?id=' . $id . '&msg=Tipo de imagem não permitido.');
                    exit();
                }
                
                // Gera nome único para nova imagem
                $novoNome = uniqid('prod_', true) . '.' . $extensao;
                $destino = 'imagens/' . $novoNome;
                if (!move_uploaded_file($imagemTmp, $destino)) {
                    header('Location: editar_produto.php?id=' . $id . '&msg=Erro ao salvar nova imagem.');
                    exit();
                }
                
                // Remove imagem antiga (opcional)
                $stmt = $pdo->prepare("SELECT imagem_url FROM produtos WHERE id = ?");
                $stmt->execute([$id]);
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($produto && file_exists($produto['imagem_url'])) {
                    unlink($produto['imagem_url']);
                }
                
                // Atualiza com nova imagem
                $sql = "UPDATE produtos SET nome = ?, preco = ?, categoria = ?, imagem_url = ?, cor = ?, tamanhos = ?, estoque = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $preco, $categoria, $destino, $cor, $tamanhos, $estoque, $id]);
            } else {
                // Atualiza sem trocar imagem
                $sql = "UPDATE produtos SET nome = ?, preco = ?, categoria = ?, cor = ?, tamanhos = ?, estoque = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nome, $preco, $categoria, $cor, $tamanhos, $estoque, $id]);
            }
            
            header('Location: painel.php?msg=Produto atualizado com sucesso!');
            exit();
            
        } catch (PDOException $e) {
            header('Location: editar_produto.php?id=' . $id . '&msg=Erro ao atualizar produto: ' . urlencode($e->getMessage()));
            exit();
        }
    }
} else {
    header('Location: painel.php');
    exit();
}
?> 