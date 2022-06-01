<?php


//layout a pergunta a ser realizada, com o tipo de personagem mais a pergunta escolhida
function primeiraPergunta(){
    echo "
    <html>
        <header>
            <link rel='stylesheet' href='../CSS/styles.css'>
            <title>Futnator!</title>
        </header> 
        <body>
            <div>
                <h1>Começando o jogo</h1>
                <h2>Qual o tipo do seu personagem?</h2>
                <div class='container'>
                    <form action = '.' method = 'post' class='confimacao'>
                            </br></br></br></br>               
                            <button type='submit' name='tipoPersonagem' value='jogador'><h6><b>Jogador</b></h6></button>
                            </br>
                            </br>
                            <button type='submit' name='tipoPersonagem' value='estádio'><h6><b>Estádio</b></h6></button>
                            </br>
                            </br>
                            <button type='submit' name='tipoPersonagem' value='time'><h6><b>Time</b></h6></button>
                        </form>
                </div>
            </div>
        </body>
    </html>
    ";
    
}



function fazPergunta($caracteristica,$tipoPersonagem){
    echo "
    <html>
        <header>
            <link rel='stylesheet' href='../CSS/styles.css'>
            <title>Futnator!</title>
        </header> 
        <body>
            <div class='container'>
                <h2>O seu $tipoPersonagem $caracteristica?</h2>
            </div>
                <div class='container'>
  
                    <form method = 'post' align='center' class='confimacao'>
                        <button type='submit' name='resposta' value='sim'><h6><b>Sim</b></h6></button>
                        </br>
                        </br>
                        <button type='submit' name='resposta' value='nao'><h6><b>Não</b></h6></button>
                        </br>
                        </br>
                        <button type='submit' name='resposta' value='nao_sei'><h6><b>Não sei</b></h6></button>
                        <input type='hidden' name='caracteristicaVerificada',value='$caracteristica'>
                    </form>
                </div>
            
        </body
    </html>
    ";
}


//escolhe uma característica
function selecionaCaracteristica($caract,$caractSelec,$tipoPersonagem){
    $tamanhoCarac = count($caract);
    $caracteristicaEscolhida = "";

    if($tamanhoCarac==0){
        //função de erro que indica que não há personagens criados
        echo "
        <html>
            <header>
                <link rel='stylesheet' href='../CSS/styles.css'>
                <title>Futnator!</title>
            </header> 
            <body>
                <div class='container'>
                    <h2>Não há personagens cadastrados com essas características</h2>
                </div>
                    <div class='container'>
                        <form method = 'post' action = './'>
                            <button type='submit'>Tente novamente</button>
                        </form>
                    </div>
                
            </body>
        </html>
                    ";
        recomeca();
        //die();
    }else{
        
        //Verifica se há caracteristicas diferentes das descartadas.
        //Se não houver, o jogador é questionado sobre a identidade dele
        //no programa principal, baseado no retorno
        
        $igual = 1;
        
        foreach($caract as $ce){
           if(!in_array($ce,$caractSelec)){
               $igual = 0;
           }
        }
        
        
        if($igual){
            return "igual";
        }else{
             while(true){
                $indice = rand(0,$tamanhoCarac-1);
                
                //verifica se a característica escolhida já foi descartada
                if(!in_array($caract[$indice],$caractSelec)){
                    $caracteristicaEscolhida = $caract[$indice];
                    break;
                }
            
            }
        
            fazPergunta($caracteristicaEscolhida,$tipoPersonagem);
                
        }
        
    }
    
    
    
   
    return $caracteristicaEscolhida;
    
}

