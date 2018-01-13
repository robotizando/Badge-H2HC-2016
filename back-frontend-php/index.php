
<!DOCTYPE html>
<html>
<head>
<title>H2HC - Mapa de resultados</title>

<link rel="stylesheet" media="screen" type="text/css" href="css/fonts.css" />
<link rel="stylesheet" media="screen" type="text/css" href="css/badge.css" />


<script type="text/javascript"></script>

	
		<div class="login-painel">
			
				<div>
					<span class="logo-badge">SSID Geo Logger</span><br>
					<span>Badge H2Hc - ESP8266 Concept Proof</span>
				</div>
				
				<div style="width: 100%; height: 100%; text-align: center; margin-top: 24px" >
		
					<form action="mapa.php" method="post" enctype="multipart/form-data">
					
							<div style="margin-top: 8px;">
								<label for="file">File to upload:</label> <input type="file" name="file" id="file"/>
							</div>
										
							<div style="margin-top: 8px;">
								<span id="mensagem" style="color:#FF0000"></span>
							</div>
							
							<div style="margin-top: 8px;">
								<p>	<input id="uploadFile" name="uploadFile" class="login_botao" type="submit" value="Send file"></p>
							</div>
							
					</form>
					
					
						<form action="mapa.php" method="post" enctype="multipart/form-data">
					
							<div style="margin-top: 8px;">
								<label for="file">Data to upload:</label> <textarea type="data" name="data" id="data" rows="10" cols="80"> </textarea>
							</div>
										
							<div style="margin-top: 8px;">
								<span id="mensagem" style="color:#FF0000"></span>
							</div>
							
							<div style="margin-top: 8px;">
								<p>	<input id="sendData" name="sendData" class="login_botao" type="submit" value="Send data"></p>
							</div>
							
					</form>
					
				</div>
				
		</div>
		

</body>
</html>
		
