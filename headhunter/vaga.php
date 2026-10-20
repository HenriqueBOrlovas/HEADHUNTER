<?php require 'includes/bootstrap.php';$u=user();$id=(int)($_GET['id']??0);
$s=$pdo->prepare('SELECT v.*,e.nome_fantasia,e.sobre FROM vagas v JOIN empresas e ON e.usuario_id=v.empresa_id WHERE v.id=?');$s->execute([$id]);$v=$s->fetch();
if(!$v){http_response_code(404);head('Vaga');echo '<p>Vaga não encontrada.</p>';foot();exit;}
$ja=false;
if($u&&$u['tipo']==='candidato'){$x=$pdo->prepare('SELECT 1 FROM candidaturas WHERE vaga_id=? AND candidato_id=?');$x->execute([$id,$u['id']]);$ja=(bool)$x->fetch();
 if($_SERVER['REQUEST_METHOD']==='POST'&&!$ja&&$v['ativa']){$pdo->prepare('INSERT INTO candidaturas(vaga_id,candidato_id) VALUES(?,?)')->execute([$id,$u['id']]);flash('Candidatura enviada! Acompanhe pelo painel.');redirect('/vaga.php?id='.$id);}}
head($v['titulo']);?>
<div class="card stack"><h1><?=e($v['titulo'])?></h1><div class="mut"><?=e($v['nome_fantasia'])?> · <?=e($v['cidade'])?> · <?=e(money($v['salario']))?></div>
<div><span class="tag"><?=e(NIVEIS[$v['nivel']])?></span><span class="tag"><?=e(MODAL[$v['modalidade']])?></span><span class="tag p"><?=e($v['area'])?></span></div>
<h3>Sobre a vaga</h3><p><?=nl2br(e($v['descricao']))?></p><h3>Requisitos</h3><p><?=nl2br(e($v['requisitos']))?></p>
<?php if(!$u):?><a class="btn p" href="login.php">Entre para se candidatar</a>
<?php elseif($u['tipo']==='candidato'):if($ja):?><span class="tag p">Você já se candidatou</span>
<?php elseif(!$v['ativa']):?><span class="tag">Vaga encerrada</span>
<?php else:?><form method="post"><?=csrf_field()?><button class="btn p">Candidatar-me</button> <a href="candidato/simulador.php">Treinar entrevista antes</a></form><?php endif;endif;?></div>
<?php foot();
