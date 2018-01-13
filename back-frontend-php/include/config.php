<?php 

$nome_sistema 	= "data_analisys";

$conn_banco   = 'teste';            // nome do banco
$conn_host    = '172.17.0.3';   		// host
$conn_usuario = 'root';       // usuario
$conn_senha   = '1qaz2wsx';       // senha
$conn_port		= "3306";

$cmd			= "php ";


//terminar os caminhos com barra

$caminho_base 			= "/home/phantor/workspaces/workspace-php/";
$caminho_absoluto 	= $caminho_base."/".$nome_sistema."/";
$caminho_log 				= $caminho_base."/".$nome_sistema."/services/logs/";
$caminho_services 	= $caminho_base."/".$nome_sistema."/services/";
$pathUploadTmp 		= $caminho_base."/".$nome_sistema."/services/tmp/";

date_default_timezone_set('America/Sao_Paulo');

?>