// Começa o total zerado
let total = 0;

// Pegamos os elementos do carrinho
const listaCarrinho = document.getElementById('lista-carrinho');
const totalCarrinho = document.getElementById('total-carrinho');

// Pegamos todos os botões de comprar
const botoesComprar = document.querySelectorAll('.botao-comprar');

// Pra cada botão, escuta o clique
botoesComprar.forEach(botao => {
  botao.addEventListener('click', () => {
    const nome = botao.getAttribute('data-nome');
    const preco = parseFloat(botao.getAttribute('data-preco'));

    // Cria item na lista
    const item = document.createElement('li');
    item.textContent = `${nome} - R$ ${preco.toFixed(2)}`;
    listaCarrinho.appendChild(item);

    // Atualiza total
    total += preco;
    totalCarrinho.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
  });
});

const iconeCarrinho = document.getElementById('icone-carrinho');
const carrinho = document.getElementById('carrinho');
const body = document.body;

iconeCarrinho.addEventListener('click', () => {
  // ativa ou desativa o modo carrinho
  carrinho.classList.toggle('ativo');
  body.classList.toggle('modo-carrinho'); // adiciona classe no <body>
});


