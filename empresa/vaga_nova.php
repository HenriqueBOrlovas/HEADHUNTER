<?php require __DIR__.'/../includes/bootstrap.php';$u=need('empresa');
if($_SERVER['REQUEST_METHOD']==='POST'&&trim($_POST['titulo']??'')!==''){
 $pdo->prepare('INSERT INTO vagas(empresa_id,titulo,descricao,requisitos,area,cidade,modalidade,nivel,salario) VALUES(?,?,?,?,?,?,?,?,?)')
 ->execute([$u['id'],trim($_POST['titulo']),trim($_POST['descricao']),trim($_POST['requisitos']),in_array($_POST['area'],AREAS,true)?$_POST['area']:'Outros',trim($_POST['cidade']),
 isset(MODAL[$_POST['modalidade']])?$_POST['modalidade']:'presencial',isset(NIVEIS[$_POST['nivel']])?$_POST['nivel']:'junior',$_POST['salario']!==''?(float)$_POST['salario']:null]);
 flash('Vaga publicada!');redirect('/empresa/painel.php');}
head('Nova vaga');?>
<h1>Publicar vaga</h1><form class="card" method="post"><?=csrf_field()?>
<label>Título</label><input name="titulo" required><div class="cols">
<div><label>Área</label><select name="area"><?=opts(AREAS)?></select></div><div><label>Nível</label><select name="nivel"><?=opts(NIVEIS)?></select></div>
<div><label>Modalidade</label><select name="modalidade"><?=opts(MODAL)?></select></div><div><label>Cidade</label><input name="cidade" required></div>
<div><label>Salário (R$)</label><input type="number" min="0" name="salario"></div></div>
<label>Descrição</label><textarea name="descricao" required></textarea><label>Requisitos</label><textarea name="requisitos"></textarea>
<p><button class="btn p">Publicar</button></p></form><?php foot();
