CREATE DATABASE IF NOT EXISTS fanbeads;
USE fanbeads;

CREATE TABLE produto (
  id_produto   INT AUTO_INCREMENT PRIMARY KEY,
  nome         VARCHAR(255) NOT NULL,
  descricao    TEXT,
  preco        DECIMAL(10,2) NOT NULL,
  imagem       VARCHAR(255),
  categoria    VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE variacao (
  id_variacao  INT AUTO_INCREMENT PRIMARY KEY,
  nome         VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE opcao_variacao (
  id_opcao     INT AUTO_INCREMENT PRIMARY KEY,
  variacao_id  INT NOT NULL,
  valor        VARCHAR(50) NOT NULL,
  FOREIGN KEY (variacao_id) REFERENCES variacao(id_variacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE produto_opcao (
  produto_id   INT NOT NULL,
  opcao_id     INT NOT NULL,
  PRIMARY KEY(produto_id, opcao_id),
  FOREIGN KEY (produto_id) REFERENCES produto(id_produto),
  FOREIGN KEY (opcao_id)    REFERENCES opcao_variacao(id_opcao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE usuario (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  email      VARCHAR(100) NOT NULL UNIQUE,
  senha      VARCHAR(255) NOT NULL,
  role       ENUM('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9) Admin inicial (senha: admin123)
INSERT INTO usuario (username, email, senha, role) VALUES
  ('admin','admin@fanbeads.com',
   '$2b$12$uB2OeUuDvnn04Haq/lgRjeY4tgnpCgRUT9vtJZ92z.9.7ZekPjUCK',
   'admin');
