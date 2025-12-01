<?php 
$pageTitle = 'FanBeads – Dashboard Gerencial'; 
require 'Views/_header.php'; 
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="dashboard-page" style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
    <h1>Dashboard Gerencial</h1>

    <div class="stats-cards" style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <div class="card-stat" style="flex: 1; background: #63acff; color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <h3 style="font-size: 2rem; margin: 0;"><?= $stats['total_pedidos'] ?></h3>
            <span style="font-size: 0.9rem;">Pedidos Realizados</span>
        </div>
        <div class="card-stat" style="flex: 1; background: #AD00F9; color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <h3 style="font-size: 2rem; margin: 0;">R$ <?= number_format($stats['faturamento_total'], 2, ',', '.') ?></h3>
            <span style="font-size: 0.9rem;">Faturamento Total</span>
        </div>
        <div class="card-stat" style="flex: 1; background: #28a745; color: white; padding: 1.5rem; border-radius: 8px; text-align: center;">
            <h3 style="font-size: 2rem; margin: 0;"><?= $stats['total_produtos'] ?></h3>
            <span style="font-size: 0.9rem;">Produtos Cadastrados</span>
        </div>
    </div>

    <div class="charts-container" style="display: flex; gap: 2rem; flex-wrap: wrap;">
        
        <div class="chart-box" style="flex: 2; min-width: 300px; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="text-align: center; margin-bottom: 1rem;">Faturamento Mensal (Últimos 6 meses)</h3>
            <canvas id="chartVendas"></canvas>
        </div>

        <div class="chart-box" style="flex: 1; min-width: 300px; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="text-align: center; margin-bottom: 1rem;">Top 5 Produtos Mais Vendidos</h3>
            <canvas id="chartTopProdutos"></canvas>
        </div>

    </div>
</main>

<script>
    // --- Configuração do Gráfico de Vendas (Barras) ---
    const ctxVendas = document.getElementById('chartVendas').getContext('2d');
    new Chart(ctxVendas, {
        type: 'bar', // Tipo barra vertical
        data: {
            labels: <?= $mesLabels ?>, // Vem do PHP
            datasets: [{
                label: 'Faturamento (R$)',
                data: <?= $mesValues ?>, // Vem do PHP
                backgroundColor: 'rgba(99, 172, 255, 0.6)',
                borderColor: 'rgba(99, 172, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } }
        }
    });

    // --- Configuração do Gráfico de Top Produtos (Rosca) ---
    const ctxTop = document.getElementById('chartTopProdutos').getContext('2d');
    new Chart(ctxTop, {
        type: 'doughnut', // Tipo Rosca
        data: {
            labels: <?= $prodLabels ?>, // Vem do PHP
            datasets: [{
                data: <?= $prodValues ?>, // Vem do PHP
                backgroundColor: [
                    '#AD00F9', '#63acff', '#ff6363', '#ffcd56', '#4bc0c0'
                ]
            }]
        }
    });
</script>

<?php require 'Views/_footer.php'; ?>