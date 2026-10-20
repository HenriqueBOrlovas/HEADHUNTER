<?php require __DIR__.'/../includes/bootstrap.php';$u=need('candidato');$id=$u['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){$a=$_POST['acao']??'';
 if($a==='perfil'){$f=fn($k)=>trim((string)($_POST[$k]??''));
  $pdo->prepare('UPDATE candidatos SET cidade=?,estado=?,telefone=?,linkedin=?,escolaridade=?,area=?,resumo=?,habilidades=?,idiomas=?,disponibilidade=?,pretensao=?,perfil_publico=? WHERE usuario_id=?')
  ->execute([$f('cidade'),strtoupper(substr($f('estado'),0,2)),$f('telefone'),$f('linkedin'),$f('escolaridade'),$f('area'),$f('resumo'),$f('habilidades'),$f('idiomas'),$f('disponibilidade'),$f('pretensao')!==''?(float)$f('pretensao'):null,isset($_POST['pub'])?1:0,$id]);flash('Currículo salvo.');}
 if($a==='exp_add'&&trim($_POST['cargo']??'')!==''){$pdo->prepare('INSERT INTO experiencias(usuario_id,cargo,empresa,periodo,descricao) VALUES(?,?,?,?,?)')->execute([$id,trim($_POST['cargo']),trim($_POST['empresa']??''),trim($_POST['periodo']??''),trim($_POST['descricao']??'')]);flash('Experiência adicionada.');}
 if($a==='exp_del'){$pdo->prepare('DELETE FROM experiencias WHERE id=? AND usuario_id=?')->execute([(int)$_POST['id'],$id]);flash('Experiência removida.');}
 redirect('/candidato/curriculo.php');}
$c=$pdo->prepare('SELECT * FROM candidatos WHERE usuario_id=?');$c->execute([$id]);$c=$c->fetch();
$ex=$pdo->prepare('SELECT * FROM experiencias WHERE usuario_id=? ORDER BY id DESC');$ex->execute([$id]);$ex=$ex->fetchAll();
$campos=['cidade','estado','telefone','escolaridade','area','resumo','habilidades','idiomas','disponibilidade'];$ok=0;foreach($campos as $k)if(!empty($c[$k]))$ok++;if($ex)$ok++;
$pct=(int)round($ok/(count($campos)+1)*100);head('Meu currículo');?>
<h1>Meu currículo</h1><p class="mut">Preenchimento: <strong><?=$pct?>%</strong> — perfis completos aparecem melhor nas buscas das empresas.</p>
<div class="tr" style="margin-bottom:18px"><i style="width:<?=$pct?>%"></i></div>
<form class="card" method="post"><?=csrf_field()?><input type="hidden" name="acao" value="perfil"><h3>Dados e perfil</h3><div class="cols">
<div><label>Cidade</label><input name="cidade" value="<?=e($c['cidade'])?>"></div><div><label>UF</label><input name="estado" maxlength="2" value="<?=e($c['estado'])?>"></div>
<div><label>Telefone</label><input name="telefone" value="<?=e($c['telefone'])?>"></div><div><label>LinkedIn (opcional)</label><input name="linkedin" value="<?=e($c['linkedin'])?>"></div>
<div><label>Escolaridade</label><select name="escolaridade"><?=opts(ESCOL,$c['escolaridade'],'Selecione')?></select></div>
<div><label>Área de interesse</label><select name="area"><?=opts(AREAS,$c['area'],'Selecione')?></select></div>
<div><label>Idiomas</label><input name="idiomas" placeholder="Inglês básico, Espanhol" value="<?=e($c['idiomas'])?>"></div>
<div><label>Disponibilidade</label><select name="disponibilidade"><?=opts(['Manhã','Tarde','Noite','Integral','Flexível'],$c['disponibilidade'],'Selecione')?></select></div>
<div><label>Pretensão salarial (R$)</label><input type="number" min="0" step="50" name="pretensao" value="<?=e($c['pretensao'])?>"></div></div>
<label>Sobre mim</label><textarea name="resumo"><?=e($c['resumo'])?></textarea>
<label>Habilidades (separe por vírgula)</label><input name="habilidades" placeholder="Excel, atendimento, comunicação" value="<?=e($c['habilidades'])?>">
<label><input type="checkbox" name="pub" <?=$c['perfil_publico']?'checked':''?>> Permitir que empresas encontrem meu perfil nas buscas</label>
<p><button class="btn p">Salvar currículo</button></p></form>
<h2 style="margin-top:28px">Experiências e projetos</h2><p class="mut">Estágio, voluntariado, projetos escolares e trabalhos informais contam!</p>
<?php foreach($ex as $x):?><div class="card row" style="justify-content:space-between;margin-bottom:10px"><div><strong><?=e($x['cargo'])?></strong> · <?=e($x['empresa'])?> <span class="mut">(<?=e($x['periodo'])?>)</span><br><?=e($x['descricao'])?></div>
<form method="post"><?=csrf_field()?><input type="hidden" name="acao" value="exp_del"><input type="hidden" name="id" value="<?=$x['id']?>"><button class="btn b sm">Remover</button></form></div><?php endforeach;?>
<form class="card" method="post"><?=csrf_field()?><input type="hidden" name="acao" value="exp_add"><h3>Adicionar experiência</h3><div class="cols">
<div><label>Cargo ou atividade</label><input name="cargo" required></div><div><label>Local</label><input name="empresa"></div></div>
<label>Período</label><input name="periodo" placeholder="2024 – 2025"><label>O que você fez</label><textarea name="descricao"></textarea><p><button class="btn b">Adicionar</button></p></form>
<?php foot();
