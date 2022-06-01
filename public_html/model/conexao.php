<?php
    function pegaConexao(){
            //$abertura = fopen("arquivosGerais/bancoDeDados/conexao.txt","r");
            
            //$senha = fgets($abertura);
            
            //fclose($abertura);
        
            $conexao = mysqli_connect("localhost","id18528817_oraculo","#Bamemerindus2014","id18528817_chatbot");
            
            if(!$conexao){
                die("<h1>Houve uma falha ao conectar-se ao banco de dados!</h1>");
                //futura função de erro aqui
                
            }else{
                return $conexao;
            }
    
    }
    
function fechaConexao($conexao){
    mysqli_close($conexao);
}



?>