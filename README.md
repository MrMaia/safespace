<div align="center">
  <img src="assets/compiled/svg/logo.svg" alt="Safe Space" width="180"/>

  <h1>🛡️ Safe Space</h1>
  <p><strong>Painel de gestão de denúncias de assédio e bullying para instituições de ensino</strong></p>

  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white"/>
  <img src="https://img.shields.io/badge/status-arquivado-lightgrey?style=for-the-badge"/>
</div>

---

> 🎓 **Projeto de faculdade** (TCC), sem manutenção ativa. Publicado aqui como
> registro histórico do trabalho — o banco de dados original não existe mais.

---

## 🚀 Sobre o Projeto

**Safe Space** é um painel administrativo para instituições de ensino
registrarem, acompanharem e resolverem denúncias de assédio e bullying entre
alunos. Cada instituição tem seu próprio login e enxerga apenas as denúncias
que cadastrou, com anexos, nível de prioridade e status de andamento.

## ✨ O que foi implementado

- 🔐 **Login por instituição** com senha com hash (`password_hash`/`password_verify`)
- 📊 **Dashboard** com gráfico de casos por mês e contadores de casos em
  progresso / resolvidos (Chart.js/ApexCharts)
- 📝 **CRUD de denúncias** — vítima, acusado, curso, período, prioridade e
  anexos (upload de arquivos via FilePond)
- 🔄 **Fluxo de status** — abrir, editar, concluir ou cancelar uma denúncia
- 📚 **Cadastro de cursos** vinculados à instituição
- 📧 **Modelos de e-mail** editáveis (Summernote/TinyMCE) para notificações
- 🎨 **UI responsiva** com tema claro/escuro, baseada no template admin
  [Mazer](https://github.com/zj1092/mazer)

## 🛠️ Tecnologias

| Tecnologia | Uso |
|---|---|
| PHP 8 (PDO + mysqli) | Backend e regras de negócio |
| MySQL | Persistência de dados |
| Bootstrap 5 | Layout e componentes de UI |
| Chart.js / ApexCharts | Gráficos do dashboard |
| FilePond | Upload de anexos das denúncias |
| Summernote / TinyMCE | Editor rico dos modelos de e-mail |
| Composer (`vlucas/phpdotenv`, `phpmailer/phpmailer`) | Configuração via `.env` e envio de e-mail |

## 📁 Estrutura do Projeto

```
safespace/
├── config/                 # Conexão com o banco (lê credenciais do .env)
│   ├── db.php               # PDO — usado pela maioria das páginas
│   └── conexao.php          # mysqli — usado pelas rotinas mais antigas
├── telas/                  # Partials compartilhados (sidebar, footer)
├── assets/                  # CSS, JS, fontes e imagens do template
├── index.php                 # Login
├── dashboard.php              # Painel com estatísticas
├── cd_clientes.php / cd_cursos.php   # Listagem de denúncias / cursos
├── form_clientes.php / form_cursos.php  # Formulários de cadastro
├── editar_denuncia.php        # Edição de uma denúncia
├── ver_detalhes.php           # Detalhes de uma denúncia
├── editar_email_template.php  # Edição dos modelos de e-mail
├── processa_*.php / processar_*.php / atualiza_*.php  # Handlers de formulário
├── apagar_*.php / cancelar_denuncia.php / concluir_denuncia.php  # Ações de status
├── .env.example               # Modelo das variáveis de ambiente
└── composer.json
```

## ⚙️ Como rodar localmente

**Pré-requisitos:** PHP 8+, MySQL, [Composer](https://getcomposer.org).

```bash
composer install
cp .env.example .env    # preencha com as credenciais do seu MySQL local
```

Crie o banco `safespace` e as tabelas `instituicoes`, `usuarios`, `denuncias`
e `cursos` (o schema original não foi preservado — este repositório contém
apenas o código da aplicação).

```bash
php -S localhost:8000
```

Acesse [http://localhost:8000](http://localhost:8000).

> ⚠️ Sem um banco com essas tabelas populadas, o login e as demais telas não
> funcionam — o projeto está aqui como referência de código, não como app
> pronto para uso.

---

<div align="center">
  <p>Feito com ❤️ por <strong>Allan Maia</strong></p>
</div>
