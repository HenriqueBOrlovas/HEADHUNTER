<?php require __DIR__.'/../includes/bootstrap.php';require __DIR__.'/../includes/ia.php';
header('Content-Type: application/json; charset=utf-8');$u=user();
if(!$u||$u['tipo']!=='candidato'){http_response_code(401);exit(json_encode(['erro'=>'Entre como candidato.']));}
$in=json_decode(file_get_contents('php://input'),true)?:[];$area=in_array($in['area']??'',AREAS,true)?$in['area']:'Outros';
if(($in['acao']??'')==='pergunta')exit(json_encode(['pergunta'=>ia_pergunta($area)],JSON_UNESCAPED_UNICODE));
$p=mb_substr(trim((string)($in['pergunta']??'')),0,300);$r=mb_substr(trim((string)($in['resposta']??'')),0,3000);
if($p===''||count(preg_split('/\s+/u',$r,-1,PREG_SPLIT_NO_EMPTY))<5)exit(json_encode(['erro'=>'Resposta muito curta.']));
$a=ia_avaliar($p,$r,$area);
$pdo->prepare('INSERT INTO entrevistas(candidato_id,area,pergunta,resposta,nota,feedback) VALUES(?,?,?,?,?,?)')
 ->execute([$u['id'],$area,$p,$r,$a['nota'],json_encode($a,JSON_UNESCAPED_UNICODE)]);
echo json_encode($a,JSON_UNESCAPED_UNICODE);
