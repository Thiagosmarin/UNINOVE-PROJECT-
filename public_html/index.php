<?php
    //Haverá estilização aqui também, mas no html lá em baixo, linkando o css
    //de um arquivo separado
    
    include_once "jogo/funcao.php";
    
    recomeca();
?>

<html>
    <header>
        <link rel="stylesheet" href="CSS/styles.css">
        <title>Futnator!</title>
    </header>
    
    <body >

        <div class = 'cadastro-inicio'>
            <h1>
                <u>Seja bem-vindo ao Futnator!</u> <img src="imagens/bola.png"alt=”some text” width= 40 height=40>
            </h1>
           
            <form action='jogo'>
                <div class="container">
                    <div style="border-style: none;">
                        <button class = 'inicio' type='submit'  style="background-color: transparent; border-color:transparent;">
                            <img src="imagens/apita.gif" height ="80" width="100" class="dvm-circle"/>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        
        <p> <b>Um projeto acadêmico baseado no jogo 
            <a href="https://pt.akinator.com/">Akinator</a>
            , possuindo a mesma premissa de perguntas com três tipos de resposta.</p>
    </body>

    <footer>
        2022
    </footer>
    
</html>

