<?php
    //aqui vem a verificação do login do jogador, via sessão

    
    include_once "../model/relacao.php";
    include_once "./funcao.php";
    
    session_start();
    
    //Variáveis do jogo
        
    //array com as caracteristicas a serem filtradas
    $arrayCaract = [];
    //Array com os elementos atuais filtrados
    $arrayElementosAtuais = [];
    //Array com as características que foram descartadas ou não confirmadas, ou já lidas
    $arrayCaractDescart = [];
    //String que define se o personagem é um jogador, um time ou um estádio
    $tipoPersonagem = "";
    //Número inteiro que define a pergunta atual do jogo
    $numPergunta = 1;
    //característica verificada na pergunta
    $caractVerif = "";
    
    
    if (isset($_SESSION["jogo"])){
        //Resposta à característica do seu personagem, podendo ser sim,nao e nao_sei
        $resposta = "";
    
        $arrayCaract =            $_SESSION["caracteristicas"];        
        $arrayElementosAtuais = $_SESSION["elementosAtuais"];      
        $arrayCaractDescart = $_SESSION["caracteristicasDescartadas"];
        $tipoPersonagem = $_SESSION["tipoPersonagem"];        
        $numPergunta = $_SESSION["numPergunta"]; 
        $caractVerif =  $_SESSION["caracteristicaVerificada"];
        $resposta =  isset($_POST["resposta"])?$_POST["resposta"]:"";
        
        $respostaSugestao = isset($_POST["respostaSugestao"])?$_POST["respostaSugestao"]:"";
        
        //verifica a resposta em relação ao personagem sugerido
        if($respostaSugestao!=""){
            $personagemSugerido =  isset($_POST["personagemSugerido"])?$_POST["personagemSugerido"]:"";
             
             
             if($respostaSugestao=="sim"){
                 atualizaEscolhido($personagemSugerido,$tipoPersonagem);
                 echo "<script language='javascript'>window.location.href='./finalizado.php?ok=true'</script>";
                 
             }else if ($respostaSugestao=="nao"){
                 verificaNome($arrayCaract,$tipoPersonagem,$personagemSugerido);
                 die();
             }
             
          
            //se for, o número de seleções é atualizado, de acordo com o id
            //se não, as características são enviadas para um outro arquivo
            //que, verificará o personagem e as características
            //se já existir um personagem de mesmo nome e características,
            //o valor de acertos será atualizado, se não, um novo poderá
            //ser criado pelo jogador
            
            
        }
        
        
        //filtra as características do personagem
        if($resposta!=""){
            if($resposta == "sim"){
                array_push($arrayCaract,$caractVerif);
                //array_push($arrayCaractDescart,$caractVerif);
                
            }else if ($resposta == "nao"){
                array_push($arrayCaractDescart,$caractVerif);
            }
            
            $numPergunta++;
            
            $caractVerif = "";
            $resposta = "";
        }
        
        //mostra o número da pergunta atual
        echo "<div class='aviso'><b>$numPergunta . </b></div>";
        
        if($tipoPersonagem=="estádio"){
                include_once "../model/estadio.php";
                
                //ids desconsiderados
                $arrayIDs = [];
                $arrayGeral = pesquisaTodosEstadio();
                
                foreach($arrayGeral as $AEA){
                    if(!isset($arrayIDs[$AEA["id"]])){
                        $arrayIDs[$AEA["id"]] = [$AEA["caracteristica"]];
                    }else{
                        array_push($arrayIDs[$AEA["id"]],$AEA["caracteristica"]);
                    }
                    
                }
                
                //pega os ids de fato
                $idsDescon = "(''";
                foreach($arrayIDs as $key=>$AID){
                    //aqui para as características descartadas
                    foreach($arrayCaractDescart as $ACD){
                        if(in_array($ACD,$AID)){
                            $idsDescon.=",'$key'";
                        }
                    }
                
                
                    //aqui para as características escolhidas
                    foreach($arrayCaract as $AC){
                        if(!in_array($AC,$AID)){
                            $idsDescon.=",'$key'";
                        }
                    }
                
                }
                
                $idsDescon.=")";
                
                //filtro para caso só exista cacacterísticas descartadas
                $arrayFiltro = [];
                
                if(count($arrayCaract)==0){
                    foreach(pesquisaTodosEstadio() as $a){
                        array_push($arrayFiltro,$a['caracteristica']);
                        
                    }
                }else{
                    $arrayFiltro = $arrayCaract;
                }
                
                
                //selecionaCaracteristica
                
                //pega todas as caracteristicas e formata para passar na função
                $caracteristicasFormatadas = [];
                
                $arrayPercorrido = count($arrayCaract)>0 || count($arrayCaractDescart)>0  ?pesquisaFiltroEstadio($arrayFiltro,$idsDescon)
                :pesquisaTodosEstadio();
                
                $arrayElementosAtuais = $arrayPercorrido;
                
                //formatação
                foreach($arrayPercorrido as $caracteristicas){
                    if( !in_array($caracteristicas["caracteristica"],$arrayCaractDescart)){
                        array_push($caracteristicasFormatadas,$caracteristicas["caracteristica"]);
                    }
                }
                
                
                //função que seleciona uma característica que não tenha sido descartada
                //e chama a função das perguntas em si
                
                
                $caractVerif = selecionaCaracteristica($caracteristicasFormatadas,$arrayCaract,
                $tipoPersonagem);
                
                if($caractVerif=="igual"){
                    //sugere o personagem pretendido
                    sugerePersonagem($arrayPercorrido,$tipoPersonagem);
                }
               
                
               
                
        }else if($tipoPersonagem=="jogador"){
                include_once "../model/jogador.php";
                
                //ids desconsiderados
                $arrayIDs = [];
                $arrayGeral = pesquisaTodosJogador();
                
                foreach($arrayGeral as $AEA){
                    if(!isset($arrayIDs[$AEA["id"]])){
                        $arrayIDs[$AEA["id"]] = [$AEA["caracteristica"]];
                    }else{
                        array_push($arrayIDs[$AEA["id"]],$AEA["caracteristica"]);
                    }
                    
                }
                
                //pega os ids de fato
                $idsDescon = "(''";
                foreach($arrayIDs as $key=>$AID){
                    //aqui para as características descartadas
                    foreach($arrayCaractDescart as $ACD){
                        if(in_array($ACD,$AID)){
                            $idsDescon.=",'$key'";
                        }
                    }
                
                
                    //aqui para as características escolhidas
                    foreach($arrayCaract as $AC){
                        if(!in_array($AC,$AID)){
                            $idsDescon.=",'$key'";
                        }
                    }
                
                }
                
                $idsDescon.=")";
                
                //filtro para caso só exista cacacterísticas descartadas
                $arrayFiltro = [];
                
                if(count($arrayCaract)==0){
                    foreach(pesquisaTodosJogador() as $a){
                        array_push($arrayFiltro,$a['caracteristica']);
                        
                    }
                }else{
                    $arrayFiltro = $arrayCaract;
                }
                
                //selecionaCaracteristica
                
                //pega todas as caracteristicas e formata para passar na função
                $caracteristicasFormatadas = [];
                
                $arrayPercorrido = count($arrayCaract)>0 || count($arrayCaractDescart)>0   ?pesquisaFiltroJogador($arrayFiltro,$idsDescon)
                :pesquisaTodosJogador();
                
                $arrayElementosAtuais = $arrayPercorrido;
                
                //formatação
                foreach($arrayPercorrido as $caracteristicas){
                    if(!in_array($caracteristicas["caracteristica"],$arrayCaractDescart)){
                        array_push($caracteristicasFormatadas,$caracteristicas["caracteristica"]);
                    }
                }
                
                //função que seleciona uma característica que não tenha sido descartada
                //e chama a função das perguntas em si
                $caractVerif = selecionaCaracteristica($caracteristicasFormatadas,$arrayCaract,
                $tipoPersonagem);
                
                if($caractVerif=="igual"){
                    //sugere o personagem pretendido
                    sugerePersonagem($arrayPercorrido,$tipoPersonagem);
                }
                
        }else if($tipoPersonagem=="time"){
                include_once "../model/time.php";
                
                
                //ids desconsiderados
                $arrayIDs = [];
                $arrayGeral = pesquisaTodosTime();
                
                foreach($arrayGeral as $AEA){
                    if(!isset($arrayIDs[$AEA["id"]])){
                        $arrayIDs[$AEA["id"]] = [$AEA["caracteristica"]];
                    }else{
                        array_push($arrayIDs[$AEA["id"]],$AEA["caracteristica"]);
                    }
                    
                }
                
                //pega os ids de fato
                $idsDescon = "(''";
                foreach($arrayIDs as $key=>$AID){
                    //aqui para as características descartadas
                    foreach($arrayCaractDescart as $ACD){
                        if(in_array($ACD,$AID)){
                            $idsDescon.=",'$key'";
                        }
                    }
                
                
                    //aqui para as características escolhidas
                    foreach($arrayCaract as $AC){
                        if(!in_array($AC,$AID)){
                            $idsDescon.=",'$key'";
                        }
                    }
                
                }
                
                $idsDescon.=")";
                
                //filtro para caso só exista cacacterísticas descartadas
                $arrayFiltro = [];
                
                if(count($arrayCaract)==0){
                    foreach(pesquisaTodosTime() as $a){
                        array_push($arrayFiltro,$a['caracteristica']);
                        
                    }
                }else{
                    $arrayFiltro = $arrayCaract;
                }
                

                //selecionaCaracteristica
                
                //pega todas as caracteristicas e formata para passar na função
                $caracteristicasFormatadas = [];
                
                $arrayPercorrido = count($arrayCaract)>0 || count($arrayCaractDescart)>0  ?pesquisaFiltroTime($arrayFiltro,$idsDescon)
                :pesquisaTodosTime();
                

                $arrayElementosAtuais = $arrayPercorrido;
                
                //formatação
                foreach($arrayPercorrido as $caracteristicas){
                    if(!in_array($caracteristicas["caracteristica"],$arrayCaractDescart)){
                        array_push($caracteristicasFormatadas,$caracteristicas["caracteristica"]);
                    }
                }
                
                //fazer um esquema para filtrar, excluindo as caract decartadas
                //no futuro, farei um esquema para selecionar a característica
                //baseada no número de escolhas
                

                //função que seleciona uma característica que não tenha sido descartada
                //e chama a função das perguntas em si
                $caractVerif = selecionaCaracteristica($caracteristicasFormatadas,$arrayCaract,
                $tipoPersonagem);
                
                 if($caractVerif=="igual"){
                    //sugere o personagem pretendido
                    sugerePersonagem($arrayPercorrido,$tipoPersonagem);
                }
                
        }
        
        //salva as variáveis de sessão
        salvaContexto();

        
    }else{
         //Verifica o tipo do personagem (início do jogo)
        if(isset($_POST["tipoPersonagem"]) and !isset($_SESSION["tipoPersonagem"])){
            
            $tipoPersonagem = $_POST["tipoPersonagem"];
            $_SESSION["jogo"] = true;
            
            //salva as variáveis de sessão
            salvaContexto();
            
            header("Location: ./");
        }else{
            primeiraPergunta();
        }
        
        
       
    }
    
?>
