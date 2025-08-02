document.addEventListener('DOMContentLoaded', function () {
    // Elementos principais
    const corBtns = document.querySelectorAll('.btn-cor');
    const miniaturas = document.querySelectorAll('.miniatura-img');
    const imgPrincipal = document.getElementById('img-principal');
    const tamanhosBtns = document.querySelectorAll('.btn-tamanho');
    const estoqueSpan = document.getElementById('estoque-produto');

    // Troca de cor
    corBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            // Remove ativo de todos
            corBtns.forEach(b => b.classList.remove('ativo'));
            this.classList.add('ativo');
            const cor = this.dataset.cor;

            // Esconde todas miniaturas
            document.querySelectorAll('.miniaturas-por-cor').forEach(div => div.style.display = 'none');
            // Mostra só as da cor selecionada
            const divCor = document.getElementById('miniaturas-' + cor);
            if (divCor) {
                divCor.style.display = 'flex';
                // Troca a imagem principal para a primeira miniatura da cor
                const primeira = divCor.querySelector('.miniatura-img');
                if (primeira) {
                    imgPrincipal.src = primeira.src;
                }
            }
        });
    });

    // Troca da imagem principal ao clicar na miniatura
    document.querySelectorAll('.miniatura-img').forEach(img => {
        img.addEventListener('click', function () {
            imgPrincipal.src = this.src;
        });
    });

    // Seleção de tamanho
    tamanhosBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            tamanhosBtns.forEach(b => b.classList.remove('ativo'));
            this.classList.add('ativo');
        });
    });

    // Adicionar ao carrinho
    const btnAdicionarCarrinho = document.getElementById('btn-adicionar-carrinho');
    if (btnAdicionarCarrinho) {
        btnAdicionarCarrinho.addEventListener('click', function () {
            const corSelecionada = document.querySelector('.btn-cor.ativo');
            const tamanhoSelecionado = document.querySelector('.btn-tamanho.ativo');
            const nome = document.querySelector('h1').textContent;
            const preco = parseFloat(document.querySelector('.preco').textContent.replace('R$', '').replace(',', '.'));
            const imagem = document.getElementById('img-principal').src;

            if (!corSelecionada || !tamanhoSelecionado) {
                alert('Selecione a cor e o tamanho!');
                return;
            }

            const produto = {
                nome: nome,
                preco: preco,
                cor: corSelecionada.textContent.trim(),
                tamanho: tamanhoSelecionado.textContent.trim(),
                imagem: imagem
            };

            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
            carrinho.push(produto);
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            alert('Produto adicionado ao carrinho!');
        });
    }
}); 