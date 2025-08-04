<div align="center">

# FanBeads E-commerce 🛍️

**Projeto de Trabalho de Conclusão de Curso – Fatec Jaú**

</div>

<div align="center">
    <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php" alt="PHP 8.2+">
    <img src="https://img.shields.io/badge/MySQL-4.0.0-4479A1?style=for-the-badge&logo=mysql" alt="MySQL">
    <img src="https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript" alt="JavaScript ES6">
    <img src="https://img.shields.io/badge/Arquitetura-MVC-blue?style=for-the-badge" alt="Arquitetura MVC">
</div>

---

## 📄 Resumo

> Este projeto aborda o desenvolvimento de uma loja virtual, a "FanBeads", especializada na comercialização de pulseiras de miçangas artesanais e personalizadas para fãs de diversos fandoms. Inserido no contexto da crescente busca por acessórios que permitem versatilidade e personalização, o sistema busca preencher uma lacuna de mercado onde fãs encontram dificuldade para adquirir produtos que representem seus interesses. A metodologia envolve o desenvolvimento prático com tecnologias como PHP, MySQL, HTML, CSS e JavaScript, utilizando os padrões de projeto MVC, Singleton e Composite para garantir a escalabilidade e a sustentabilidade do sistema.

---

### ❓ Problematização

Muitos fãs enfrentam dificuldades em encontrar acessórios específicos relacionados aos seus fandoms favoritos, seja pela falta de opções ou pela pouca diversidade disponível no mercado. O problema central que este projeto busca resolver é: **como criar uma plataforma online eficiente e atrativa que atenda à demanda específica de fãs por pulseiras personalizadas, garantindo uma experiência de compra intuitiva, ágil e agradável?**

### 🎯 Objetivos do Projeto

#### Objetivo Geral
* Desenvolver uma plataforma de comércio eletrônico eficiente utilizando o padrão arquitetural MVC (Model-View-Controller) para comercializar pulseiras personalizadas voltadas a diferentes fandoms.

#### Objetivos Específicos
* Implementar uma arquitetura orientada a objetos utilizando os padrões de projeto Singleton (para conexão com o banco de dados) e Composite (para a construção de interfaces).
* Proporcionar uma experiência de usuário intuitiva e agradável com uma interface moderna e responsiva.
* Garantir a sustentabilidade e a escalabilidade do sistema através de boas práticas de desenvolvimento.

---

## ✨ Funcionalidades Principais

### 👤 Módulo do Cliente
* **Autenticação:** Cadastro e Login de usuários com controle de sessão.
* **Navegação:** Listagem de produtos com opção de personalização de pulseiras.
* **Carrinho de Compras:** Adição de itens ao carrinho com armazenamento local.
* **Finalização de Pedido:** Simulação da finalização do pedido.

### 🔑 Módulo Administrativo
* **Gerenciamento de Produtos:** Área administrativa para cadastro e edição de produtos.
* **Controle de Estoque:** Funcionalidade básica para controle de estoque.

---

## 🛠️ Tecnologias e Arquitetura

- **Backend:** **PHP 8.2+** (Orientado a Objetos)
- **Frontend:** **HTML5**, **CSS3**, **JavaScript (ES6+)**
- **Banco de Dados:** **MySQL / MariaDB**
- **Arquitetura e Padrões de Projeto:**
    - **MVC (Model-View-Controller):** Utilizado para separar a lógica da aplicação em três camadas interconectadas: dados (Model), apresentação (View) e controle (Controller).
    - **Singleton:** Aplicado na classe de conexão com o banco de dados para garantir que exista apenas uma única instância de conexão, otimizando recursos.
    - **Composite:** Usado para permitir a construção de interfaces HTML complexas tratando componentes individuais e agrupamentos de forma uniforme.

---

## 🚀 Instalação e Execução Local

Siga os passos abaixo para configurar o ambiente e executar o projeto.

### Pré-requisitos
- Ambiente de desenvolvimento local (XAMPP, WampServer, Laragon).
- Git (opcional, para clonar o repositório).

### Passos

1.  **Clone o Repositório:**
    ```bash
    git clone [URL_DO_SEU_REPOSITORIO] fanbeads
    ```
    Ou baixe o ZIP e extraia para a pasta `htdocs` ou `www`.

2.  **Banco de Dados:**
    - Crie um novo banco de dados no seu SGBD chamado `fanbeads`.
    - Importe o arquivo `database.sql` (você deve criar este arquivo exportando a estrutura e os dados do seu banco local).

3.  **Configuração da Conexão:**
    - Verifique se as credenciais no arquivo `Models/Conexao.class.php` correspondem às do seu ambiente local.

4.  **Servidor Apache e `.htaccess`:**
    - Certifique-se de que o módulo `mod_rewrite` do Apache está ativado.
    - O arquivo `.htaccess` na raiz do projeto deve conter:
      ```apacheconf
      RewriteEngine On
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteRule ^ index.php [QSA,L]
      ```

5.  **Acesse:**
    - Inicie seu servidor Apache e MySQL.
    - Abra o navegador e acesse `http://localhost/fanbeads/`.

---

## 👨‍🎓 Autora

Desenvolvido por **Ana Laura de Marins** como Trabalho de Conclusão de Curso (TCC) para o curso de **Sistemas para Internet** na **Fatec Jaú**.

**Ano:** 2025
