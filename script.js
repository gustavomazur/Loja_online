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
const botoesAdicionar = document.querySelectorAll('.botao-adicionar-carrinho');
botoesAdicionar.forEach(botao => {
  botao.addEventListener('click', (e) => {
    e.stopPropagation();
    adicionarAoCarrinho(botao);
    botao.classList.add('feedback');
    setTimeout(() => botao.classList.remove('feedback'), 400);
  });
});

const botoesComprar = document.querySelectorAll('.botao-comprar');
botoesComprar.forEach(botao => {
  botao.addEventListener('click', () => {
    adicionarAoCarrinho(botao);
    carrinho.classList.add('ativo');
    body.classList.add('modo-carrinho');
  });
});

// Função para adicionar item ao carrinho
function adicionarAoCarrinho(botao) {
  const nome = botao.getAttribute('data-nome');
  const preco = parseFloat(botao.getAttribute('data-preco'));

  const itemExistente = Array.from(listaCarrinho.children).find(
    item => item.getAttribute('data-nome') === nome
  );

  if (itemExistente) {
    console.log("Item já está no carrinho.");
    return; 
  }

  const item = document.createElement('li');
  item.setAttribute('data-nome', nome);
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
}

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

document.querySelectorAll('.categoria-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.categoria-btn').forEach(b => b.classList.remove('ativo'));
    this.classList.add('ativo');
    const categoria = this.getAttribute('data-categoria');
    document.querySelectorAll('.produto').forEach(prod => {
      if (categoria === 'todos') {
        prod.style.display = '';
      } else if (prod.getAttribute('data-categoria') === categoria) {
        prod.style.display = '';
      } else {
        prod.style.display = 'none';
      }
    });
  });
});

function rastrearPedido() {
  const codigo = document.getElementById('codigo-pedido').value.trim();
  const statusDiv = document.getElementById('status-pedido');
  if (!codigo) {
    statusDiv.innerHTML = '<span style="color:red;">Digite o número do pedido.</span>';
    return;
  }
  // Simulação de status
  statusDiv.innerHTML = `<b>Status do pedido ${codigo}:</b> <br>Em separação no estoque. <br>Previsão de entrega: 3 dias úteis.`;
}

function calcularFrete() {
  const cep = document.getElementById('cep').value.trim();
  const resultado = document.getElementById('resultado-frete');
  if (!cep) {
    resultado.innerHTML = '<span style="color:red;">Digite o CEP.</span>';
    return;
  }
  // Simulação de cálculo
  let frete = 25;
  if (cep.startsWith('11')) frete = 10;
  if (cep.startsWith('22')) frete = 15;
  resultado.innerHTML = `
    <b>Frete:</b> R$ ${frete},00<br>
    <b>Pagamento à vista:</b> 10% de desconto<br>
    <b>Cartão em até 12x:</b> Valor com taxa: R$ ${(100 + frete) * 1.08} (8% de taxa)
  `;
}

// Função para atualizar o carrinho visual a partir do localStorage
function atualizarCarrinhoVisual() {
  const listaCarrinho = document.getElementById('lista-carrinho');
  const contadorCarrinho = document.getElementById('contador-carrinho');
  const totalCarrinho = document.getElementById('total-carrinho');
  if (!listaCarrinho || !contadorCarrinho || !totalCarrinho) return;
  let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
  listaCarrinho.innerHTML = '';
  let total = 0;
  carrinho.forEach((item, idx) => {
    const li = document.createElement('li');
    li.innerHTML = `
      <img src="${item.imagem}" style="width:40px;vertical-align:middle;border-radius:4px;margin-right:8px;">
      ${item.nome} - ${item.cor} - ${item.tamanho} - R$ ${item.preco.toFixed(2)}
      <button class="botao-remover">🗑️</button>
    `;
    li.querySelector('.botao-remover').addEventListener('click', function() {
      carrinho.splice(idx, 1);
      localStorage.setItem('carrinho', JSON.stringify(carrinho));
      atualizarCarrinhoVisual();
    });
    listaCarrinho.appendChild(li);
    total += item.preco;
  });
  contadorCarrinho.textContent = carrinho.length;
  totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
}

document.addEventListener('DOMContentLoaded', atualizarCarrinhoVisual);
