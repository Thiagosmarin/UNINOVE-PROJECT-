<?php

include_once "conexao.php";

//essa função pegará tudo no banco
function pesquisaTodosEstadio(){
    $conexao = pegaConexao();
    
    $arrayPesquisa = [];
    
    $sql = "
    SELECT
    id,
    nome,
    imagem,
    caracteristica,
    escolhido
    FROM
    Estadio E
    INNER JOIN
    CaracteristicaEstadio CE
    ON
    E.id = CE.idEstadio
    ";
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["id"]."+".$linha["caracteristica"]] = $linha;
    }
    
    fechaConexao($conexao);
    
    return $arrayPesquisa;
}

//O filtro depende das características confirmada pelo usuário
function pesquisaFiltroEstadio($strFiltro,$idsDescon){
    //strFiltro é um array de características
    
    $conexao = pegaConexao();
    
    $arrayPesquisa = [];
    
   $sql = "
    SELECT
    id,
    nome,
    imagem,
    caracteristica,
    escolhido
    FROM
    Estadio E
    INNER JOIN
    CaracteristicaEstadio CE
    ON
    E.id = CE.idEstadio
    WHERE
    E.id in (select idEstadio from CaracteristicaEstadio where caracteristica in (''%s))
    AND
    E.id not in $idsDescon
    ";
    
    $valores = "";
    
    foreach($strFiltro as $caracteristica){
        $valores.=","."'".$caracteristica."'";
    }
    
    $sql = sprintf($sql,$valores);
    
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["id"]."+".$linha["caracteristica"]] = $linha;
    }
    

    fechaConexao($conexao);
    

    return $arrayPesquisa;
        
}

//essa função pegará tudo no banco
function pesquisaTodosJogador(){
    $conexao = pegaConexao();
    
    $arrayPesquisa = [];
    
    $sql = "
    SELECT
    J.id,
    J.nome,
    J.imagem,
    J.idTime,
    caracteristica,
    J.escolhido,
    T.nome as nomeTime,
    T.imagem as imagemTime
    FROM
    Jogador J
    INNER JOIN
    CaracteristicaJogador CJ
    ON
    J.id = CJ.idJogador
    LEFT JOIN
    Time T
    ON
    J.idTime=T.id
    ";
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["id"]."+".$linha["caracteristica"]] = $linha;
    }
    
    fechaConexao($conexao);
    
    return $arrayPesquisa;
}

//O filtro depende das características confirmada pelo usuário
function pesquisaFiltroJogador($strFiltro,$idsDescon){
    //strFiltro é um array de características
    
    $conexao = pegaConexao();
    
    $arrayPesquisa = [];
    
   $sql = "
     SELECT
    J.id,
    J.nome,
    J.imagem,
    J.idTime,
    caracteristica,
    J.escolhido,
    T.nome as nomeTime,
    T.imagem as imagemTime
    FROM
    Jogador J
    INNER JOIN
    CaracteristicaJogador CJ
    ON
    J.id = CJ.idJogador
    LEFT JOIN
    Time T
    ON
    J.idTime=T.id
    WHERE
    J.id in (select idJogador from CaracteristicaJogador where caracteristica in (''%s))
    AND
    J.id not in $idsDescon
    ";
    
    $valores = "";
    
    foreach($strFiltro as $caracteristica){
        $valores.=","."'".$caracteristica."'";
    }
    
    $sql = sprintf($sql,$valores);
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["id"]."+".$linha["caracteristica"]] = $linha;
    }
    

    fechaConexao($conexao);
    

    return $arrayPesquisa;
        
}

//essa função pegará tudo no banco
function pesquisaTodosTime(){
    $conexao = pegaConexao();
    
    $arrayPesquisa = [];
    
    $sql = "
    SELECT
    T.id,
    T.nome,
    T.imagem,
    T.idEstadio,
    caracteristica,
    T.escolhido,
    E.nome as nomeEstadio,
    E.imagem as imagemEstadio
    FROM
    Time T
    INNER JOIN
    CaracteristicaTime CT
    ON
    T.id = CT.idTime
    LEFT JOIN 
    Estadio E
    on T.idEstadio = E.id
    ";
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["id"]."+".$linha["caracteristica"]] = $linha;
    }
    
    fechaConexao($conexao);
    
    return $arrayPesquisa;
}

