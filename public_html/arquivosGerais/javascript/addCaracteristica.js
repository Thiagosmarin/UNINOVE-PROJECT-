function criaCaract(){
    div = form.querySelector("div#campos");
    
    //adição dos campos de acordo com a quantidade de 
    
    campos = "";
    
    valor = this.value;
    
    for(i=0;i<valor;i++){
        campo = "<input type='text' name='caracteristicas[]' maxlength='50'><br>"
        
        campos+=campo;
    }
    
    div.innerHTML = campos;
    
}

form = document.querySelector("form#addCarac");

numero = form.querySelector("input#numero");

numero.addEventListener("change",criaCaract);



