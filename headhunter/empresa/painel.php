<?php require __DIR__.'/../includes/bootstrap.php';$u=need('empresa');$id=$u['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){$a=$_POST['acao']??'';
 if($a==='perfil')$pdo->prepare('UPDATE empresas SET nome_fantasia=?,setor=?,cidade=?,sobre=? WHERE usuario_id=?')->execute([trim($_POST['nome']),trim($_POST['setor']),trim($_POST['cidade']),trim($_POST['sobre']),$id]);
 if($a==='toggle')$pdo->prepare('UPDATE vagas SET ativa=1-ativa WHERE id=? AND empresa_id=?')->execute([(int)$_POST['id'],$id]);
 if($a==='status'&&isset(STATUS[$_POST['status']]))$pdo->prepare('UPDATE candidaturas ca JOIN vagas v ON v.id=ca.vaga_id SET ca.status=? WHERE ca.id=? AND v.empresa_id=?')->execute([$_POST['status'],(int)$_POST['id'],$id]);
 flash('Atualizado.');redirect('/empresa/painel.php'.(!empty($_POST['vaga'])?'?vaga='.(int)$_POST['vaga']:''));}
$vg=$pdo->prepare('SELECT v.*,(SELECT COUNT(*) FROM candidaturas WHERE vaga_id=v.id) n FROM vagas v WHERE empresa_id=? ORDER BY id DESC');$vg->execute([$id]);$vg=$vg->fetchAll();
$emp=$pdo->prepare('SELECT * FROM empresas WHERE usuario_id=?');$emp->execute([$id]);$emp=$emp->fetch();
$sel=(int)($_GET['vaga']??0);$cands=[];
if($sel){$q=$pdo->prepare("SELECT ca.id,ca.status,usr.nome,usr.email,c.cidade,c.estado,c.area,c.escolaridade,c.habilidades,
 (SELECT ROUND(AVG(nota)) FROM entrevistas en WHERE en.candidato_id=ca.candidato_id AND en.compartilhada=1) media
 FROM candidaturas ca JOIN vagas v ON v.id=ca.vaga_id JOIN usuarios usr ON usr.id=ca.candidato_id JOIN candidatos c ON c.usuario_id=ca.candidato_id
 WHERE ca.vaga_id=? AND v.empresa_id=? ORDER BY media DESC");$q->execute([$sel,$id]);$cands=$q->fetchAll();}
head('Painel da empresa');?>
<h1>Painel da empresa</h1><p><a class="btn p" href="vaga_nova.php">Nova vaga</a> <a class="btn b" href="talentos.php">Buscar talentos</a></p>
<div class="card"><table><tr><th>Vaga</th><th>Candidatos</th><th>Situação</th><th></th></tr>
<?php foreach($vg as $v):?><tr><td><a href="?vaga=<?=$v['id']?>"><?=e($v['titulo'])?></a></td><td><?=$v['n']?></td><td><?=$v['ativa']?'Aberta':'Encerrada'?></td>
<td><form method="post"><?=csrf_field()?><input type="hidden" name="acao" value="toggle"><input type="hidden" name="id" value="<?=$v['id']?>"><button class="btn b sm"><?=$v['ativa']?'Encerrar':'Reabrir'?></button></form></td></tr><?php endforeach;
if(!$vg)echo '<tr><td colspan="4" class="mut">Nenhuma vaga ainda. Publique a primeira!</td></tr>';?></table></div>
<?php if($sel):?><h2 style="margin-top:24px">Candidatos da vaga</h2><div class="card"><table><tr><th>Candidato</th><th>Perfil</th><th>Nota (treinos)</th><th>Status</th></tr>
<?php foreach($cands as $c):?><tr><td><strong><?=e($c['nome'])?></strong><br><span class="mut"><?=e($c['email'])?></span></td>
<td><?=e($c['cidade'])?>/<?=e($c['estado'])?> · <?=e($c['escolaridade'])?><br><span class="mut"><?=e($c['habilidades'])?></span></td><td><?=$c['media']??'—'?></td>
<td><form method="post"><?=csrf_field()?><input type="hidden" name="acao" value="status"><input type="hidden" name="id" value="<?=$c['id']?>"><input type="hidden" name="vaga" value="<?=$sel?>">
<select name="status" onchange="this.form.submit()"><?=opts(STATUS,$c['status'])?></select></form></td></tr><?php endforeach;?></table></div><?php endif;?>
<details class="card" style="margin-top:24px"><summary><strong>Perfil da empresa</strong></summary><form method="post"><?=csrf_field()?><input type="hidden" name="acao" value="perfil">
<label>Nome</label><input name="nome" value="<?=e($emp['nome_fantasia'])?>"><label>Setor</label><input name="setor" value="<?=e($emp['setor'])?>"><label>Cidade</label><input name="cidade" value="<?=e($emp['cidade'])?>">
<label>Sobre</label><textarea name="sobre"><?=e($emp['sobre'])?></textarea><p><button class="btn p sm">Salvar</button></p></form></details><?php foot();