//O filtro depende das características confirmada pelo usuário
function pesquisaFiltroTime($strFiltro,$idsDescon){
    //strFiltro é um array de características
    
    $conexao = pegaConexao();
    
    $arrayPesquisa = [];
    
   $sql = "
    SELECT
    T.id,
    T.nome,
    T.imagem,
    T.idEstadio,
    caracteristica,
    T.escolhido,
    E.nome as nomeEstadio,
    E.imagem as imagemEstadio
    FROM
    Time T
    INNER JOIN
    CaracteristicaTime CT
    ON
    T.id = CT.idTime
    LEFT JOIN 
    Estadio E
    on T.idEstadio = E.id
    WHERE
    T.id in (select idTime from CaracteristicaTime where caracteristica in (''%s))
    AND
    T.id not in $idsDescon
    ";
    
    $valores = "";
    
    foreach($strFiltro as $caracteristica){
        $valores.=","."'".$caracteristica."'";
    }
    
    $sql = sprintf($sql,$valores);
    
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["id"]."+".$linha["caracteristica"]] = $linha;
    }
    

    fechaConexao($conexao);
    

    return $arrayPesquisa;
        
}

function atualizaEscolhido($id,$tipoPersonagem){
    //atualiza o número de vezes que um personagem foi escolhido
    
    $conexao = pegaConexao();
    
    $sql = "
    UPDATE
    %s
    SET
    escolhido = escolhido + 1
    WHERE
    id = %d
    ";
    
    if($tipoPersonagem=="estádio"){
        $tabela = "Estadio";
    }else if($tipoPersonagem=="time"){
        $tabela = "Time";
    }else{
        $tabela = "Jogador";
    }
    
    $sql = sprintf($sql,$tabela,$id);
    
    $transacao = mysqli_query($conexao,$sql);
    
    $resultado = true;
    
    if(!$transacao){
        $resultado =  false;
    }
    

    fechaConexao($conexao);
    

    return $resultado;
}

function inserePersonagem($tipoPersonagem,$nome,$caracteristicas,$imagem="",$relacao=""){
    //insere um novo personagem e suas características
    
    $conexao = pegaConexao();
    
    $campoRelacao = "";
    
    if($tipoPersonagem=="estádio"){
        $tabela = "Estadio";
    }else if($tipoPersonagem=="time"){
        $tabela = "Time";
        $campoRelacao = "idEstadio";
    }else{
        $tabela = "Jogador";
        $campoRelacao = "idTime";
    }
    
    
    //sql para a criação do personagem
    $sql = "
    INSERT INTO
    $tabela
    (nome,imagem%s)
    VALUES
    ('$nome','$imagem'%s);
    ";
    
    if($campoRelacao!="" and $relacao!="0"){
        $sql = sprintf($sql,",".$campoRelacao,",'".$relacao."'");
    }else{
        $sql = sprintf($sql,"","");
    }
    
    
    $transacao = mysqli_query($conexao,$sql);
    
    $sql = "SELECT LAST_INSERT_ID() as id;";
    
    $transacao = mysqli_query($conexao,$sql);
    

    if($transacao){
        //insere as características relativas ao personagem, por isso devo
        //pegar o id do novo personagem criado, que é o que aquela função
        //no select acima faz
        $id = ""; 

        while ($linha = mysqli_fetch_array($transacao,MYSQLI_ASSOC)){
            $id = $linha["id"];
        }
        
        
        $valores = "";
        
        for($i=0;$i<count($caracteristicas);$i++){
            if($i==count($caracteristicas)-1){
                $valores.="($id,'".$caracteristicas[$i]."') \n";
                
            }else{
                $valores.="($id,'".$caracteristicas[$i]."'), \n";
            }
            
        }
        
        $sql = "
            INSERT INTO
            Caracteristica$tabela
            (id$tabela,caracteristica)
            VALUES
            $valores
            ";
        
        $transacao = mysqli_query($conexao,$sql);
        
        if($transacao){
            return $id;
        }else{
            //deleta o personagem criado
             $sql = "
            DELETE from
            $tabela
            WHERE
            id = $id;
            ";
        
            $transacao = mysqli_query($conexao,$sql);
            
            
            return "repetido";
        }
        
    
    }else{
        return false;
    }
    
    
    

    fechaConexao($conexao);
}
    
?>