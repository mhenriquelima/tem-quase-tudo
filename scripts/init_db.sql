CREATE DATABASE IF NOT EXISTS tem_quase_tudo_db;
USE tem_quase_tudo_db;

-- TABELA DE PRODUTOS
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `produto` VARCHAR(100) NOT NULL,
  `descricao` TEXT,
  `preco` DECIMAL(10,2) NOT NULL,
  `estoque` INT NOT NULL DEFAULT 0,
  `desconto` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TABELA DE CLIENTES
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `senha` VARCHAR(100) NOT NULL,
  `telefone` VARCHAR(50),
  `endereco` VARCHAR(255),
  `cidade` VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TABELA DE CONTATOS
CREATE TABLE IF NOT EXISTS `contatos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `telefone` VARCHAR(50),
  `assunto` VARCHAR(255) NOT NULL,
  `mensagem` LONGTEXT NOT NULL,
  `ip_cliente` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `status` ENUM('novo', 'respondido', 'arquivado') DEFAULT 'novo',
  `lido` BOOLEAN DEFAULT FALSE,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_status (status),
  INDEX idx_criado_em (criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- INSERIR PRODUTOS (NÃO DUPLICA)
INSERT INTO produtos (produto, descricao, preco, estoque, desconto)
VALUES
('Relógio que Corre ao Contrário', 'Marca o tempo no sentido inverso, ideal para quem vive de nostalgia.', 47.22, 12, 0),
('Lâmpada Antissombra', 'Emite luz que não ilumina, mas espanta sombras tímidas.', 33.90, 25, 5),
('Pente para Cabelos Invisíveis', 'Funciona apenas em penteados imaginários.', 9.10, 80, 0),
('Caneca Autopensante', 'Cria pensamentos profundos enquanto você bebe café.', 21.77, 40, 2),
('Chave que Abre Nada', 'Perfeitamente inútil, mas tem um charme misterioso.', 6.66, 60, 0),
('Sapatilhas Antigravitacionais Falhas', 'Levitam por 0.3 segundos de forma imprevisível.', 129.99, 8, 12),
('Livro de Páginas em Branco que Conta História', 'Quanto mais ignorado, mais a história se desenvolve.', 58.12, 13, 5),
('Pote de Gargalhadas Engarrafadas', 'Ao abrir, solta risadas aleatórias de desconhecidos.', 15.00, 33, 0),
('Régua Curvada', 'Medir linhas retas nunca foi tão difícil.', 3.99, 102, 0),
('Óculos para Olhar o Passado Distante', 'Funciona apenas para lembrar coisas vergonhosas.', 71.40, 5, 10),
('Fone de Ouvido que Só Toca Silêncio', 'Alta fidelidade de nada.', 19.55, 44, 0),
('Caderno que Se Escreve Sozinho', 'Anota só coisas irrelevantes.', 27.89, 10, 8),
('Sabonete de Névoa', 'Limpa? Ninguém sabe. Dissipa-se ao toque.', 8.27, 70, 0),
('Moeda que Cai Sempre em Pé', 'Extremamente frustrante para apostas.', 12.75, 23, 0),
('Chá de Relaxamento Instantâneo', 'Relaxa tanto que você esquece por que tomou.', 18.90, 55, 3),
('Pedra Filosofal de Plástico', 'Não transforma nada em ouro, mas brilha legal.', 4.44, 200, 0),
('Vassoura Autodidata', 'Aprende a varrer… eventualmente.', 99.90, 7, 12),
('Guarda-Chuva que Chove Por Dentro', 'Uma experiência única de molhar-se.', 34.10, 27, 0),
('Meias que Sempre Somem', 'Cada par vem com só uma meia por garantia.', 11.23, 61, 0),
('Relatório de Nada', 'Documento oficial que prova absolutamente nada.', 2.00, 140, 0),
('Panela que Não Esquenta', 'Indestrutível e completamente inútil para cozinhar.', 75.33, 12, 5),
('Mouse com Vontade Própria', 'Move o cursor quando quer, não quando você manda.', 44.77, 19, 0),
('Post-it Eterno', 'Gruda demais. Ninguém conseguiu tirar até hoje.', 5.50, 100, 0),
('Camiseta Mutável', 'Muda a estampa conforme seu humor… ou azar.', 49.99, 16, 9),
('Tênis que Acelera Só Parado', 'Faz você se sentir rápido enquanto não anda.', 89.10, 9, 0),
('Ampulheta Horizontal', 'Marca o tempo errado, mas é divertida de ver.', 14.20, 47, 0),
('Garrafa de Ar do Futuro', 'Aroma de esperança e leve cheiro de metal.', 23.00, 30, 0),
('Rádio que Captura Pensamentos Soltos', 'Toca músicas que você quase lembra.', 63.12, 6, 10),
('Cubo Perfeitamente Inútil', 'Objeto cúbico. Nada mais a declarar.', 1.99, 300, 0),
('Kit de Mini Tormentas', 'Nuvens pequenas que fazem chuvinhas de 3 segundos.', 39.50, 12, 0),
('Faca que Não Corta', 'Ótima para quem não gosta de risco.', 16.75, 45, 0),
('Espelho que Mostra o Reflexo Errado', 'Veja-se como um alienígena!', 29.99, 22, 0),
('Cinto que Aperta Sozinho', 'Ideal para quem quer perder peso sem esforço.', 54.60, 14, 7),
('Lápis que Apaga Só o que Você Não Quer', 'Perfeito para confusões criativas.', 3.33, 88, 0),
('Relógio de Sol Portátil', 'Funciona melhor em dias nublados.', 22.45, 18, 0),
('Bola de Cristal Quebrada', 'Ainda dá para ver o futuro… com algumas distorções.', 45.00, 11, 0);

-- TABELA DE PEDIDOS
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id` INT NOT NULL,
  `data_pedido` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(50) DEFAULT 'Pendente',
  `total` DECIMAL(10,2) DEFAULT 0,
  `atualizado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
  INDEX idx_cliente_id (cliente_id),
  INDEX idx_data_pedido (data_pedido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- TABELA DE ITENS DO PEDIDO
CREATE TABLE IF NOT EXISTS `pedidos_item` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pedido_id` INT NOT NULL,
  `produto_id` INT NOT NULL,
  `quantidade` INT NOT NULL,
  `preco_unitario` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
  FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
  INDEX idx_pedido_id (pedido_id),
  INDEX idx_produto_id (produto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;