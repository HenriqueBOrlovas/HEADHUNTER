<?php
define('DB_HOST','localhost'); define('DB_NAME','headhunter'); define('DB_USER','root'); define('DB_PASS','root');
define('BASE_URL','/headhunter'); // pasta do projeto no servidor ('' se estiver na raiz)
// Para usar IA real (Claude), defina a variável de ambiente ANTHROPIC_API_KEY ou cole a chave abaixo.
define('ANTHROPIC_API_KEY', getenv('ANTHROPIC_API_KEY') ?: '');
define('ANTHROPIC_MODEL','claude-sonnet-5-5');
