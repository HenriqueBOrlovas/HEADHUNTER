<?php require __DIR__.'/../includes/bootstrap.php';need('candidato');head('Simulador de entrevista');?>
<h1>Simulador de entrevista com IA</h1><p class="mut">Escolha a área, responda como se estivesse falando com o recrutador e receba um retorno na hora. Cada resposta fica salva no seu painel.</p>
<div class="card" id="app" data-t="<?=e(csrf())?>" data-api="<?=e(BASE_URL)?>/api/ia.php">
<label for="area">Área da vaga</label><select id="area"><?=opts(AREAS)?></select><p><button class="btn p" id="ini" type="button">Começar</button></p>
<div id="box" hidden><div class="q" id="perg" aria-live="polite"></div>
<label for="resp">Sua resposta</label><textarea id="resp" placeholder="Escreva ou use o microfone…"></textarea>
<div class="row"><button class="btn p" id="env" type="button">Enviar resposta</button><button class="btn b" id="mic" type="button">🎤 Falar</button><button class="btn" id="prox" type="button">Outra pergunta</button></div></div>
<div id="res" hidden style="margin-top:18px"></div></div>
<script src="<?=e(BASE_URL)?>/assets/js/simulador.js"></script>
<?php foot();
