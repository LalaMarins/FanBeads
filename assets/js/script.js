// assets/js/script.js

/**
 * Aguarda o carregamento completo do HTML para garantir que todos os
 * elementos da página existam antes de tentar manipulá-los.
 */
document.addEventListener('DOMContentLoaded', () => {

    /**
     * INICIALIZADOR PARA O FORMULÁRIO DE PRODUTO (form_produto.php)
     * Ativa o botão para adicionar múltiplos campos de cor personalizada.
     */
    const initAdminProductForm = () => {
        const addColorBtn = document.getElementById('add-cor-btn');
        const colorsContainer = document.getElementById('novas-cores-container');

        // Só executa se os elementos existirem nesta página.
        if (addColorBtn && colorsContainer) {
            addColorBtn.addEventListener('click', () => {
                const newLabel = document.createElement('label');
                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'cor_extra[]';
                newInput.placeholder = 'Escreva uma nova cor';
                
                newLabel.appendChild(newInput);
                colorsContainer.appendChild(newLabel);
            });
        }
    };

    /**
     * INICIALIZADOR PARA A PÁGINA DO CARRINHO (carrinho.php)
     * Adiciona a funcionalidade AJAX para atualizar quantidades e remover itens.
     */
    const initCartPage = () => {
        const cartForm = document.getElementById('cart-form');
        // Só executa se o formulário do carrinho existir nesta página.
        if (!cartForm) {
            return;
        }

        // Função auxiliar para enviar dados via POST.
        const postData = async (url, formData) => {
            const response = await fetch(url, { method: 'POST', body: formData });
            if (!response.ok || response.status !== 204) { // 204 = No Content (sucesso)
                throw new Error(`Erro na requisição: ${response.statusText}`);
            }
        };

        // Lógica para atualizar a quantidade.
        cartForm.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', async () => {
                const index = input.name.match(/\[(\d+)\]/)[1]; // Extrai o índice do 'name'
                const formData = new FormData();
                formData.append(`quantidade[${index}]`, input.value);
                
                try {
                    await postData('/fanbeads/carrinho/atualizar', formData);
                    window.location.reload(); // Recarrega a página para atualizar totais.
                } catch (e) {
                    console.error('Erro ao atualizar quantidade:', e);
                    alert('Não foi possível atualizar o carrinho.');
                }
            });
        });

        // Lógica para remover um item.
        cartForm.querySelectorAll('.remove-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const index = button.dataset.index;
                const formData = new FormData();
                formData.append('index', index);
                
                try {
                    await postData('/fanbeads/carrinho/remover', formData);
                    window.location.reload(); // Recarrega a página para atualizar o carrinho.
                } catch (e) {
                    console.error('Erro ao remover item:', e);
                    alert('Não foi possível remover o item do carrinho.');
                }
            });
        });
    };

    /**
     * INICIALIZADOR PARA A PÁGINA DE DETALHES (detalhes_produto.php)
     * Valida o formulário de personalização antes do envio.
     */
    const initDetailsForm = () => {
        const form = document.getElementById('detalhes-form');
        // Só executa se o formulário de detalhes existir nesta página.
        if (!form) {
            return;
        }

        form.addEventListener('submit', (event) => {
            // 1. Valida a seleção de cor.
            const corSelecionada = form.querySelector('input[name="cor"]:checked');
            if (!corSelecionada) {
                alert('Por favor, selecione uma cor.');
                event.preventDefault(); // Impede o envio do formulário.
                return;
            }

            // 2. Valida a seleção de tamanho (se existir).
            const tamanhoFieldset = form.querySelector('.tamanho-fieldset');
            if (tamanhoFieldset) {
                const tamanhoSelecionado = form.querySelector('input[name="tamanho"]:checked');
                if (!tamanhoSelecionado) {
                    alert('Por favor, selecione um tamanho.');
                    event.preventDefault();
                    return;
                }
                // 3. Se "Outro" tamanho foi selecionado, valida o campo de texto.
                if (tamanhoSelecionado.value === 'extra') {
                    const tamanhoExtra = form.querySelector('input[name="tamanho_extra"]').value.trim();
                    if (!tamanhoExtra) {
                        alert('Por favor, informe o tamanho no campo "Outro".');
                        event.preventDefault();
                    }
                }
            }
        });
    };

    // --- Executa todos os inicializadores ---
    initAdminProductForm();
    initCartPage();
    initDetailsForm();
});