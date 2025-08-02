// SCRIPT PARA COMENTÁRIOS E AVALIAÇÕES - PERFUME

document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const formComentario = document.getElementById('enviar-comentario');
    const nomeComentario = document.getElementById('nome-comentario');
    const textoComentario = document.getElementById('texto-comentario');
    const fotoComentario = document.getElementById('foto-comentario');
    const avaliacaoEstrelas = document.querySelectorAll('input[name="avaliacao"]');
    const filtros = document.querySelectorAll('.filtro');
    const containerComentarios = document.getElementById('comentarios-container');

    // Dados do produto
    const produtoId = 'perfume-212-vip';
    
    // Carregar comentários existentes
    let comentarios = JSON.parse(localStorage.getItem(`comentarios_${produtoId}`)) || [];
    let filtroAtivo = 'todos';

    // Comentários de exemplo para demonstração
    if (comentarios.length === 0) {
        comentarios = [
            {
                id: 1,
                nome: 'Maria Silva',
                texto: 'Perfume incrível! Duração excelente e fragrância sofisticada. Recomendo muito!',
                avaliacao: 5,
                data: '2024-01-15',
                foto: null
            },
            {
                id: 2,
                nome: 'João Santos',
                texto: 'Presente perfeito para meu pai. Ele adorou o cheiro e a embalagem.',
                avaliacao: 5,
                data: '2024-01-10',
                foto: null
            },
            {
                id: 3,
                nome: 'Ana Costa',
                texto: 'Boa qualidade, mas o cheiro poderia ser mais duradouro.',
                avaliacao: 4,
                data: '2024-01-08',
                foto: null
            }
        ];
        salvarComentarios();
    }

    // Inicialização
    carregarComentarios();
    atualizarEstatisticas();

    // EVENTOS
    formComentario.addEventListener('click', enviarComentario);
    
    filtros.forEach(filtro => {
        filtro.addEventListener('click', function() {
            const filtroValor = this.getAttribute('data-filtro');
            aplicarFiltro(filtroValor);
        });
    });

    // FUNÇÕES
    function enviarComentario(e) {
        e.preventDefault();

        // Validação
        if (!nomeComentario.value.trim()) {
            alert('Por favor, digite seu nome.');
            return;
        }

        if (!textoComentario.value.trim()) {
            alert('Por favor, digite seu comentário.');
            return;
        }

        // Pegar avaliação selecionada
        let avaliacao = 5;
        avaliacaoEstrelas.forEach(estrela => {
            if (estrela.checked) {
                avaliacao = parseInt(estrela.value);
            }
        });

        // Processar foto se houver
        let fotoUrl = null;
        if (fotoComentario.files.length > 0) {
            const arquivo = fotoComentario.files[0];
            
            // Validar tamanho (5MB)
            if (arquivo.size > 5 * 1024 * 1024) {
                alert('A foto deve ter no máximo 5MB.');
                return;
            }

            // Validar tipo
            if (!arquivo.type.startsWith('image/')) {
                alert('Por favor, selecione apenas arquivos de imagem.');
                return;
            }

            // Converter para base64 (simulação de upload)
            const reader = new FileReader();
            reader.onload = function(e) {
                fotoUrl = e.target.result;
                salvarComentarioCompleto(avaliacao, fotoUrl);
            };
            reader.readAsDataURL(arquivo);
        } else {
            salvarComentarioCompleto(avaliacao, null);
        }
    }

    function salvarComentarioCompleto(avaliacao, fotoUrl) {
        const novoComentario = {
            id: Date.now(),
            nome: nomeComentario.value.trim(),
            texto: textoComentario.value.trim(),
            avaliacao: avaliacao,
            data: new Date().toISOString().split('T')[0],
            foto: fotoUrl
        };

        comentarios.unshift(novoComentario); // Adicionar no início
        salvarComentarios();
        carregarComentarios();
        atualizarEstatisticas();
        limparFormulario();
        
        // Mensagem de sucesso
        mostrarMensagemSucesso();
    }

    function salvarComentarios() {
        localStorage.setItem(`comentarios_${produtoId}`, JSON.stringify(comentarios));
    }

    function carregarComentarios() {
        const comentariosFiltrados = filtrarComentarios(comentarios, filtroAtivo);
        exibirComentarios(comentariosFiltrados);
    }

    function filtrarComentarios(comentarios, filtro) {
        if (filtro === 'todos') {
            return comentarios;
        }
        return comentarios.filter(comentario => comentario.avaliacao === parseInt(filtro));
    }

    function aplicarFiltro(filtro) {
        filtroAtivo = filtro;
        
        // Atualizar botões de filtro
        filtros.forEach(btn => btn.classList.remove('ativo'));
        event.target.classList.add('ativo');
        
        carregarComentarios();
    }

    function exibirComentarios(comentarios) {
        containerComentarios.innerHTML = '';

        if (comentarios.length === 0) {
            containerComentarios.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">Nenhum comentário encontrado.</p>';
            return;
        }

        comentarios.forEach(comentario => {
            const comentarioElement = criarElementoComentario(comentario);
            containerComentarios.appendChild(comentarioElement);
        });
    }

    function criarElementoComentario(comentario) {
        const div = document.createElement('div');
        div.className = 'comentario';

        const estrelas = '⭐'.repeat(comentario.avaliacao);
        const dataFormatada = formatarData(comentario.data);

        let fotoHTML = '';
        if (comentario.foto) {
            fotoHTML = `<img src="${comentario.foto}" alt="Foto do comentário" class="comentario-foto">`;
        }

        div.innerHTML = `
            <div class="comentario-header">
                <span class="comentario-nome">${comentario.nome}</span>
                <span class="comentario-data">${dataFormatada}</span>
            </div>
            <div class="comentario-estrelas">
                ${estrelas}
            </div>
            <div class="comentario-texto">
                ${comentario.texto}
            </div>
            ${fotoHTML}
        `;

        return div;
    }

    function formatarData(dataString) {
        const data = new Date(dataString);
        return data.toLocaleDateString('pt-BR');
    }

    function atualizarEstatisticas() {
        if (comentarios.length === 0) return;

        const totalAvaliacoes = comentarios.length;
        const somaAvaliacoes = comentarios.reduce((soma, comentario) => soma + comentario.avaliacao, 0);
        const media = (somaAvaliacoes / totalAvaliacoes).toFixed(1);

        // Atualizar elementos na página
        const notaMediaElement = document.querySelector('.nota-media');
        const totalAvaliacoesElement = document.querySelector('.total-avaliacoes');

        if (notaMediaElement) notaMediaElement.textContent = media;
        if (totalAvaliacoesElement) totalAvaliacoesElement.textContent = `(${totalAvaliacoes} avaliações)`;
    }

    function limparFormulario() {
        nomeComentario.value = '';
        textoComentario.value = '';
        fotoComentario.value = '';
        
        // Resetar estrelas para 5
        document.getElementById('estrela5').checked = true;
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
        mensagem.textContent = '✅ Comentário enviado com sucesso!';
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
}); 