# Plataforma de Chamados de TI

## Descrição

A **Plataforma de Chamados de TI** é um sistema desenvolvido para a Prefeitura, permitindo que funcionários registrem problemas técnicos, sugestões ou incidentes. O objetivo é facilitar a comunicação entre a equipe de TI e os colaboradores municipais, promovendo uma gestão eficiente dos chamados.

## Tecnologias Utilizadas

- **Frontend:** Bootstrap, jQuery
- **Backend:** PHP 8
- **Banco de Dados:** MySQL
- **Controle de Versão:** Git

## Funcionalidades

1. **Página Inicial:**
   - Informações gerais sobre o sistema.
   - Opções de login e cadastro.

2. **Cadastro e Login:**
   - Formulário de cadastro com validações rigorosas:
     - Campos obrigatórios: nome completo, data de nascimento, e-mail, telefone, WhatsApp, senha, confirmação da senha, cidade e estado.
     - Validações: idade mínima de 18 anos, e-mail válido e máscaras para telefone e WhatsApp.
     - Carregamento dinâmico das cidades com base no estado selecionado.
     - Proteção contra SQL Injection.
     - Envio de código de validação por e-mail.
     - Login com criação de sessão em PHP.

3. **Abertura de Chamado:**
   - Disponível somente após o login.
   - Formulário de registro com:
     - Campos obrigatórios: descrição do problema (com suporte a texto formatado usando Summernote), tipo de incidente, anexos e contatos telefônicos.
     - Anexos armazenados em base64 no banco de dados.
     - Validação do formulário com jQuery antes da submissão.

4. **Pós-Abertura de Chamado:**
   - Possibilidade de adicionar anexos após a abertura.
   - Histórico do chamado exibindo uma timeline de atualizações.
   - Visualização do histórico e adição de novas descrições.

5. **Listagem de Chamados:**
   - Visualização dos chamados abertos pelo usuário logado, incluindo histórico e anexos.

6. **Segurança:**
   - Proteção contra vulnerabilidades como SQL Injection e XSS.
   - Senhas armazenadas de forma segura.

7. **Documentação e Código:**
   - Código bem organizado e comentado.
   - Documentação clara do projeto.

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/CarlosSchefferr/plataforma-chamados.git



## Capturas de Tela

Abaixo estão as imagens das principais páginas da aplicação:

### Página Inicial
![image](https://github.com/user-attachments/assets/193362dc-3f43-4bdd-9f18-f467010392e4)
*Informações gerais sobre o sistema e opções de login/cadastro.*

### Registro
![image](https://github.com/user-attachments/assets/d2f40ac7-affe-4db3-94eb-3905f57dcd89)

*Formulário de registro com validações e campos obrigatórios.*

### Login
![image](https://github.com/user-attachments/assets/a95c212e-747d-4a9a-8017-e26114848942)
*Formulário de login para acessar o sistema.*

### Dashboard
![image](https://github.com/user-attachments/assets/5c96e98d-556f-4f01-97e4-ce023b7e4888)
*Visão geral dos chamados abertos pelo usuário.*

### Criar Chamado
![image](https://github.com/user-attachments/assets/22dc998d-cd39-416a-9d7a-dd2d54a10c13)
*Formulário para abertura de um novo chamado, incluindo anexos e contatos.*

### Ver Histórico
![image](https://github.com/user-attachments/assets/18aef76f-d9df-41cf-bebd-43c06fc3288b)
*Histórico de chamados com detalhes e timeline de atualizações.*

### Detalhes do Chamado
![image](https://github.com/user-attachments/assets/57351f4a-6067-40a4-8e21-b7e52a5f6476)
*Visualização detalhada do chamado, incluindo histórico e anexos adicionais.*

### Editar Chamado
![image](https://github.com/user-attachments/assets/57351f4a-6067-40a4-8e21-b7e52a5f6476)
*Tela para edição do seu chamado.*


### Código de Verificação
![image](https://github.com/user-attachments/assets/ee306856-88d6-4e8c-9d07-48b16d6015b2)
*Tela com o código de validação enviado por e-mail para o usuário.*

### E-mail
![image](https://github.com/user-attachments/assets/93d39bff-10b4-40a2-867a-cac55eba0867)
*Exemplo de e-mail de confirmação enviado ao usuário.*



