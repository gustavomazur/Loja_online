// Começa o total zerado
let total = 0;
const listaCarrinho = document.getElementById('lista-carrinho');
const totalCarrinho = document.getElementById('total-carrinho');
const contadorCarrinho = document.getElementById('contador-carrinho');
const iconeCarrinho = document.getElementById('icone-carrinho');
const carrinho = document.getElementById('carrinho');
const botaoVoltar = document.getElementById('botao-voltar');
const body = document.body;

// Função pra atualizar o contador
function atualizarContador() {
  contadorCarrinho.textContent = listaCarrinho.children.length;
}

// Lógica do carrinho no index.html
const botoesComprar = document.querySelectorAll('.botao-comprar');
botoesComprar.forEach(botao => {
  botao.addEventListener('click', () => {
    const nome = botao.getAttribute('data-nome');
    const preco = parseFloat(botao.getAttribute('data-preco'));

    const item = document.createElement('li');
    item.innerHTML = `
      ${nome} - R$ ${preco.toFixed(2)}
      <button class="botao-remover">🗑️</button>
    `;

    item.querySelector('.botao-remover').addEventListener('click', () => {
      total -= preco;
      item.remove();
      totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
      atualizarContador();
    });

    listaCarrinho.appendChild(item);
    total += preco;
    totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
    atualizarContador();
  });
});

// Abrir/Fechar carrinho
iconeCarrinho?.addEventListener('click', () => {
  carrinho.classList.toggle('ativo');
  body.classList.toggle('modo-carrinho');
});

botaoVoltar?.addEventListener('click', () => {
  carrinho.classList.remove('ativo');
  body.classList.remove('modo-carrinho');
});

// Seleção de tamanho no produto.html
const opcoesTamanho = document.querySelectorAll('.opcao-tamanho');
opcoesTamanho.forEach(botao => {
  botao.addEventListener('click', () => {
    opcoesTamanho.forEach(b => b.classList.remove('ativo'));
    botao.classList.add('ativo');
  });
});

// Adicionar no carrinho (produto.html)
const botaoAdicionarCarrinho = document.getElementById('botao-adicionar-carrinho');
botaoAdicionarCarrinho?.addEventListener('click', () => {
  const nome = document.querySelector('.info-produto h2').textContent;
  const precoTexto = document.querySelector('.info-produto .preco').textContent;
  const preco = parseFloat(precoTexto.replace('R$', '').replace(',', '.'));
  const quantidade = parseInt(document.getElementById('quantidade').value);
  const tamanhoSelecionado = document.querySelector('.opcao-tamanho.ativo');

  if (!tamanhoSelecionado) {
    alert('Selecione um tamanho!');
    return;
  }

  const tamanho = tamanhoSelecionado.textContent;

  for (let i = 0; i < quantidade; i++) {
    const item = document.createElement('li');
    item.innerHTML = `
      ${nome} - ${tamanho} - R$ ${preco.toFixed(2)} (x1)
      <button class="botao-remover">🗑️</button>
    `;

    item.querySelector('.botao-remover').addEventListener('click', () => {
      total -= preco;
      item.remove();
      totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
      atualizarContador();
    });

    listaCarrinho.appendChild(item);
    total += preco;
  }

  totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
  atualizarContador();
});

// Comprar agora (produto.html)
const botaoComprarAgora = document.getElementById('botao-comprar-detalhado');
botaoComprarAgora?.addEventListener('click', () => {
  const nome = document.querySelector('.info-produto h2').textContent;
  const precoTexto = document.querySelector('.info-produto .preco').textContent;
  const preco = parseFloat(precoTexto.replace('R$', '').replace(',', '.'));
  const quantidade = parseInt(document.getElementById('quantidade').value);
  const tamanhoSelecionado = document.querySelector('.opcao-tamanho.ativo');

  if (!tamanhoSelecionado) {
    alert('Selecione um tamanho!');
    return;
  }

  const tamanho = tamanhoSelecionado.textContent;

  for (let i = 0; i < quantidade; i++) {
    const item = document.createElement('li');
    item.innerHTML = `
      ${nome} - ${tamanho} - R$ ${preco.toFixed(2)} (x1)
      <button class="botao-remover">🗑️</button>
    `;

    item.querySelector('.botao-remover').addEventListener('click', () => {
      total -= preco;
      item.remove();
      totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
      atualizarContador();
    });

    listaCarrinho.appendChild(item);
    total += preco;
  }

  totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
  atualizarContador();

  carrinho.classList.add('ativo');
  body.classList.add('modo-carrinho');
});
