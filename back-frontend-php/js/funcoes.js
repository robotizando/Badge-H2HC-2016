


function inputFocus(i){
    if(i.value==i.defaultValue){ i.value=""; i.style.color="#000"; }
}


function inputBlur(i){
    if(i.value==""){ i.value=i.defaultValue; i.style.color="#888"; }
}


// --------------------------------------------------------------------------------------------------
function getInternetExplorerVersion() {
	var rv = -1;
	if (navigator.appName == 'Microsoft Internet Explorer') {
		var ua = navigator.userAgent;
		var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		if (re.exec(ua) != null) {
			rv = parseFloat( RegExp.$1 );
		}
	}
	return rv;
}

// --------------------------------------------------------------------------------------------------
function isOnlyNumbers(sText) {
   var validChars = "0123456789";
   var isNumber=true;
   var char;

   for (i=0;i<sText.length && isNumber==true;i++){ 
      char = sText.charAt(i); 
      if (validChars.indexOf(char) == -1){
         isNumber = false;
      }
   }
   return (isNumber && (sText.length>0));   
}

// --------------------------------------------------------------------------------------------------
function getDigits(n) {
    return n.replace(/[^\d]+/g,'');
}

// --------------------------------------------------------------------------------------------------
function createBidimensionalArray(n1,n2){
	var a1 = new Array(n1);
	for(var i=0;i<n1;i++){
		a1[i] = new Array(n2);
	}
	return a1;
}

// --------------------------------------------------------------------------------------------------
function checkDate(d) {
	var date=d;
	var ardt=new Array;
	var ExpReg=new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
	ardt=date.split("/");
	err=false;
	if ( date.search(ExpReg)==-1){
		err = true;
		}
	else if (((ardt[1]==4)||(ardt[1]==6)||(ardt[1]==9)||(ardt[1]==11))&&(ardt[0]>30))
		err = true;
	else if ( ardt[1]==2) {
		if ((ardt[0]>28)&&((ardt[2]%4)!=0))
			err = true;
		if ((ardt[0]>29)&&((ardt[2]%4)==0))
			err = true;
	}
	return !err;
}

// --------------------------------------------------------------------------------------------------
function remove(str, sub) {
	i = str.indexOf(sub);
	r = "";
	if (i == -1) return str;
	r += str.substring(0,i) + remove(str.substring(i + sub.length), sub);
	return r;
}

// --------------------------------------------------------------------------------------------------
function checkCPF(cpf) {
	CPF = remove(cpf, ".");
	CPF = remove(CPF, "-");	
	if (CPF.length != 11 || CPF == "00000000000" || CPF == "11111111111" ||
	  	CPF == "22222222222" ||	CPF == "33333333333" || CPF == "44444444444" ||
	  	CPF == "55555555555" || CPF == "66666666666" || CPF == "77777777777" ||
	  	CPF == "88888888888" || CPF == "99999999999")
	return false;
	soma = 0;
	for(i=0; i < 9; i ++)
		soma += parseInt(CPF.charAt(i)) * (10 - i);
	resto = 11 - (soma % 11);
	if(resto == 10 || resto == 11)
		resto = 0;
	if(resto != parseInt(CPF.charAt(9)))
		return false;
	soma = 0;
	for(i = 0; i < 10; i ++)
		soma += parseInt(CPF.charAt(i)) * (11 - i);
	resto = 11 - (soma % 11);
	if(resto == 10 || resto == 11)
		resto = 0;
	if(resto != parseInt(CPF.charAt(10)))
		return false;
	return true;
}

// --------------------------------------------------------------------------------------------------
function checkCNPJ(cnpj) {
 
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if(cnpj == '') return false;
     
    if (cnpj.length != 14)
        return false;
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999")
        return false;
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;
           
    return true;
}

// --------------------------------------------------------------------------------------------------
function checkEmail(email) {
	if(/^[a-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,4}$/.test(email)){
		return true;
	} else {
		return false;
	}
}

// --------------------------------------------------------------------------------------------------
function disableScroll() {
	document.body.scroll = "no";
	document.body.style.overflow = "hidden";
	document.width = window.innerWidth;
	document.height = window.innerHeight;
	scroll(0, 0);
}


// --------------------------------------- Banco de dados -------------------------------------

function comboVeiculoTipo( id_combo ){
	
	$.ajax({
		async: false,
		type: "POST",
		url: "services/getVeiculoTipo.php",
		dataType: "json",
		data: "",
		error: function(e) {alert('ERRO DO SISTEMA: ' + e.responseText) },
		success: function(result) {
			if(result[0]){
				var html = "<option value='0'>Selecione o tipo do seu veículo...</option>  ";
				
				for(i=0;i<result.length;i++){
					html += '<option value="' + result[i].id_veiculo_tipo + '">' + result[i].descricao + '</option>'; 
				}
				
				$("#"+id_combo).html(html);
				lastResult = result;
			} else {
				alert("Erro de comunicação com o servidor. Clique OK para tentar novamente.");
				//window.location.href = currentPage + ".php";
			}
		}
	});
}


function comboVeiculoMarca( tipo,  id_combo ){
	
	$.ajax({
		async: false,
		type: "POST",
		url: "services/getVeiculoMarca.php",
		dataType: "json",
		data: "id_veiculo_tipo="+tipo ,
		error: function(e) {alert('ERRO DO SISTEMA: ' + e.responseText) },
		success: function(result) {
			if(result[0]){
				var html = "<option value='0'>Selecione a marca...</option>  ";
				
				for(i=0;i<result.length;i++){
					html += '<option value="' + result[i].id_veiculo_marca + '">' + result[i].descricao + '</option>'; 
				}
				
				$("#"+id_combo).html(html);
				lastResult = result;
			} else {
				alert("Erro de comunicação com o servidor. Clique OK para tentar novamente.");
				//window.location.href = currentPage + ".php";
			}
		}
	});
}



function comboVeiculoModelo( marca,  id_combo ){
	
	$.ajax({
		async: false,
		type: "POST",
		url: "services/getVeiculoModelo.php",
		dataType: "json",
		data: "id_veiculo_marca="+marca ,
		error: function(e) {alert('ERRO DO SISTEMA: ' + e.responseText) },
		success: function(result) {
			if(result[0]){
				var html = "<option value='0'>Selecione o modelo...</option>  ";
				
				for(i=0;i<result.length;i++){
					html += '<option value="' + result[i].id_veiculo_modelo + '">' + result[i].descricao + '</option>'; 
				}
				
				$("#"+id_combo).html(html);
				lastResult = result;
			} else {
				alert("Erro de comunicação com o servidor. Clique OK para tentar novamente.");
				//window.location.href = currentPage + ".php";
			}
		}
	});
}












