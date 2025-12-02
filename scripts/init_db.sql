DROP DATABASE IF EXISTS tem_quase_tudo_db;

CREATE DATABASE IF NOT EXISTS tem_quase_tudo_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE tem_quase_tudo_db;

CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  telefone VARCHAR(20),
  endereco VARCHAR(255),
  cidade VARCHAR(100),
  estado VARCHAR(2),
  cep VARCHAR(10),
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  produto VARCHAR(255) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2) NOT NULL DEFAULT 0,
  desconto TINYINT NOT NULL DEFAULT 0,
  estoque INT NOT NULL DEFAULT 0,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX(produto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cliente_id` INT NOT NULL,
  `data_pedido` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pendente',
  `endereco_entrega` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_pedidos_cliente` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pedido_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `pedido_id` INT NOT NULL,
  `produto_id` INT NULL,
  `nome_produto` VARCHAR(255) NULL,
  `quantidade` INT NOT NULL DEFAULT 1,
  `preco_unitario` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  INDEX `idx_pedido_items_pedido` (`pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedidos_cliente`
    FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pedido_items`
  ADD CONSTRAINT `fk_items_pedido`
    FOREIGN KEY (`pedido_id`) REFERENCES `pedidos`(`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

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
('Faca que Não Corta', 'Ótima para quem não gosta de riscos… ou utilidade.', 7.70, 58, 0),
('Livro de Receitas Impossíveis', 'Requer ingredientes inexistentes.', 32.00, 21, 5),
('Moletom Autoconfortável', 'Ajusta o calor conforme seu nível de preguiça.', 66.90, 13, 0),
('Gato Mecânico que Não Mia', 'Emite sons aleatórios de vibração.', 120.00, 4, 15),
('Lanterna de Escuridão', 'Ao ligar, escurece tudo ao redor.', 44.40, 22, 0),
('Pilha Infinita que Não Funciona', 'Dura para sempre, mas não liga nada.', 9.99, 99, 0),
('Cajado de Erro de Sistema', 'Às vezes emite mensagens de bug aleatórias.', 150.75, 3, 10),
('Espelho que Não Reflete Você', 'Mostra apenas possibilidades alternativas.', 82.22, 7, 0),
('Ração para Criaturas Imaginárias', 'Sabor bacon metafísico.', 6.80, 60, 0),
('Colar que Brilha Só no Escuro Profundo', 'Precisa de escuridão absoluta para funcionar.', 12.50, 34, 2),
('Bússola Confusa', 'Aponta sempre para “talvez”.', 18.30, 45, 0),
('Capuz Anticuriosidade', 'Ninguém pergunta nada quando você usa.', 55.00, 15, 7),
('Aromatizador de Memórias', 'Cheiro de dias que talvez tenham acontecido.', 17.88, 39, 0),
('Copo que Transborda Quando Cheio', 'Funciona exatamente como um copo comum.', 4.20, 150, 0),
('Martelo Suave', 'Feito de espuma. Zero impacto, muito estilo.', 13.13, 52, 0),
('Colher Telepática', 'Sabe o que você quer comer, mas não ajuda.', 10.99, 80, 0),
('Cinto do Destino Aleatório', 'Ajusta-se sozinho sem critério algum.', 27.45, 26, 5),
('Bolsa com Eco Interno', 'Repete tudo que você coloca dentro.', 60.60, 11, 0),
('Tinta Invisível Barata', 'Praticamente água, mas com etiqueta estilosa.', 3.33, 120, 0);