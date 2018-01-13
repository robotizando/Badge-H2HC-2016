<?php

include_once '../include/config.php';
include_once '../include/constantes.php';
include_once '../include/functions.php';
include_once '../include/database.php';
	

$opts = array(
		'http'=>array(
				'method'=>"GET",
				'header'=>"Accept-language: en\r\n" .
				"Cookie: foo=bar\r\n"
		)
);

$context = stream_context_create($opts);

	
$sql = "select id_veiculo_marca from veiculo_marca where id_veiculo_marca";
$stmt = $conn->prepare($sql);
//$stmt->bindParam("usuario", $usuario );
$stmt->execute();

if( $stmt->rowCount() > 0 ){
	
	echo "Marcas ".$stmt->rowCount()."<br>";

	while ($row = $stmt->fetch(PDO::FETCH_OBJ, PDO::FETCH_ORI_NEXT)){	
	
		//chama pagina dos caras para popular os modelos
		$marca = $row->id_veiculo_marca;
		
		echo "Marca - ".$marca."<br>\n";
		
		$url = "http://www.carrobrasil.com.br/includes/ajax/combo_modelo.php?idMarca=".$marca;
		// Open the file using the HTTP headers set above
		$file = file_get_contents( $url , false, $context);
		
		//echo $file."<br>";
		
		$modelos = explode("|", $file);
		
		for($x=0; $x < count( $modelos ); $x++ ){
		
			$modelo = explode("¥", $modelos[$x] );
			

		
			if( count($modelo) > 1 ){
				$n = $modelo[0];
				$t =  $modelo[1];

				//echo $modelos[$x]."\n<br>";
				
				$sql = "insert into veiculo_modelo(id_veiculo_modelo, id_veiculo_marca,  descricao) values (:n, :marca, :t);";
				$stmt2 = $conn->prepare( $sql );
				$stmt2->bindParam("n", $n );
				$stmt2->bindParam("marca", $marca );
				$stmt2->bindParam("t", $t );
				$stmt2->execute();
				
				//$sql = "insert into veiculo_modelo(id_veiculo_modelo, id_veiculo_marca,  descricao) values ($n, $marca, '$t');";
				//echo $sql."<br>\n";
		
			}
		
		}
		
		
		
	} //fim do laço de marcas
}
	










?>