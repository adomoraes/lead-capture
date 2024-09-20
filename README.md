# Lead Capture Plugin

## Descrição

Plugin para captação de leads no WordPress. Permite que visitantes do site submetam seus e-mails através de um formulário exibido via widget ou shortcode. Os e-mails capturados são armazenados no banco de dados e podem ser exportados pelo administrador em formato CSV.

## Funcionalidades

- Formulário de captura de e-mails exibido via widget ou shortcode.
- Armazenamento seguro dos e-mails capturados no banco de dados do WordPress.
- Visualização dos leads capturados na área administrativa.
- Exportação dos e-mails para arquivo CSV.
- Submissão do formulário via AJAX com mensagens de feedback dinâmicas.

## Instalação

1. Baixe o plugin e extraia o conteúdo.
2. Faça o upload da pasta `lead-capture` para o diretório `wp-content/plugins/` do seu site WordPress.
3. Ative o plugin no painel de administração do WordPress em **Plugins > Plugins Instalados**.

## Uso

### Widget

1. Vá até **Aparência > Widgets**.
2. Adicione o widget `Lead Capture Form` ao rodapé ou a outra área de widget desejada.

### Shortcode

1. Insira o shortcode `[capture_lead_form]` em qualquer página ou post onde deseja exibir o formulário de captura.

## Exportação dos Leads

1. Acesse o painel de administração e clique em **Leads**.
2. Clique no botão **Exportar CSV** para baixar a lista de e-mails capturados.

## Desenvolvimento

### Estrutura do Plugin

- `lead-capture.php`: Arquivo principal do plugin.
- `includes/`: Contém arquivos auxiliares do plugin.
  - `form-handler.php`: Lida com o envio e validação do formulário.
  - `admin-page.php`: Gera a interface de administração.
  - `export.php`: Função para exportação dos e-mails para CSV.
- `assets/`: Arquivos CSS e JS do plugin.
  - `style.css`: Estilos do formulário.
  - `script.js`: Script para envio do formulário via AJAX.

### Hooks e Filtros

- `admin_post_nopriv_submit_lead_ajax` e `admin_post_submit_lead_ajax`: Manipuladores para submissão do formulário via AJAX.

### Banco de Dados

O plugin cria a tabela `wp_lead_capture` no banco de dados, com as seguintes colunas:

- `id`: Identificador único do lead.
- `email`: E-mail capturado.
- `created_at`: Data de submissão do e-mail.

## Contribuição

Sinta-se à vontade para contribuir com melhorias ou correções. Para sugestões, crie um pull request ou abra uma issue.

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE).
