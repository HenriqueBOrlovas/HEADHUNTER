<?php require 'includes/bootstrap.php';$err='';$tipo=($_GET['tipo']??$_POST['tipo']??'')==='empresa'?'empresa':'candidato';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $n=trim($_POST['nome']??'');$m=strtolower(trim($_POST['email']??''));$s=$_POST['senha']??'';
 if(!$n||!filter_var($m,FILTER_VALIDATE_EMAIL)||strlen($s)<8)$err='Informe nome, e-mail válido e senha com 8 ou mais caracteres.';
 else{$x=$pdo->prepare('SELECT 1 FROM usuarios WHERE email=?');$x->execute([$m]);
  if($x->fetch())$err='Este e-mail já está cadastrado.';
  else{$pdo->prepare('INSERT INTO usuarios(nome,email,senha,tipo) VALUES(?,?,?,?)')->execute([$n,$m,password_hash($s,PASSWORD_DEFAULT),$tipo]);
   $id=(int)$pdo->lastInsertId();
   if($tipo==='empresa')$pdo->prepare('INSERT INTO empresas(usuario_id,nome_fantasia) VALUES(?,?)')->execute([$id,$n]);
   else $pdo->prepare('INSERT INTO candidatos(usuario_id) VALUES(?)')->execute([$id]);
   session_regenerate_id(true);$_SESSION['u']=['id'=>$id,'nome'=>$n,'tipo'=>$tipo];
   flash('Conta criada! Bem-vindo(a).');redirect($tipo==='empresa'?'/empresa/painel.php':'/candidato/curriculo.php');}}}
head('Criar conta');?>
<div class="card" style="max-width:480px;margin:auto"><h2>Criar conta</h2>
<?php if($err)echo '<p class="err">'.e($err).'</p>';?>
<form method="post"><?=csrf_field()?>
<label>Eu sou</label><select name="tipo"><option value="candidato"<?=$tipo==='candidato'?' selected':''?>>Candidato</option><option value="empresa"<?=$tipo==='empresa'?' selected':''?>>Empresa</option></select>
<label for="n">Nome (ou nome da empresa)</label><input id="n" name="nome" required value="<?=e($_POST['nome']??'')?>">
<label for="m">E-mail</label><input id="m" type="email" name="email" required value="<?=e($_POST['email']??'')?>">
<label for="s">Senha (mín. 8 caracteres)</label><input id="s" type="password" name="senha" minlength="8" required>
<p><button class="btn p">Criar conta</button></p></form><p class="mut">Já tem conta? <a href="login.php">Entrar</a></p></div>
<?php foot();
