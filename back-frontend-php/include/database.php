<?php


Class SafePDO extends PDO {

	public static function exception_handler($exception) {
		// Output the exception details
		die('Uncaught exception: '.$exception->getMessage());
	}

	public function __construct($dsn, $username='', $password='', $driver_options=array()) {

		// Temporarily change the PHP exception handler while we . . .
		set_exception_handler(array(__CLASS__, 'exception_handler'));

		// . . . create a PDO object
		parent::__construct($dsn, $username, $password, $driver_options);

		// Change the exception handler back to whatever it was before
		restore_exception_handler();
	}

}


$conn_string = "mysql:host=".$conn_host.";port=".$conn_port.";dbname=".$conn_banco;
$conn = new  SafePDO($conn_string, $conn_usuario, $conn_senha);


function close_database(){
	global $conn;
	 $conn = null;
}


function logSistema($tipo ){
	
	
	
	
}

function executeInsert($conexao, $tabela, $dados ){

	
	$sql = "insert into ".$tabela." (";
	foreach ($dados as $key => $value) {
		$sql = $sql.$key.",";
	}
	$sql = substr($sql , 0, strlen($sql)-1 );
	$sql = $sql.") values (";
	foreach ($dados as $key => $value) {
		$sql = $sql.":".$key.",";
	}
	$sql = substr($sql , 0, strlen($sql)-1 );
	$sql = $sql.");";
	
	$stmt = $conexao->prepare($sql);
	
	foreach ($dados as $key => $value) {
		//echo $key."-".$value."\n";
		$k = "".$key;
		$stmt->bindValue($k, $value );
	}

	
	$stmt->execute();
	//$stmt->debugDumpParams();
	
	$erro = $stmt->errorInfo();
	
	return $erro;
	
	//die();
}

function executeUpdate($conexao, $tabela, $dados, $condicao ){

	$sql = "update ".$tabela." set ";
	
	foreach ($dados as $key => $value) {
		$sql = $sql.$key."= :".$key.",";
	}
	$sql = substr($sql , 0, strlen($sql)-1 );

	$sql = $sql." where ";
	foreach ($condicao as $key => $value) {
		$sql = $sql.$key."= :".$key.",";
	}
	$sql = substr($sql , 0, strlen($sql)-1 );
	$sql = $sql.";";

	$stmt = $conexao->prepare($sql);

	foreach ($dados as $key => $value) {
		$k = "".$key;
		$stmt->bindValue($k, $value );
		//echo $k." - ".$value."<br>"  ;
	}

	foreach ($condicao as $key => $value) {
		$k = "".$key;
		$stmt->bindParam($k, $value );
		//echo $k." - ".$value."<br>"  ;
		
	}
	
	//echo "<br><br>";
	//echo $sql;
	
	//$stmt->debugDumpParams();
		
	//echo "<br><br>";
	
	$stmt->execute();

	

	$erro = $stmt->errorInfo();

	//var_dump( $erro );
	return $erro;
	
	//var_dump( $erro );

	//die();
}

function executeSelect($conexao, $sql, $parametros, $ordem = array()){
	
	if( $parametros == null )
		$parametros = array();
	

	if( count($parametros) > 0 ){
		$sql = $sql." where ";
		
		foreach ($parametros as $key => $value) {
			//echo $key."-".$value."\n";
			$k = "".$key;
			$sql = $sql." ".$k." = :".$k." and ";
		}
		
		$sql = substr($sql , 0, strlen($sql)-5 );
	}

	
	
	if( count($ordem) > 0 ){
		$sql = $sql." order by ";
	
		foreach ($ordem as $key => $value) {
			//echo $key."-".$value."\n";
			$k = "".$key;
			$sql = $sql." ".$k." ".$value." , ";
		}
		
		$sql = substr($sql , 0, strlen($sql)-2 );
	}	
	


	

	$stmt = $conexao->prepare($sql);

	foreach ($parametros as $key => $value) {
		$k = "".$key;
		$stmt->bindValue($k, $value );
	}

	$stmt->execute();
	//$stmt->debugDumpParams();

	$erro = $stmt->errorInfo();
	//echo var_dump( $erro );
	
	return $stmt;

}

function arrayToInsert($tableName, $dataArray, $dbtype = 1 ){

	global $DBTYPE_MYSQL, $DBTYPE_SQLITE;

	$str = "insert into ".$tableName." (";
	$values = "";
	
	foreach ($dataArray as $fieldName => $fieldValue) {
		$str = $str.$fieldName.",";
		
		if( is_numeric($fieldValue) )
			$values = $values.$fieldValue.",";	
		else {
			
			if( $dbtype == $DBTYPE_SQLITE && strlen($fieldValue) > 1024 )
				$values = $values."X'".$fieldValue."',";
			else if( $dbtype == $DBTYPE_MYSQL && strlen($fieldValue) > 1024 )
				$values = $values."'".$fieldValue."',";
			else {
				$fieldValue = str_replace("'", "''", $fieldValue);
				$values = $values."'".$fieldValue."',";
			}
				
		}
	}
	
	$str = substr($str, 0, -1); //remove a ultima virgula
	$values = substr($values, 0, -1); //remove a ultima virgula
	
	//concatena tudo
	$str = $str.") values (". $values. ");";
	
	return $str;
}


function arrayToUpdate($tableName, $dataArray, $whereArray, $dbtype = 1 ){

	global $DBTYPE_MYSQL, $DBTYPE_SQLITE;
	
	$str = "update ".$tableName." set ";
	
	foreach ($dataArray as $fieldName => $fieldValue) {
		$str = $str.$fieldName."=";
		
		if( is_numeric($fieldValue) )
			$str = $str.$fieldValue.",";	
		else  {
			if( $dbtype == $DBTYPE_SQLITE && strlen($fieldValue) > 1024 )
				$str = $str."X'".$fieldValue."',";	
			else 
				$str = $str."'".$fieldValue."',";				
		}
		
	}
	$str = substr($str, 0, -1); //remove a ultima virgula
	$str = $str." where ";

	//Monta o where
	foreach ($whereArray as $fieldName => $fieldValue) {
		
		
		$str = $str.$fieldName."=";
		
		if( is_numeric($fieldValue) )
			$str = $str.$fieldValue." and ";	
		else 
			$str = $str."'".$fieldValue."' and ";
	}
	$str = substr($str, 0, -5); //remove o ultimo and
	
	return $str;
}


function getImageTemp( $filename ){

	$data = '';
	if( $filename == '' ){
		return $data; 
	}
		
	global $pathUploadTmp;
	$file = $pathUploadTmp.$filename;
	
	if( file_exists( $file ) ){
		$fp      = fopen($file, 'r');
		$data = fread($fp, filesize($file));
		fclose($fp);
	}
	
	return $data; 
}


function getTmpFileData( $filename ){

	$data = '';
	if( $filename == '' ){
		return $data; 
	}
		
	global $pathUploadTmp;
	$file = $pathUploadTmp.$filename;
	
	if( file_exists( $file ) ){
		$fp      = fopen($file, 'r');
		$data = fread($fp, filesize($file));
		$data = addslashes($data);
		fclose($fp);
		unlink( $file );
	}
	
	return $data; 
}

function cleanQuery($string) {
	
  if(get_magic_quotes_gpc()){
    $string = stripslashes($string);
  }
  if(phpversion()>='4.3.0'){
    $string = mysql_real_escape_string($string);
  } else {
    $string = mysql_escape_string($string);
  }
  return $string;
}


?>
