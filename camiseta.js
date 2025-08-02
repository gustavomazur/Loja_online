// SCRIPT ESPECÍFICO PARA A PÁGINA DA CAMISETA

document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const tamanhos = document.querySelectorAll('input[name="tamanho"]');
    const quantidadeInput = document.getElementById('quantidade');
    const btnDiminuir = document.getElementById('diminuir-quantidade');
    const btnAumentar = document.getElementById('aumentar-quantidade');
    const btnComprar = document.getElementById('botao-comprar-detalhado');
    const btnAdicionarCarrinho = document.getElementById('botao-adicionar-carrinho');
    const avisoTamanho = document.querySelector('.aviso-tamanho');
    const iconeCarrinho = document.getElementById('icone-carrinho');
    const contadorCarrinho = document.getElementById('contador-carrinho');
    const carrinho = document.getElementById('carrinho');
    const listaCarrinho = document.getElementById('lista-carrinho');
    const totalCarrinho = document.getElementById('total-carrinho');
    const btnFinalizar = document.getElementById('botao-finalizar');
    const btnVoltar = document.getElementById('botao-voltar');

    // Dados do produto
    const produto = {
        nome: 'Camiseta Diesel Branca',
        preco: 100,
        imagem: 'imagens/camiseta.png'
    };

    // Variáveis de estado
    let tamanhoSelecionado = null;
    let quantidade = 1;
    let carrinhoItens = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Inicialização
    atualizarContadorCarrinho();
    atualizarBotaoTamanho();

    // EVENTOS DE TAMANHO
    tamanhos.forEach(tamanho => {
        tamanho.addEventListener('change', function() {
            tamanhoSelecionado = this.value;
            atualizarBotaoTamanho();
            atualizarAvisoTamanho();
        });
    });

    // EVENTOS DE QUANTIDADE
    btnDiminuir.addEventListener('click', function() {
        if (quantidade > 1) {
            quantidade--;
            quantidadeInput.value = quantidade;
        }
    });

    btnAumentar.addEventListener('click', function() {
        quantidade++;
        quantidadeInput.value = quantidade;
    });

    quantidadeInput.addEventListener('input', function() {
        quantidade = parseInt(this.value) || 1;
        if (quantidade < 1) {
            quantidade = 1;
            this.value = 1;
        }
    });

    // BOTÕES DE AÇÃO
    btnComprar.addEventListener('click', function() {
        if (tamanhoSelecionado) {
            adicionarAoCarrinho();
            mostrarCarrinho();
        }
    });

    btnAdicionarCarrinho.addEventListener('click', function() {
        if (tamanhoSelecionado) {
            adicionarAoCarrinho();
            mostrarMensagemSucesso();
        }
    });

    // CARRINHO
    iconeCarrinho.addEventListener('click', function() {
        if (carrinhoItens.length > 0) {
            mostrarCarrinho();
        } else {
            alert('Seu carrinho está vazio!');
        }
    });

    btnFinalizar.addEventListener('click', function() {
        if (carrinhoItens.length > 0) {
            alert('Compra finalizada com sucesso! 🎉\nEm breve você receberá um e-mail com os detalhes.');
            limparCarrinho();
            esconderCarrinho();
        }
    });

    btnVoltar.addEventListener('click', function() {
        esconderCarrinho();
    });

    // FUNÇÕES AUXILIARES
    function atualizarBotaoTamanho() {
        const habilitado = tamanhoSelecionado !== null;
        btnComprar.disabled = !habilitado;
        btnAdicionarCarrinho.disabled = !habilitado;
        
        if (habilitado) {
            btnComprar.style.opacity = '1';
            btnAdicionarCarrinho.style.opacity = '1';
        } else {
            btnComprar.style.opacity = '0.5';
            btnAdicionarCarrinho.style.opacity = '0.5';
        }
    }

    function atualizarAvisoTamanho() {
        if (tamanhoSelecionado) {
            avisoTamanho.style.display = 'none';
        } else {
            avisoTamanho.style.display = 'block';
        }
    }

    function adicionarAoCarrinho() {
        const item = {
            id: Date.now(), // ID único
            nome: produto.nome,
            preco: produto.preco,
            tamanho: tamanhoSelecionado,
            quantidade: quantidade,
            imagem: produto.imagem
        };

        carrinhoItens.push(item);
        localStorage.setItem('carrinho', JSON.stringify(carrinhoItens));
        atualizarContadorCarrinho();
    }

    function atualizarContadorCarrinho() {
        const totalItens = carrinhoItens.reduce((total, item) => total + item.quantidade, 0);
        contadorCarrinho.textContent = totalItens;
    }

    function mostrarCarrinho() {
        carrinho.classList.add('ativo');
        document.body.classList.add('modo-carrinho');
        atualizarListaCarrinho();
    }

    function esconderCarrinho() {
        carrinho.classList.remove('ativo');
        document.body.classList.remove('modo-carrinho');
    }

    function atualizarListaCarrinho() {
        listaCarrinho.innerHTML = '';
        let total = 0;

        carrinhoItens.forEach((item, index) => {
            const li = document.createElement('li');
            li.innerHTML = `
                <div class="item-info">
                    <img src="${item.imagem}" alt="${item.nome}" width="50" height="50">
                    <div>
                        <strong>${item.nome}</strong><br>
                        <small>Tamanho: ${item.tamanho} | Qtd: ${item.quantidade}</small>
                    </div>
                </div>
                <div class="item-preco">
                    R$ ${(item.preco * item.quantidade).toFixed(2).replace('.', ',')}
                    <button class="botao-remover" onclick="removerItem(${index})">×</button>
                </div>
            `;
            listaCarrinho.appendChild(li);
            total += item.preco * item.quantidade;
        });

        totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2).replace('.', ',')}`;
    }

    function removerItem(index) {
        carrinhoItens.splice(index, 1);
        localStorage.setItem('carrinho', JSON.stringify(carrinhoItens));
        atualizarContadorCarrinho();
        atualizarListaCarrinho();
        
        if (carrinhoItens.length === 0) {
            esconderCarrinho();
        }
    }

    function limparCarrinho() {
        carrinhoItens = [];
        localStorage.removeItem('carrinho');
        atualizarContadorCarrinho();
    }

    function mostrarMensagemSucesso() {
        const mensagem = document.createElement('div');
        mensagem.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
        mensagem.textContent = '✅ Produto adicionado ao carrinho!';
        document.body.appendChild(mensagem);

        setTimeout(() => {
            mensagem.remove();
        }, 3000);
    }

    // Adicionar CSS para animação
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);

    // Tornar função removerItem global
    window.removerItem = removerItem;
}); 