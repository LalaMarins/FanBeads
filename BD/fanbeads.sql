-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23-Out-2025 às 01:55
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `fanbeads`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nome`) VALUES
(2, 'Chaveiros'),
(1, 'Pulseiras');

-- --------------------------------------------------------

--
-- Estrutura da tabela `opcao_variacao`
--

CREATE TABLE `opcao_variacao` (
  `id_opcao` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `valor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `opcao_variacao`
--

INSERT INTO `opcao_variacao` (`id_opcao`, `variacao_id`, `valor`) VALUES
(1, 1, 'Vermelho'),
(2, 1, 'Azul'),
(3, 1, 'Preto'),
(4, 1, 'Branco'),
(5, 1, 'Laranja'),
(6, 1, 'Amarelo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `valor_total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Processando'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `data_pedido`, `valor_total`, `status`) VALUES
(1, 2, '2025-10-12 21:03:02', '23.80', 'Processando'),
(2, 2, '2025-10-22 23:46:00', '11.90', 'Processando');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id_item` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `cor` varchar(100) DEFAULT NULL,
  `tamanho` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `pedido_itens`
--

INSERT INTO `pedido_itens` (`id_item`, `id_pedido`, `id_produto`, `quantidade`, `preco_unitario`, `cor`, `tamanho`) VALUES
(1, 1, 6, 2, '11.90', 'Branco', '15'),
(2, 2, 5, 1, '11.90', 'Branco', '15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome`, `descricao`, `preco`, `imagem`, `categoria_id`) VALUES
(2, 'TesteStrap', 'DescriçãoStrap', '15.00', 'img_682683ddba15b.png', 2),
(5, 'Pulseira de Kpop da Oh My Girl', 'omg', '11.90', 'img_6826875934167.png', 1),
(6, 'Pulseira da Taylor do Reputation', 'descrição', '11.90', 'img_683a4b304ace5.png', 1),
(7, 'Pulseira de Epic the Musical - The Wisdom Saga', 'ddesc', '11.90', 'img_683a4b5805e0f.png', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto_opcao`
--

CREATE TABLE `produto_opcao` (
  `produto_id` int(11) NOT NULL,
  `opcao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produto_opcao`
--

INSERT INTO `produto_opcao` (`produto_id`, `opcao_id`) VALUES
(2, 4),
(5, 2),
(5, 4),
(5, 6),
(6, 3),
(6, 4),
(7, 2),
(7, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `username`, `email`, `senha`, `role`) VALUES
(1, 'admin', 'admin@fanbeads.com', '$2b$12$uB2OeUuDvnn04Haq/lgRjeY4tgnpCgRUT9vtJZ92z.9.7ZekPjUCK', 'admin'),
(2, 'Ana', 'almarins34@gmail.com', '$2y$10$9nmUic.44n/s1RyORh7OiefRPrYZSV6Wev0vrKnVdmuVnFLPqdhgO', 'user');

-- --------------------------------------------------------

--
-- Estrutura da tabela `variacao`
--

CREATE TABLE `variacao` (
  `id_variacao` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `variacao`
--

INSERT INTO `variacao` (`id_variacao`, `nome`) VALUES
(1, 'Cor');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `opcao_variacao`
--
ALTER TABLE `opcao_variacao`
  ADD PRIMARY KEY (`id_opcao`),
  ADD KEY `variacao_id` (`variacao_id`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`),
  ADD KEY `token_index` (`token`);

--
-- Índices para tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices para tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices para tabela `produto_opcao`
--
ALTER TABLE `produto_opcao`
  ADD PRIMARY KEY (`produto_id`,`opcao_id`),
  ADD KEY `opcao_id` (`opcao_id`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `variacao`
--
ALTER TABLE `variacao`
  ADD PRIMARY KEY (`id_variacao`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `opcao_variacao`
--
ALTER TABLE `opcao_variacao`
  MODIFY `id_opcao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `variacao`
--
ALTER TABLE `variacao`
  MODIFY `id_variacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `opcao_variacao`
--
ALTER TABLE `opcao_variacao`
  ADD CONSTRAINT `opcao_variacao_ibfk_1` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id_variacao`);

--
-- Limitadores para a tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Limitadores para a tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `pedido_itens_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_itens_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`);

--
-- Limitadores para a tabela `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_categoria`);

--
-- Limitadores para a tabela `produto_opcao`
--
ALTER TABLE `produto_opcao`
  ADD CONSTRAINT `produto_opcao_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id_produto`),
  ADD CONSTRAINT `produto_opcao_ibfk_2` FOREIGN KEY (`opcao_id`) REFERENCES `opcao_variacao` (`id_opcao`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
