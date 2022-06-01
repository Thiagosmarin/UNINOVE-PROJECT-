<?php

include_once "conexao.php";

//essa função pegará tudo no banco
function pesquisaTodos(){
    $conexao = pegaConexao();
    
    $arrayPesquisa = [];
    
    $sql = "
    SELECT
    id,
    nome,
    idTime,
    imagem,
    escolhido
    FROM
    Jogador
    ";
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["id"]] = $linha;
    }
    
    fechaConexao($conexao);
    
    return $arrayPesquisa;
}

//O filtro depende das características confirmada pelo usuário
function pesquisaFiltro($strFiltro){
    //strFiltro é um array de características
    
    $conexao = pegaConexao();
    
    $arrayPesquisa = [];
    
    $sql = "
    SELECT
    idJogador,
    caracteristica
    FROM
    CaracteristicaJogador 
    WHERE 1=1
    ";
    
    foreach($strFiltro as $caracteristica){
        $sql.="\nAND caracteristica="."'".$caracteristica."'";
    }
    
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["idJogador"]] = $linha;
    }
    

    fechaConexao($conexao);
    

    return $arrayPesquisa;
        
}

?>