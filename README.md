# Engenharia Social — Demonstração Educacional com SEToolkit

Este projeto foi desenvolvido como uma **prática** para uma palestra/apresentação sobre **cibersegurança / engenharia social**, com foco em **educar e conscientizar o público** sobre os riscos e mecanismos por trás de ataques digitais.
> **Atenção:**
> Este projeto tem **propósito exclusivamente educacional e demonstrativo**.
> Ele **não deve ser utilizado fora de um ambiente controlado e com consentimento dos participantes**.

---

## Estrutura do Projeto

```
├── preview/
    ├── converter.sh              # Script que converte o XML do SEToolkit em JSON
    ├── dados/
    │   ├── json/                 # Saída em JSON
    │   └── xml/                  # Cópia do XML gerado pelo SEToolkit
    ├── imports/                  # Arquivos de apoio (configurações, funções, CSS)
    ├── index.php                 # Página principal de visualização dos dados
    ├── mapeamento.conf           # Arquivo de mapeamento entre XML e campos JSON
    ├── php/Dockerfile            # Dockerfile do ambiente PHP
    └── sucesso.php               # Página de feedback/sucesso
├── docker-compose.yaml           # Contém as especificações do container
└── index.html                    # Página falsa para a realização do cadastro

```

---

## Funcionamento Geral

O sistema é composto por duas partes principais:

1. **Coleta (via SEToolkit):**
   Após a execução de um ataque controlado com o **SEToolkit**, um relatório em formato `.xml` é gerado em:

   ```
   /root/.set/reports/
   ```

2. **Conversão e Visualização:**

   * O script `converter.sh` busca o XML mais recente nesse diretório e converte em JSON.
   * O JSON gerado é utilizado pela aplicação PHP para exibir uma **tabela** com as informações capturadas.

   A aplicação permite:

   * Escolher quais colunas exibir
   * Censurar campos sensíveis (como e-mail e CPF)

---

## Dependências e Execução

### Pré-requisitos

* **Docker** e **Docker Compose** instalados
* **Permissões de root** para acessar `/root/.set/reports` (onde o SEToolkit salva os relatórios)

---

### Como executar o projeto

1. **Clone este repositório**

   ```bash
   git clone https://github.com/gustavogordoni/setoolkit-event-suap.git
   cd setoolkit-event-suap
   ```

2. **Execute o script de conversão após a coleta**

   > Este passo deve ser feito **depois** da execução da engenharia social no SEToolkit.

   ```bash
   chmod u+x ./preview/converter.sh
   ./preview/converter.sh
   ```

   O script irá:

   * Buscar o XML mais recente gerado pelo SEToolkit
   * Copiar para `dados/xml/input.xml`
   * Gerar um `dados/json/dados.json` pronto para visualização

3. **Suba o ambiente Docker**

   ```bash
   docker compose up -d
   ```

   A aplicação estará disponível em:

   ```
   http://localhost:8080
   ```

## Aviso Legal

Este projeto foi desenvolvido **exclusivamente para fins educacionais**.
O uso indevido — fora de contextos controlados, consentidos e acadêmicos — pode configurar **crime**.

> Use com responsabilidade.
