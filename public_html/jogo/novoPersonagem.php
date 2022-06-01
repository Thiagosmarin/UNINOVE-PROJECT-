<?php
    include_once "../model/conexao.php";
    include_once "../model/relacao.php";
    include_once "funcao.php";

    session_start();
    
    //tratamento do dados vindos do cadastro programado abaixo
    if(isset($_POST["cadastro"]) and $_POST["cadastro"] == "reinicio"){
        recomeca();
        header("Location: ./");
    }else if(isset($_POST["cadastro"])){
        //Dados do formulário
        
        // Dados principais
        $caracteristicas = $_POST['caracteristicas'];
        
        $nomePersonagem = $_POST['nome'];
        
        $tipoPersonagem = $_POST['tipoPersonagem'];
        
    
        $personagemRelacionado = isset($_POST["relacionamento"])?$_POST["relacionamento"]:"";
        
        $diretorio = "";
        
        //Dados da imagem
        if($_FILES["imagem"]["name"]!=""){
            $imagem = $_FILES["imagem"];
        
            $imagemTemp = $_FILES["imagem"]["tmp_name"];
            
            $nomeImagem = $_FILES['imagem']["name"];
            
            $tipoImagem = $_FILES['imagem']["type"];
            
            if(strpos($tipoImagem,"png")){
                $nomeImagem = modStringComp($nomePersonagem).".png";
            }else{
                $nomeImagem = modStringComp($nomePersonagem).".jpg";
            }
            
            //gerando o ditório da imagem, de acordo com o tipo do personagem
            $pasta = "";
            
            if($tipoPersonagem=="estádio"){
                $pasta = "estadio";
            }else{
                $pasta = $tipoPersonagem;
            }
            
            $diretorio = "../imagens/$pasta/$nomeImagem";
            
            //pra pegar o id, que será o nome da imagem -->
            //INSERT INTO table_name (col1, col2,...) VALUES ('val1', 'val2'...);
            //SELECT LAST_INSERT_ID();
            //o insert final virá aqui
            
            $resultado = inserePersonagem($tipoPersonagem,$nomePersonagem,$caracteristicas,$diretorio,$personagemRelacionado);
            
            //manipulação de diretórios
            
            //pegando a imagem enviada ao site
            $abertura = fopen($imagemTemp,"r");
            
            //lendo os dados da imagem
            $dados = fread($abertura,filesize($imagemTemp));
            
            //fechando o arquivo temporário
            fclose($abertura);
            
           
            //abertura, criação e escrita da imagem com os dados binários 
        
            $abertura = fopen($diretorio,"w");
            
            fwrite($abertura,$dados);
            
            //fechamento do arquivo
            fclose($abertura);
        
        
        }else{
             $resultado = inserePersonagem($tipoPersonagem,$nomePersonagem,$caracteristicas,$diretorio,$personagemRelacionado);
        }
            
        if($resultado!="repetido" and $resultado!=false){
                echo "
                    <html>
                        <header>
                            <link rel='stylesheet' href='../CSS/styles.css'>
                            <title>Futnator!</title>
                        </header> 
                        <body>
                            <div class='aviso'>
                                <div class='sucesso'>
                                    <h2>Personagem cadastrado com sucesso!</h2>
                                    <form method='post' action='./'>
                                        <button type='submit'>Jogar novamente</button>
                                        <input type='hidden' name = 'recomeco' value='true'>
                                    </form>
                                </div>
                            </div>
                        </body>
                    </html>
                ";
                recomeca();
                
                
                    
            }else{
                if($resultado!="repetido"){
                    $mensagem = "Houve um erro ao cadastrar o personagem!";
                }else{
                    $mensagem = "Você digitou características repetidas para 
                    tal personagem!";
                    
                     //removendo as características duplicadas
                    $array_aux = [];
                    
                    foreach($caracteristicas as $c){
                        array_push($array_aux,modStringComp($c));
                    }
                    
                    $caractaux = [];
                    
                    foreach($caracteristicas as $c){
                        if (array_count_values($array_aux)[modStringComp($c)]==1 ){
                            array_push($caractaux,$c);
                        }
                    }
                    
                    $caracteristicas = $caractaux;
                }
                
                $personagemID = $_POST['personagemID'];
                $formulario =  "
                    <html>
                        <header>
                            <link rel='stylesheet' href='../CSS/styles.css'>
                            <title>Futnator!</title>
                        </header> 
                        <body>
                            <div class='aviso'>
                        
                                <h2>$mensagem<br>
                                </h2>
                            
                                <form method = 'post' action = 'novoPersonagem.php'>
                                    <button type='submit'>Tente novamente</button>
                                    <input type='hidden' name='personagem' maxlength = '40' value='$nomePersonagem'><br>
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
                    
                    
                    die();
                    
            }
        
        
    }else{

    if(!isset($_POST['personagem'])){
        header("Location: ./");
    }else{

        if(!isset($_POST["caracteristicas"])){
            header("Location: ./");
        }
    
        $conexao = pegaConexao();
        
        $caracteristicas = $_POST["caracteristicas"];

        //aqui será feita uma formatação, sem acentos, sem maiusculas ou espaços,
        //bem como na hora de das verificações. Isso será feito com uma função
        //específica
        $nomePersonagem    =  mysqli_escape_string($conexao,$_POST['personagem']);
        $personagemID      = $_POST['personagemID'];
        $tipoPersonagem    = $_POST['tipoPersonagem'];
        
        fechaConexao($conexao);
        
        if($tipoPersonagem=="estádio"){
            $elementos = pesquisaFiltroEstadio($caracteristicas,"('')");
            $todos = pesquisaTodosEstadio();
        }else if($tipoPersonagem=="jogador"){
            $elementos = pesquisaFiltroJogador($caracteristicas,"('')");
            $todos = pesquisaTodosJogador();
        }else{
            $elementos = pesquisaFiltroTime($caracteristicas,"('')");
            $todos = pesquisaTodosTime();
        }
        
        $acao = "";
        
        //nome modificado para comparação
        $nomeComp = modStringComp($nomePersonagem);
        
        foreach($elementos as $e){
            if(modStringComp($e["nome"])==$nomeComp and $e["id"]==$personagemID){
                $acao = "selecionado";
                break;
                
            }else if(modStringComp($e["nome"])==$nomeComp){
                $acao = "contabilizado";
                break;
            }else{
                //verifica se existe um personagem de mesmo nome, para o tipo
                //do personagem
                 $acao = "novo";
            }
            
        }
        
       if($acao == "novo"){
           //Verifica se existe um personagem com o nome digitado, mas que não
           //possui qualquer das características 
           
            foreach($todos as $t){
                if(modStringComp($t["nome"])==$nomeComp){
                    echo "
                        <html>
                            <header>
                                <link rel='stylesheet' href='../CSS/styles.css'>
                                <title>Futnator!</title>
                            </header> 
                            <body>

                                <div class='aviso'>
                                
                                    <h2>Personagem digitado não contém tais características!<br>
                                    </h2>
                                
                                    <form method = 'post' action = './'>
                                        <button type='submit' name='respostaSugestao' value='nao'>Tente novamente</button>
                                        <input type='hidden' name='personagemSugerido' value='$personagemID'>
                                    </form>
                                
                                </div>
                            </body>
                        </html>

                    ";
                    
                    die();
                }
                
            }
            
                //add uma formatação incluindo o personagem associado via
                //chave estrangeira, num select
                $relacionado = "";
                
                if($tipoPersonagem=="jogador" || $tipoPersonagem=="time"){
                    //pego os valores relativos ao relacionamento de cada tipo
                    //de personagem
                
                    if($tipoPersonagem=="jogador" ){
                        $valores = pesquisaTodosTime();
                        $tipoRelacao = "Time";
                    }else{
                        $valores = pesquisaTodosEstadio();
                        $tipoRelacao = "Estádio";
                    }
                    
                     $relacionado.="
                        <label>$tipoRelacao: </label><select name='relacionamento'>
                            <option value='0'>Selecione</option>
                    ";        
            
                    //foreach para pegar os valores uma única vez
                    $relacoes = [];
                    
                    foreach($valores as $v){
                        $relacoes[$v['id']] = $v['nome'];
                    }
            
                    //foreach para preencher o select
                    foreach($relacoes as $id => $valor){
                        $relacionado.="
                             <option value='$id'>$valor</option>
                        ";
                    }
                    
                    $relacionado.="
                        </select><br>
                    ";
                    
                }
                
                //foreach para enviar as características
                    $inputsCaract = "";
                    foreach($caracteristicas as $c){
                        $inputsCaract.= "<input type='hidden' name='caracteristicas[]' value='$c'><br>";
                    }
                        
                $form =  "
                        <html>
                            <header>
                                <link rel='stylesheet' href='../CSS/styles.css'>
                                <title>Futnator!</title>
                            </header> 
                            <body>

                    
                        <div class='cadastro'>
                    
                            <h1>Vamos cadastrar um novo $tipoPersonagem!<br></h1>
                            
                            <div class='definidas'>
                            
                                <h2>Características já definidas</h2>
                                <ul class='caracteristicas'>
                                    %s
                                </ul>
                            
                            </div>
                            
                            <form id = 'addCarac'  method = 'post' action = 'novoPersonagem.php' enctype='multipart/form-data'>
                                <input type='hidden' name='personagemID' value='$personagemID'>
                                <label>Nome: </label><input type='text' name='nome' value='$nomePersonagem' readonly><br>
                                <label>Imagem: </label><input type='file' name='imagem' accept='image/png, image/jpeg'><br>
                                $relacionado
                                $inputsCaract
                                <label>Caracteristicas adicionais: </label><input id='numero' type='number' value='0' min='0' max='20'>
                                <input type='hidden' name='tipoPersonagem' value='$tipoPersonagem'><br>
                                <div id='campos'></div>
                                <button class = 'cadastrar' type='submit' name='cadastro' value='cadastro'>Cadastrar</button>
                                <button class = 'cancelar' type='submit' name='cadastro' value = 'reinicio' >Cancelar</button>
                            </form>
                        
                        </div>
                </body>
            </html>
                    
                     <!-- adição do arquivo javascript -->
  
                    <script language='javascript' src='../arquivosGerais/javascript/addCaracteristica.js'></script>
                    ";
                    
                    $itens = "";
                    
                    foreach($caracteristicas as $c){
                        $itens.="<li>$c</li>\n";
                    }
                    
                    $form = sprintf($form,$itens);
                    
                    echo $form;
            
       }else if ($acao=="contabilizado"){
            atualizaEscolhido($personagemID,$tipoPersonagem);
            echo "<script language='javascript'>window.location.href='./finalizado.php?ok=true'</script>";
       }else{
            echo "
                <html>
                    <header>
                        <link rel='stylesheet' href='../CSS/styles.css'>
                        <title>Futnator!</title>
                    </header> 
                    <body>
                        <div class='aviso'>
                          <h2>Personagem digitado foi o personagem sugerido!<br>
                            </h2>
                            <form method = 'post' action = './'>
                                <button type='submit' name='respostaSugestao' value='nao'>Tente novamente</button>
                                <input type='hidden' name='personagemSugerido' value='$personagemID'>
                            </form>
                            
                        </div>
                    </body>
                </html>
                    ";
                    
                    
       }
    }
    
    }

?>