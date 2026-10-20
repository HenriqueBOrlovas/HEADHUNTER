<?php require 'includes/bootstrap.php';head('Início');
$n=$pdo->query("SELECT (SELECT COUNT(*) FROM vagas WHERE ativa=1) v,(SELECT COUNT(*) FROM candidatos) c,(SELECT COUNT(*) FROM entrevistas) e")->fetch();?>
<section class="hero"><div><h1>Seu primeiro emprego começa com uma <em>boa entrevista.</em></h1>
<p class="mut">Monte seu currículo, treine com IA e deixe empresas conhecerem você. Tudo em um só lugar, pensado para quem está começando.</p>
<div class="row"><a class="btn p" href="register.php">Sou candidato</a><a class="btn b" href="register.php?tipo=empresa">Sou empresa</a><a class="btn" href="vagas.php">Ver vagas</a></div>
<p class="mut"><?=e($n['v'])?> vagas abertas · <?=e($n['c'])?> candidatos · <?=e($n['e'])?> entrevistas treinadas</p></div>
<img src="assets/img/logo.png" alt="Logo HEADHUNTER: jovem apontando para você"></section>
<div class="grid">
<div class="card"><h3>Currículo completo</h3><p class="mut">Perfil, experiências, habilidades e idiomas, com indicador de preenchimento.</p></div>
<div class="card"><h3>Simulador com IA</h3><p class="mut">Perguntas de recrutador, nota por critério e dicas para melhorar a resposta.</p></div>
<div class="card"><h3>Entrevistas compartilhadas</h3><p class="mut">Você escolhe quais treinos as empresas podem ver. Seu controle, sempre.</p></div>
<div class="card"><h3>Filtro avançado</h3><p class="mut">Empresas filtram talentos por área, local, escolaridade, habilidades, idiomas e nota.</p></div></div>
<?php foot();
