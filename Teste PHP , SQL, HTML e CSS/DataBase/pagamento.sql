-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Out-2023 às 20:58
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pagamento`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_conta_pagar`
--

CREATE TABLE `tbl_conta_pagar` (
  `id_conta_pagar` int(11) NOT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `data_pagar` date DEFAULT NULL,
  `pago` tinyint(3) UNSIGNED DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_conta_pagar`
--

INSERT INTO `tbl_conta_pagar` (`id_conta_pagar`, `valor`, `data_pagar`, `pago`, `id_empresa`) VALUES
(1, '134.00', '2023-10-10', 1, 1),
(2, '190.00', '2023-10-04', 1, 2),
(3, '78.00', '2023-08-05', 1, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_empresa`
--

CREATE TABLE `tbl_empresa` (
  `id_empresa` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_empresa`
--

INSERT INTO `tbl_empresa` (`id_empresa`, `nome`) VALUES
(1, 'Empresa A'),
(2, 'Empresa B'),
(3, 'Empresa C');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tbl_conta_pagar`
--
ALTER TABLE `tbl_conta_pagar`
  ADD PRIMARY KEY (`id_conta_pagar`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Índices para tabela `tbl_empresa`
--
ALTER TABLE `tbl_empresa`
  ADD PRIMARY KEY (`id_empresa`);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tbl_conta_pagar`
--
ALTER TABLE `tbl_conta_pagar`
  ADD CONSTRAINT `tbl_conta_pagar_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `tbl_empresa` (`id_empresa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
