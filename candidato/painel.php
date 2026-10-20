<?php require __DIR__.'/../includes/bootstrap.php';$u=need('candidato');$id=$u['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){$pdo->prepare('UPDATE entrevistas SET compartilhada=1-compartilhada WHERE id=? AND candidato_id=?')->execute([(int)$_POST['id'],$id]);redirect('/candidato/painel.php');}
$ca=$pdo->prepare('SELECT ca.status,ca.criada_em,v.id vid,v.titulo,e.nome_fantasia FROM candidaturas ca JOIN vagas v ON v.id=ca.vaga_id JOIN empresas e ON e.usuario_id=v.empresa_id WHERE ca.candidato_id=? ORDER BY ca.id DESC');$ca->execute([$id]);$ca=$ca->fetchAll();
$en=$pdo->prepare('SELECT * FROM entrevistas WHERE candidato_id=? ORDER BY id DESC LIMIT 20');$en->execute([$id]);$en=$en->fetchAll();
$med=$en?(int)round(array_sum(array_column($en,'nota'))/count($en)):0;head('Painel');?>
<h1>Olá, <?=e(explode(' ',$u['nome'])[0])?>!</h1>
<div class="grid"><div class="card"><div class="nota"><?=count($ca)?></div>candidaturas</div><div class="card"><div class="nota"><?=count($en)?></div>entrevistas treinadas</div>
<div class="card"><div class="nota"><?=$med?></div>nota média</div></div>
<p class="row"><a class="btn p" href="simulador.php">Treinar entrevista</a><a class="btn b" href="curriculo.php">Editar currículo</a><a class="btn" href="../vagas.php">Buscar vagas</a></p>
<h2>Minhas candidaturas</h2><div class="card"><table><tr><th>Vaga</th><th>Empresa</th><th>Status</th></tr>
<?php foreach($ca as $x)echo '<tr><td><a href="../vaga.php?id='.$x['vid'].'">'.e($x['titulo']).'</a></td><td>'.e($x['nome_fantasia']).'</td><td><span class="tag p">'.e(STATUS[$x['status']]).'</span></td></tr>';
if(!$ca)echo '<tr><td colspan="3" class="mut">Você ainda não se candidatou. Veja as vagas abertas!</td></tr>';?></table></div>
<h2 style="margin-top:26px">Meus treinos</h2><p class="mut">Compartilhe os melhores com as empresas. Só o que você marcar fica visível.</p>
<?php foreach($en as $x):?><details class="card" style="margin-bottom:10px"><summary><strong>Nota <?=$x['nota']?></strong> · <?=e($x['pergunta'])?> <?=$x['compartilhada']?'<span class="tag p">Compartilhada</span>':''?></summary>
<p><?=nl2br(e($x['resposta']))?></p><form method="post"><?=csrf_field()?><input type="hidden" name="id" value="<?=$x['id']?>"><button class="btn sm <?=$x['compartilhada']?'b':'p'?>"><?=$x['compartilhada']?'Parar de compartilhar':'Compartilhar com empresas'?></button></form></details><?php endforeach;?>
<?php foot();
