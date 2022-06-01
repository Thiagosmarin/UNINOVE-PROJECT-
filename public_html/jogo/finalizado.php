<?php
    include_once "./funcao.php";

    if(isset($_GET['ok'])){
        recomeca();
        if (isset($_POST['recomeco'])){
            header("Location: ./");
        }else if($_GET['ok']=="true"){
            echo "
            <html>
                <header>
                    <link rel='stylesheet' href='../CSS/styles.css'>
                    <title>Futnator!</title>
                </header> 
                <body>
                    
                        <div class='aviso'>
                            <div class='sucesso'>
                                <h2>Resposta registrada com sucesso!</h2>
                                <form method='post'>
                                    <button type='submit'>Jogar novamente</button>
                                    <input type='hidden' name = 'recomeco' value='true'>
                                </form>
                            </div>
                        </div>
                    
                </body>
            </html>
                ";
            die();
            
                   
        }
        
    }else{
        recomeca();
        header("Location: ./");
    }
   



?>