document.addEventListener('DOMContentLoaded', () => {
    const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    const resumoDiv = document.getElementById('resumo-pedido');
  
    if (carrinho.length === 0) {
      resumoDiv.innerHTML = '<p>Seu carrinho estava vazio.</p>';
      return;
    }
  
    let total = 0;
    const lista = document.createElement('ul');
  
    carrinho.forEach(produto => {
      const preco = parseFloat(produto.preco); // garante que é número
      const quantidade = parseInt(produto.quantidade);
  
      const item = document.createElement('li');
      item.textContent = `${produto.nome} - R$ ${preco.toFixed(2)} x ${quantidade}`;
      total += preco * quantidade;
      lista.appendChild(item);
    });
  
    const totalElem = document.createElement('p');
    totalElem.innerHTML = `<strong>Total:</strong> R$ ${total.toFixed(2)}`;
  
    resumoDiv.appendChild(lista);
    resumoDiv.appendChild(totalElem);
  
    // Limpa carrinho
    localStorage.removeItem('carrinho');
  
    // Atualiza visualmente o carrinho no menu (se houver)
    const contador = document.getElementById('contador-carrinho');
    if (contador) contador.textContent = '(0)';
  });
  