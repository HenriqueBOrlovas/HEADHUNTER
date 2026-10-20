<?php
declare(strict_types=1);
session_start();
require __DIR__.'/../config.php';
$pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,
  [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
const AREAS=['Administrativo','Atendimento','Comércio e Vendas','Tecnologia','Logística','Marketing','Saúde','Educação','Outros'];
const NIVEIS=['aprendiz'=>'Jovem aprendiz','estagio'=>'Estágio','junior'=>'Júnior','pleno'=>'Pleno'];
const MODAL=['presencial'=>'Presencial','hibrido'=>'Híbrido','remoto'=>'Remoto'];
const ESCOL=['Ensino fundamental','Ensino médio cursando','Ensino médio completo','Técnico','Superior cursando','Superior completo'];
const STATUS=['recebida'=>'Recebida','em_analise'=>'Em análise','entrevista'=>'Entrevista','aprovada'=>'Aprovada','recusada'=>'Recusada'];
function e($s):string{return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function user():?array{return $_SESSION['u']??null;}
function csrf():string{return $_SESSION['t']??=bin2hex(random_bytes(16));}
function csrf_field():string{return '<input type="hidden" name="_t" value="'.csrf().'">';}
function redirect(string $p):never{header('Location: '.BASE_URL.$p);exit;}
function flash(string $m):void{$_SESSION['f']=$m;}
function need(string $tipo):array{$u=user();if(!$u||$u['tipo']!==$tipo)redirect('/login.php');return $u;}
function opts(array $l,$sel='',string $vazio=''):string{
  $h=$vazio!==''?'<option value="">'.e($vazio).'</option>':'';$lista=array_is_list($l);
  foreach($l as $k=>$v){$val=$lista?$v:$k;$h.='<option value="'.e($val).'"'.((string)$val===(string)$sel?' selected':'').'>'.e($v).'</option>';}
  return $h;}
function money($v):string{return $v?'R$ '.number_format((float)$v,2,',','.'):'A combinar';}
if($_SERVER['REQUEST_METHOD']==='POST'){
  $t=$_POST['_t']??($_SERVER['HTTP_X_T']??'');
  if(!hash_equals(csrf(),(string)$t)){http_response_code(400);exit('Sessão expirada. Volte e tente de novo.');}
}
function head(string $t):void{
  $u=user();$b=BASE_URL;
  echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
   .'<title>'.e($t).' · HEADHUNTER</title><link rel="stylesheet" href="'.$b.'/assets/css/style.css"></head><body>'
   .'<header class="top"><div class="wrap bar"><a class="brand" href="'.$b.'/index.php"><i></i>HEADHUNTER</a><nav>';
  $l=[['/vagas.php','Vagas']];
  if(!$u){$l[]=['/login.php','Entrar'];$l[]=['/register.php','Criar conta'];}
  elseif($u['tipo']==='candidato'){array_push($l,['/candidato/painel.php','Painel'],['/candidato/curriculo.php','Currículo'],['/candidato/simulador.php','Simulador IA'],['/logout.php','Sair']);}
  else{array_push($l,['/empresa/painel.php','Painel'],['/empresa/vaga_nova.php','Nova vaga'],['/empresa/talentos.php','Talentos'],['/logout.php','Sair']);}
  foreach($l as $x)echo '<a href="'.$b.$x[0].'">'.e($x[1]).'</a>';
  echo '</nav></div></header><main class="wrap">';
  if(!empty($_SESSION['f'])){echo '<p class="flash" role="status">'.e($_SESSION['f']).'</p>';unset($_SESSION['f']);}
}
function foot():void{echo '</main><footer class="wrap foot">HEADHUNTER © 2026 · Preparação para entrevistas com IA e conexão com empresas</footer></body></html>';}

