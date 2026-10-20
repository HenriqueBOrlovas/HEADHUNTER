<?php require 'includes/bootstrap.php';
$w=['v.ativa=1'];$p=[];
if($q=trim($_GET['q']??'')){$w[]='(v.titulo LIKE ? OR v.descricao LIKE ? OR v.requisitos LIKE ?)';array_push($p,"%$q%","%$q%","%$q%");}
foreach(['area','modalidade','nivel'] as $k)if(!empty($_GET[$k])){$w[]="v.$k=?";$p[]=$_GET[$k];}
if($c=trim($_GET['cidade']??'')){$w[]='v.cidade LIKE ?';$p[]="%$c%";}
if(!empty($_GET['sal'])){$w[]='v.salario>=?';$p[]=(float)$_GET['sal'];}
$s=$pdo->prepare('SELECT v.*,e.nome_fantasia FROM vagas v JOIN empresas e ON e.usuario_id=v.empresa_id WHERE '.implode(' AND ',$w).' ORDER BY v.criada_em DESC LIMIT 60');
$s->execute($p);$vagas=$s->fetchAll();head('Vagas');?>
<div class="side"><form class="card" method="get"><h3>Filtrar vagas</h3>
<label>Busca</label><input name="q" value="<?=e($q)?>" placeholder="cargo, palavra-chave">
<label>Área</label><select name="area"><?=opts(AREAS,$_GET['area']??'','Todas')?></select>
<label>Nível</label><select name="nivel"><?=opts(NIVEIS,$_GET['nivel']??'','Todos')?></select>
<label>Modalidade</label><select name="modalidade"><?=opts(MODAL,$_GET['modalidade']??'','Todas')?></select>
<label>Cidade</label><input name="cidade" value="<?=e($c)?>">
<label>Salário mínimo (R$)</label><input type="number" min="0" name="sal" value="<?=e($_GET['sal']??'')?>">
<p><button class="btn p">Aplicar filtros</button> <a href="vagas.php">Limpar</a></p></form>
<div class="stack"><h2><?=count($vagas)?> vaga(s) encontrada(s)</h2>
<?php foreach($vagas as $v):?><a class="card" style="display:block;text-decoration:none" href="vaga.php?id=<?=$v['id']?>">
<h3><?=e($v['titulo'])?></h3><div class="mut"><?=e($v['nome_fantasia'])?> · <?=e($v['cidade'])?> · <?=e(money($v['salario']))?></div>
<span class="tag"><?=e(NIVEIS[$v['nivel']])?></span><span class="tag"><?=e(MODAL[$v['modalidade']])?></span><span class="tag p"><?=e($v['area'])?></span></a>
<?php endforeach;if(!$vagas)echo '<p class="card mut">Nenhuma vaga com esses filtros. Tente remover algum deles.</p>';?></div></div>
<?php foot();
