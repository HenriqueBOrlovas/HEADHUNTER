const $=s=>document.querySelector(s),A=$('#app'),esc=s=>String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
let perg='';
const api=async o=>(await fetch(A.dataset.api,{method:'POST',headers:{'Content-Type':'application/json','X-T':A.dataset.t},body:JSON.stringify(o)})).json();
async function nova(){$('#res').hidden=true;$('#resp').value='';$('#box').hidden=false;$('#perg').textContent='Preparando pergunta…';
  const d=await api({acao:'pergunta',area:$('#area').value});perg=d.pergunta||d.erro||'Erro ao gerar pergunta.';$('#perg').textContent=perg}
$('#ini').onclick=nova;$('#prox').onclick=nova;
$('#env').onclick=async()=>{const r=$('#resp').value.trim();
  if(r.split(/\s+/).length<8){alert('Escreva pelo menos 8 palavras para eu conseguir analisar.');return}
  $('#env').disabled=true;$('#env').textContent='Analisando…';
  const d=await api({acao:'avaliar',area:$('#area').value,pergunta:perg,resposta:r});
  $('#env').disabled=false;$('#env').textContent='Enviar resposta';
  if(d.erro){alert(d.erro);return}
  const bar=(n,v,i)=>`<div class="bar2"><span>${n}</span><div class="tr"><i class="${i%2?'b':''}" style="width:${v}%"></i></div><span>${v}</span></div>`;
  const li=a=>(a||[]).map(x=>`<li>${esc(x)}</li>`).join('');
  $('#res').innerHTML=`<div class="row"><div class="nota">${d.nota}</div><div><strong>Nota geral</strong><br><span class="mut">${d.fonte==='ia'?'Análise por IA':'Análise automática (ative a chave da IA para feedback completo)'}</span></div></div>
  ${bar('Estrutura',d.estrutura,0)}${bar('Clareza',d.clareza,1)}${bar('Exemplos',d.exemplos,2)}${bar('Postura',d.postura,3)}
  ${d.pontos_fortes.length?`<h3>O que foi bem</h3><ul>${li(d.pontos_fortes)}</ul>`:''}<h3>Para melhorar</h3><ul>${li(d.melhorias)}</ul>
  ${d.modelo?`<h3>Exemplo de resposta</h3><p class="card">${esc(d.modelo)}</p>`:''}<p class="mut">Salvo no seu painel, onde você decide se compartilha com empresas.</p>`;
  $('#res').hidden=false};
const SR=window.SpeechRecognition||window.webkitSpeechRecognition;
if(!SR)$('#mic').hidden=true;else $('#mic').onclick=()=>{const s=new SR();s.lang='pt-BR';
  s.onresult=e=>{$('#resp').value+=(($('#resp').value?' ':'')+e.results[0][0].transcript)};s.start()};
