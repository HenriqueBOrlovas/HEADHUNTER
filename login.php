<?php require 'includes/bootstrap.php';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $s=$pdo->prepare('SELECT * FROM usuarios WHERE email=?');$s->execute([strtolower(trim($_POST['email']??''))]);$u=$s->fetch();
 if($u&&password_verify($_POST['senha']??'',$u['senha'])){session_regenerate_id(true);
  $_SESSION['u']=['id'=>(int)$u['id'],'nome'=>$u['nome'],'tipo'=>$u['tipo']];
  redirect($u['tipo']==='empresa'?'/empresa/painel.php':'/candidato/painel.php');}
 $err='E-mail ou senha incorretos.';}
head('Entrar');?>
<div class="card" style="max-width:420px;margin:auto"><h2>Entrar</h2>
<?php if($err)echo '<p class="err">'.e($err).'</p>';?>
<form method="post"><?=csrf_field()?><label for="m">E-mail</label><input id="m" type="email" name="email" required>
<label for="s">Senha</label><input id="s" type="password" name="senha" required><p><button class="btn p">Entrar</button></p></form>
<p class="mut">Novo por aqui? <a href="register.php">Criar conta</a></p></div>
<?php foot();
