// SCRIPT PARA A PÁGINA DE CADASTRO

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-cadastro');
    const campos = {
        nome: document.getElementById('nome'),
        sobrenome: document.getElementById('sobrenome'),
        telefone: document.getElementById('telefone'),
        email: document.getElementById('email'),
        senha: document.getElementById('senha'),
        confirmarSenha: document.getElementById('confirmar-senha'),
        aceite: document.getElementById('aceite')
    };

    // Máscara para telefone
    campos.telefone.addEventListener('input', function(e) {
        let valor = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
        valor = valor.replace(/(\d{2})(\d)/, '($1) $2'); // Coloca parênteses
        valor = valor.replace(/(\d{5})(\d)/, '$1-$2'); // Coloca hífen
        valor = valor.replace(/(-\d{4})\d+?$/, '$1'); // Limita a 4 dígitos após hífen
        e.target.value = valor;
    });

    // Validação em tempo real
    Object.keys(campos).forEach(campo => {
        if (campos[campo] && campos[campo].type !== 'checkbox') {
            campos[campo].addEventListener('blur', function() {
                validarCampo(campo, this.value);
            });
        }
    });

    // Validação da senha em tempo real
    campos.senha.addEventListener('input', function() {
        validarSenha(this.value);
    });

    // Validação da confirmação de senha
    campos.confirmarSenha.addEventListener('input', function() {
        validarConfirmacaoSenha(this.value);
    });

    // Submissão do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validarFormulario()) {
            // Aqui você pode enviar os dados para um servidor
            alert('Cadastro realizado com sucesso! 🎉');
            // Redirecionar para a página principal
            window.location.href = 'index.html';
        }
    });

    // Função para validar um campo específico
    function validarCampo(nomeCampo, valor) {
        const campo = campos[nomeCampo];
        const campoDiv = campo.parentElement;
        let valido = true;
        let mensagem = '';

        // Remove classes de erro anteriores
        campoDiv.classList.remove('erro');
        const mensagemErro = campoDiv.querySelector('.mensagem-erro');
        if (mensagemErro) {
            mensagemErro.remove();
        }

        // Validações específicas para cada campo
        switch (nomeCampo) {
            case 'nome':
            case 'sobrenome':
                if (valor.trim().length < 2) {
                    valido = false;
                    mensagem = 'Nome deve ter pelo menos 2 caracteres';
                }
                break;

            case 'telefone':
                const telefoneLimpo = valor.replace(/\D/g, '');
                if (telefoneLimpo.length < 10) {
                    valido = false;
                    mensagem = 'Telefone deve ter pelo menos 10 dígitos';
                }
                break;

            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(valor)) {
                    valido = false;
                    mensagem = 'E-mail inválido';
                }
                break;

            case 'senha':
                if (valor.length < 6) {
                    valido = false;
                    mensagem = 'Senha deve ter pelo menos 6 caracteres';
                }
                break;
        }

        // Aplica erro se necessário
        if (!valido) {
            campoDiv.classList.add('erro');
            const erroElement = document.createElement('div');
            erroElement.className = 'mensagem-erro';
            erroElement.textContent = mensagem;
            campoDiv.appendChild(erroElement);
        }

        return valido;
    }

    // Função para validar senha
    function validarSenha(senha) {
        const campoDiv = campos.senha.parentElement;
        campoDiv.classList.remove('erro');
        const mensagemErro = campoDiv.querySelector('.mensagem-erro');
        if (mensagemErro) {
            mensagemErro.remove();
        }

        if (senha.length < 6) {
            campoDiv.classList.add('erro');
            const erroElement = document.createElement('div');
            erroElement.className = 'mensagem-erro';
            erroElement.textContent = 'Senha deve ter pelo menos 6 caracteres';
            campoDiv.appendChild(erroElement);
            return false;
        }

        // Se a confirmação de senha já foi digitada, valida novamente
        if (campos.confirmarSenha.value) {
            validarConfirmacaoSenha(campos.confirmarSenha.value);
        }

        return true;
    }

    // Função para validar confirmação de senha
    function validarConfirmacaoSenha(confirmacao) {
        const campoDiv = campos.confirmarSenha.parentElement;
        campoDiv.classList.remove('erro');
        const mensagemErro = campoDiv.querySelector('.mensagem-erro');
        if (mensagemErro) {
            mensagemErro.remove();
        }

        if (confirmacao !== campos.senha.value) {
            campoDiv.classList.add('erro');
            const erroElement = document.createElement('div');
            erroElement.className = 'mensagem-erro';
            erroElement.textContent = 'As senhas não coincidem';
            campoDiv.appendChild(erroElement);
            return false;
        }

        return true;
    }

    // Função para validar todo o formulário
    function validarFormulario() {
        let valido = true;

        // Valida todos os campos obrigatórios
        Object.keys(campos).forEach(campo => {
            if (campos[campo]) {
                if (campos[campo].type === 'checkbox') {
                    if (!campos[campo].checked) {
                        valido = false;
                        alert('Você deve aceitar os termos de uso');
                    }
                } else {
                    if (!validarCampo(campo, campos[campo].value)) {
                        valido = false;
                    }
                }
            }
        });

        // Validação especial para confirmação de senha
        if (campos.confirmarSenha.value !== campos.senha.value) {
            valido = false;
            validarConfirmacaoSenha(campos.confirmarSenha.value);
        }

        return valido;
    }

    // Adiciona efeito de foco nos campos
    Object.keys(campos).forEach(campo => {
        if (campos[campo] && campos[campo].type !== 'checkbox') {
            campos[campo].addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            campos[campo].addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        }
    });
}); 