function sugerePersonagem($personagemFinal,$tipoPersonagem){
    
    //variável para selecionar o mais escolhido
    $escolhido = 0;
    
    $personagemEscolhido = null;

    foreach($personagemFinal as $p){
        
         if((int) $p["escolhido"]>=$escolhido){
            $personagemEscolhido = $p;
            $escolhido = (int) $p["escolhido"];
         }
    }
    
    $personagemNome = $personagemEscolhido['nome'];
    $personagemID = $personagemEscolhido['id'];
    $personagemImagem = $personagemEscolhido['imagem'];
    
    //nome e imagem do personagem relacionado, caso tenha
    $nomeRelacao = "";
    $imagemRelacao = "";
    $tipoRelacao = "";
    
    if($tipoPersonagem=="time"){
        $nomeRelacao = $personagemEscolhido['nomeEstadio'];
        $imagemRelacao = $personagemEscolhido['imagemEstadio'];
        $tipoRelacao = "Estádio";
    }else if($tipoPersonagem=="jogador"){
        $nomeRelacao = $personagemEscolhido['nomeTime'];
        $imagemRelacao = $personagemEscolhido['imagemTime'];
        $tipoRelacao = "Time";
    }
    
    if($nomeRelacao == "" and $tipoRelacao !=""){
        $nomeRelacao = "nenhum";
    }
    
    
    //mudar o texto de acordo com o tipo de relação
    
    echo "
    <html>
        <header>
            <link rel='stylesheet' href='../CSS/styles.css'>
            <title>Futnator!</title>
        </header> 
        <body>
        
            <h2>O seu $tipoPersonagem é $personagemNome?</h2>
            
            <div class='container'>
                
                <img class = 'personagem' src = '$personagemImagem'></img>
                
            </div>
            
            <div class='infoJogador'>
                
                 <strong>$tipoRelacao: </strong><quote>$nomeRelacao</quote>
                <img class = 'relacao' src = '$imagemRelacao'></img>
                <quote><strong>Número de vezes escolhido:</strong> $escolhido</quote>
            </div>
            
            
 
            
             <form class='simNao' method = 'post'>
                    <button type='submit' name='respostaSugestao' value='sim'>Sim</button>
                     
                    <button type='submit' name='respostaSugestao' value='nao'>Não</button>
                    <input type='hidden' name='personagemSugerido' value='$personagemID'>
                </form>
        </body>
    </html>
    ";
}


//função que pergunta pelo nome do personagem que o jogador está sugerindo, caso
//o sugerido não o satisfaça
function verificaNome($caracteristicas,$tipoPersonagem,$personagemID){
    
    //as caracteristicas são passadas como parâmetro para serem enviadas
    //para outro arquivo, assim elas serão comparadas com o personagem
    //escolhido, ou, se não houver tal personagem cadastradado, o jogador
    //poderá cadastrá-lo, com essas características sendo obrigatórias.
    
    $formulario =  "
    <html>
        <header>
            <link rel='stylesheet' href='../CSS/styles.css'>
            <title>Futnator!</title>
        </header> 
        <body>
            <div class='cadastro-nome'>
                <form method='post' action='novoPersonagem.php'>
                    <h2>Então qual é o $tipoPersonagem?</h2>
                    <input type='text' name='personagem' maxlength = '40' placeholder = 'Digite o nome' required='required'><br>
                    <input type='submit'  value='Enviar'>
                    <input type='hidden' name='tipoPersonagem' value='$tipoPersonagem'>
                    <input type='hidden' name='personagemID' value='$personagemID'>
        ";
        
        foreach($caracteristicas as $c){
            $formulario.=
            "<input type='hidden' name='caracteristicas[]' value='$c'>";
        }
        
        $formulario.="
                </form>
            </div>
        </body>
    </html>";
                 
        echo $formulario;
        
}

function recomeca(){
    if(!isset($_SESSION)){
        session_start();
     }
   
    
    if(isset($_SESSION)){
        session_destroy();
    }

}

//modifica uma string para comparações. removendo os acentose espaços e tornando-a
//minúscula
function modStringComp($string){
    $semAcento = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj',''=>'Z', ''=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
    'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
);
    

    $string = str_replace(" ","",$string);
    
    $string = strtr($string, $semAcento);
    
    $string = strtolower($string);
    
    return $string;
}


function salvaContexto(){
   global $arrayCaract,
    $arrayElementosAtuais,
    $arrayCaractDescart,
    $tipoPersonagem,
    $numPergunta,
    $caractVerif;

    
   $_SESSION["caracteristicas"] =              $arrayCaract;
   $_SESSION["elementosAtuais"] =              $arrayElementosAtuais;
   $_SESSION["caracteristicasDescartadas"] =   $arrayCaractDescart;
   $_SESSION["tipoPersonagem"] =               $tipoPersonagem;
   $_SESSION["numPergunta"] =                  $numPergunta;
   $_SESSION["caracteristicaVerificada"] =     $caractVerif;
}

?>