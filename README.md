# HEADHUNTER
Plataforma (PHP 8 + MySQL) para jovens treinarem entrevistas com IA, montarem currículo e serem encontrados por empresas.

## Como rodar
1. Instale XAMPP/Laragon (PHP 8.1+, MySQL, extensões pdo_mysql, curl, mbstring).
2. Copie a pasta `headhunter` para `htdocs` e importe `database.sql` (phpMyAdmin ou `mysql < database.sql`).
3. Ajuste `config.php` (usuário/senha do banco e `BASE_URL`).
4. Abra http://localhost/headhunter
5. IA real (opcional): defina `ANTHROPIC_API_KEY` no ambiente. Sem chave, o simulador usa análise automática por regras.

## Estrutura
- `index.php`, `login.php`, `register.php`, `vagas.php`, `vaga.php`: área pública
- `candidato/`: painel, currículo e simulador | `empresa/`: painel, nova vaga e busca de talentos (filtro avançado)
- `api/ia.php`: perguntas e avaliação | `includes/`: bootstrap (sessão, PDO, CSRF) e `ia.php`

## Segurança e LGPD
Senhas com `password_hash`, consultas preparadas, proteção CSRF e escape de saída. Candidatos escolhem se o perfil é público e quais entrevistas compartilhar; contatos só aparecem para empresas às quais a pessoa se candidatou. Para menores de 18 anos, obtenha consentimento do responsável antes de ir para produção.
