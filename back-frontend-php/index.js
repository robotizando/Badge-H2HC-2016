

var postou = false;



function checkForm(){
	
	
	if(!checkEmail($("#email").val())){
		document.getElementById("mensagem").innerHTML = ("O campo EMAIL é obrigatório e deve conter um endereço válido.");
		$("#email").focus();
		return false;
	}
	
	if($("#senha").val().length==0 ){
		document.getElementById("mensagem").innerHTML = ("Preencha a senha");
		$("#senha").focus();
		return false;
	}
	
	return true;
	
}


$(document).ready( function(){

	//$.watermark.options.className = 'field_watermark';
	
	$("#email").watermark("e-mail", {className: 'field_watermark'});
	$("#senha").watermark("senha", {className: 'field_watermark'});

	var email = $.cookie('email');
	
	if( email != undefined )
		$("#email").val( email );
	
	
	$("#btn_acessa").click(function(e){
		
		if( checkForm() ){
		
			if( !postou ){
				
				postou = true;
				
				$.ajax({
					async: false,
					type: "POST",
					url: "services/login.php",
					dataType: "json",
					data: $('#login_form').serialize(),
					error: function(result) {
						document.getElementById("mensagem").innerHTML = ('ERRO DO SISTEMA: ' + e.responseText);
						postou = false;	
					},
					success: function(result) {
						
						if( result[0].result == 1 ){
							
							var redir = 'estatisticas.php?log=y';
							
							//$.cookie('email', $("#email").val() , { expires: 7 });
							//alert( redir );
							
							//document.location.href = redir;	
							window.location.href = redir;
							
							
						} else {
							
							//alert( result[0].result );
							document.getElementById("mensagem").innerHTML = result[0].result;
							
						}
						
						postou = false;
					}
				});
			}
			
		}
		
	});

	
});