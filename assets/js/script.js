document.addEventListener('DOMContentLoaded', () => {
  // 1) Adicionar múltiplos inputs de "outra cor"
  const btn        = document.getElementById('add-cor-btn');
  const container  = document.getElementById('novas-cores-container');

  if (btn && container) {
    btn.addEventListener('click', () => {
      const label = document.createElement('label');
      label.style.marginRight = '10px';

      const input = document.createElement('input');
      input.type        = 'text';
      input.name        = 'cor_extra[]';
      input.placeholder = 'Escreva uma nova cor';

      label.appendChild(input);
      container.appendChild(label);
    });
  }

  // 2) Validação customizada do formulário de detalhes
  const form = document.getElementById('detalhes-form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    // Checa se selecionou uma cor
    const cor = form.querySelector('input[name="cor"]:checked');
    if (!cor) {
      alert('Por favor, selecione uma cor.');
      e.preventDefault();
      return;
    }

    // Se houver campo de tamanho, checa seleção
    const tfset = form.querySelector('.tamanho-fieldset');
    if (tfset) {
      const tam = form.querySelector('input[name="tamanho"]:checked');
      if (!tam) {
        alert('Por favor, selecione um tamanho.');
        e.preventDefault();
        return;
      }

      // Se escolheu "extra", exige texto
      if (tam.value === 'extra') {
        const extra = form.querySelector('input[name="tamanho_extra"]').value.trim();
        if (!extra) {
          alert('Por favor, informe o tamanho no campo "Outro".');
          e.preventDefault();
          return;
        }
      }
    }

    // se chegou aqui, tudo ok e o form é submetido
  });
});
