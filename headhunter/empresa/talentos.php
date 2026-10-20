<?php require __DIR__.'/../includes/bootstrap.php';$u=need('empresa');
$w=['c.perfil_publico=1'];$p=[$u['id']];$h=[];$g=$_GET;
if($q=trim($g['q']??'')){$w[]='(usr.nome LIKE ? OR c.resumo LIKE ?)';array_push($p,"%$q%","%$q%");}
foreach(['area','escolaridade','disponibilidade'] as $k)if(!empty($g[$k])){$w[]="c.$k=?";$p[]=$g[$k];}
if($x=trim($g['estado']??'')){$w[]='c.estado=?';$p[]=strtoupper($x);}
if($x=trim($g['cidade']??'')){$w[]='c.cidade LIKE ?';$p[]="%$x%";}
foreach(['habilidade'=>'habilidades','idioma'=>'idiomas'] as $k=>$col)if($x=trim($g[$k]??'')){$w[]="c.$col LIKE ?";$p[]="%$x%";}
if(!empty($g['pret']))$w[]='(c.pretensao IS NULL OR c.pretensao<='.(float)$g['pret'].')';
if(!empty($g['nota']))$h[]='media>='.(int)$g['nota'];
if(!empty($g['so_ent']))$h[]='n_ent>0';
$sql="SELECT usr.id,usr.nome,usr.email,c.*,
 (SELECT ROUND(AVG(nota)) FROM entrevistas e WHERE e.candidato_id=c.usuario_id AND e.compartilhada=1) media,
 (SELECT COUNT(*) FROM entrevistas e WHERE e.candidato_id=c.usuario_id AND e.compartilhada=1) n_ent,
 EXISTS(SELECT 1 FROM candidaturas ca JOIN vagas v ON v.id=ca.vaga_id WHERE ca.candidato_id=c.usuario_id AND v.empresa_id=?) aplicou
 FROM candidatos c JOIN usuarios usr ON usr.id=c.usuario_id WHERE ".implode(' AND ',$w).($h?' HAVING '.implode(' AND ',$h):'').' ORDER BY media DESC, usr.id DESC LIMIT 40';
// o 1º parâmetro (empresa) pertence ao EXISTS, que vem antes dos filtros no SQL
$s=$pdo->prepare($sql);$s->execute($p);$lista=$s->fetchAll();
$ents=[];if($lista){$ids=array_column($lista,'id');$in=implode(',',array_fill(0,count($ids),'?'));
 $t=$pdo->prepare("SELECT * FROM entrevistas WHERE compartilhada=1 AND candidato_id IN($in) ORDER BY nota DESC");$t->execute($ids);foreach($t->fetchAll() as $r)$ents[$r['candidato_id']][]=$r;}
head('Talentos');?>
<div class="side"><form class="card" method="get"><h3>Filtro avançado</h3>
<label>Nome ou palavra no resumo</label><input name="q" value="<?=e($q)?>"><label>Área</label><select name="area"><?=opts(AREAS,$g['area']??'','Todas')?></select>
<label>Escolaridade</label><select name="escolaridade"><?=opts(ESCOL,$g['escolaridade']??'','Todas')?></select>
<div class="cols"><div><label>Cidade</label><input name="cidade" value="<?=e($g['cidade']??'')?>"></div><div><label>UF</label><input name="estado" maxlength="2" value="<?=e($g['estado']??'')?>"></div></div>
<label>Habilidade</label><input name="habilidade" value="<?=e($g['habilidade']??'')?>" placeholder="Excel"><label>Idioma</label><input name="idioma" value="<?=e($g['idioma']??'')?>" placeholder="Inglês">
<label>Disponibilidade</label><select name="disponibilidade"><?=opts(['Manhã','Tarde','Noite','Integral','Flexível'],$g['disponibilidade']??'','Qualquer')?></select>
<label>Pretensão máxima (R$)</label><input type="number" name="pret" value="<?=e($g['pret']??'')?>"><label>Nota mínima nas entrevistas</label><input type="number" min="0" max="100" name="nota" value="<?=e($g['nota']??'')?>">
<label><input type="checkbox" name="so_ent" value="1" <?=!empty($g['so_ent'])?'checked':''?>> Só quem compartilhou entrevistas</label>
<p><button class="btn p">Filtrar</button> <a href="talentos.php">Limpar</a></p></form>
<div class="stack"><h2><?=count($lista)?> talento(s)</h2>
<?php foreach($lista as $c):?><div class="card"><div class="row" style="justify-content:space-between"><h3><?=e($c['nome'])?></h3><?=$c['media']!==null?'<span class="tag p">Nota média '.$c['media'].' · '.$c['n_ent'].' entrevista(s)</span>':''?></div>
<div class="mut"><?=e($c['cidade'])?>/<?=e($c['estado'])?> · <?=e($c['escolaridade'])?> · <?=e($c['area'])?> · <?=e($c['disponibilidade'])?></div>
<p><?=e($c['resumo'])?></p><?php foreach(array_filter(array_map('trim',explode(',',(string)$c['habilidades']))) as $hb)echo '<span class="tag">'.e($hb).'</span>';?>
<p class="mut"><?=$c['aplicou']?'Contato: '.e($c['email']).' '.e($c['telefone']):'Os contatos ficam visíveis quando a pessoa se candidata a uma das suas vagas.'?></p>
<?php foreach($ents[$c['id']]??[] as $en):?><details><summary>Entrevista (<?=e($en['area'])?>) · nota <?=$en['nota']?> · <?=e($en['pergunta'])?></summary><p><?=nl2br(e($en['resposta']))?></p></details><?php endforeach;?></div>
<?php endforeach;if(!$lista)echo '<p class="card mut">Nenhum talento com esses filtros. Tente ampliar a busca.</p>';?></div></div><?php foot();
