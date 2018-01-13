/* AUTOR

	(desconhecido)
	não encontrei a autoria na internet. Espero conseguir saber quem fez para dar os créditos

	Revisado por:
	Daniel Omar Basconcello Filho
	2017
	daniel@robotizando.com.br
	Faço questão de salientar que esse código é SOFTWARE LIVRE... CUMPRA AS 4 Liberdades!! 
	Os termos da licença "GPL" estão ai abaixo:
*/

/*
    Este programa é um software livre; você pode redistribuí-lo e/ou 
    modificá-lo sob os termos da Licença Pública Geral GNU como publicada
    pela Fundação do Software Livre (FSF); na versão 3 da Licença,
    ou (a seu critério) qualquer versão posterior.

    Este programa é distribuído na esperança de que possa ser útil, 
    mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO
    a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a
    Licença Pública Geral GNU para mais detalhes.

    Você deve ter recebido uma cópia da Licença Pública Geral GNU junto
    com este programa. Se não, veja <http://www.gnu.org/licenses/>.
*/


void pisca_escrevendo(){

  for( int x=0; x<5; x++){
    digitalWrite(LED_ESCREVENDO, HIGH);
    delay(50);
    digitalWrite(LED_ESCREVENDO, LOW);
    delay(50);

  }
  
}

void listaMenu(){
    Serial.println("");
    Serial.println(" ____ ____ ___ ____    _                                ");                                  
    Serial.println("/ ___/ ___|_ _|  _ \\  | |    ___   __ _  __ _  ___ _ __ ");
    Serial.println("\\___ \\___ \\| || | | | | |   / _ \\ / _` |/ _` |/ _ \\ '__|");
    Serial.println(" ___) |__) | || |_| | | |__| (_) | (_| | (_| |  __/ |   ");
    Serial.println("|____/____/___|____/  |_____\\___/ \\__, |\\__, |\\___|_|   ");
    Serial.println("                                  |___/ |___/           ");
    Serial.println("--------------------------------------------------------");          
    Serial.println("----------------- MENU DE COMANDOS ---------------------");
    Serial.println("--------------------------------------------------------");
    Serial.println("    l - Lista os dados capturados");
    Serial.println("    D - Deleta os dados capturados");
    Serial.println("    d - lista o diretorio raiz do SPIFFS");
    Serial.println("    g - exibe a captura atual");
    Serial.println("    h - lista o menu de comandos disponiveis");

    Serial.println("");
    Serial.println("> ");
}


void listaDiretorio(){
      Dir dir = SPIFFS.openDir("/");
      Serial.println("");
        while (dir.next()) {
          Serial.print(dir.fileName());
          Serial.print("\t - Tamanho: ");
          File file = dir.openFile("r");
          Serial.print(file.size());
          Serial.println(" bytes");
          file.close();
        }
}


/** Is this an IP? */
boolean isIp(String str) {
  for (int i = 0; i < str.length(); i++) {
    int c = str.charAt(i);
    if (c != '.' && (c < '0' || c > '9')) {
      return false;
    }
  }
  return true;
}

/** IP to String? */
String toStringIp(IPAddress ip) {
  String res = "";
  for (int i = 0; i < 3; i++) {
    res += String((ip >> (8 * i)) & 0xFF) + ".";
  }
  res += String(((ip >> 8 * 3)) & 0xFF);
  return res;
}


void corre_apaga(){
  for(int x =0; x< qtd_led; x++)
  digitalWrite(led[x], LOW);  
  
  for(int x =0; x< qtd_led; x++){
    digitalWrite(led[x], HIGH);  
    delay(100);              // wait for a second
  }

  for(int x =0; x< qtd_led; x++){
    digitalWrite(led[x], LOW);  
    delay(100);              // wait for a second
  }
 
}
