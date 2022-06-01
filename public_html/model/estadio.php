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
    imagem,
    escolhido
    FROM
    Estadio
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
    idEstadio,
    caracteristica
    FROM
    CaracteristicaEstadio 
    WHERE 1=1
    ";
    
    foreach($strFiltro as $caracteristica){
        $sql.="\nAND caracteristica="."'".$caracteristica."'";
    }
    
    
    $pesquisa = mysqli_query($conexao,$sql);
    
    while ($linha = mysqli_fetch_array($pesquisa,MYSQLI_ASSOC)){
        $arrayPesquisa[$linha["idEstadio"]] = $linha;
    }
    

    fechaConexao($conexao);
    

    return $arrayPesquisa;
        
}

?>