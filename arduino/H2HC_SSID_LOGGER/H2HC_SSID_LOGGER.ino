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


#include <SoftwareSerial.h>
#include <TinyGPS++.h>
#include "FS.h"
#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <DNSServer.h>
#include <ESP8266mDNS.h>
#include <EEPROM.h>

#define qtd_led  7
int led[qtd_led] = {  D1, D3, D2, D4, D6, D5, D7 };


// Variaveis do Parser NMEA strings
int gpsValido = 0;

//Variaveis para tomada de tensão na bateria
int vin = 0;


const char *softAP_ssid = "HACKUDO_CUIDADO";
const char *softAP_password = "12345678";

/* hostname for mDNS. Should work at least on windows. Try http://esp8266.local */
const char *myHostname = "esp8266.local";

/* Don't set this wifi credentials. They are configurated at runtime and stored on EEPROM */
char ssid[32] = "";
char password[32] = "";

// DNS server
const byte DNS_PORT = 53;
DNSServer dnsServer;

// Web server
ESP8266WebServer server(80);

/* Soft AP network parameters */
IPAddress apIP(192, 168, 4, 1);
IPAddress netMsk(255, 255, 255, 0);


//Serial por software para leitura do GPS
SoftwareSerial gpsSerial(D8,D7); // RX, TX
// The TinyGPS++ object
static TinyGPSPlus gps;


File f;
boolean escreve = false;

#define LED_ESCREVE   D1
#define LED_GPS_LOCK  D2
#define BTN_ESCREVE   D0
#define LED_ESCREVENDO   D3


void setup() {
  
  for(int x =0; x< qtd_led; x++)
    pinMode(led[x], OUTPUT);

  pinMode(BTN_ESCREVE, INPUT);
  pinMode(LED_ESCREVE, OUTPUT);
  pinMode(LED_GPS_LOCK, OUTPUT);
  
  //porta analógica
  pinMode( A0, INPUT );

  Serial.begin(115200);
  Serial.println();
  Serial.print("Inicializando...");
  
  corre_apaga();
      
  SPIFFS.begin();
  gpsSerial.begin(9600);


  escreve = false;

  listaMenu();

}

unsigned long int millis_escrita = 0;
#define ESCRITA_TIMEOUT 10000UL

int bin = 0;

void loop() {

  //liga e desliga o logger
  if( digitalRead( D0 ) == HIGH ){

    if( !escreve ){
      digitalWrite( LED_ESCREVE, HIGH );
      escreve = true;
      Serial.println("Iniciando registro de dados");
         
    } else {
    
      digitalWrite( LED_ESCREVE, LOW );
      escreve = false;
      Serial.println("Finalizando registro de dados");
     
    }

    delay(300);
  
  }



  //carrega o objeto GPS com os dados do dispositivo via software Serial
  while (gpsSerial.available() > 0)
    gps.encode(gpsSerial.read());

  //acende o led GPS LOCK quando já existirem dados validos
  if (gps.location.isValid())  {
      digitalWrite( LED_GPS_LOCK, HIGH );
  } else {
      digitalWrite( LED_GPS_LOCK, LOW );
  }


  //----------- Menu de comandos -----------
  if( Serial.available() ){
     bin = Serial.read();

     Serial.println( bin );

     if( bin == 'h'){ //Lista o menu de opções
       listaMenu();
     } 


     if( bin == 'l'){

        f = SPIFFS.open("/dados.txt", "r");
        if (!f) {
           Serial.println("file open failed");
        }  
        
        Serial.println("====== Lendo arquivo dados.txt  =======");
        while( f.available() ){
          String s=f.readStringUntil('\n');
          Serial.println(s);
        }
        Serial.println("======  Fim do arquivo dados.txt   =======");
        f.close();
      
     } 

     if( bin == 'd'){ //Lista os arquivos e os tamanhos

        listaDiretorio();

     }
 
     
     if( bin == 'D'){
        Serial.println("Deletando arquivo");      
        SPIFFS.remove("/dados.txt");
        Serial.println("Arquivo deletado...");      
        
     }
  
    if( bin == 'g'){

        Serial.println( lista_ssid( ) );      
        
     }
     

    
  }  //final de serial available

/*
  Serial.print(millis());
  Serial.print( " - ");
  Serial.println( millis_escrita + ESCRITA_TIMEOUT );
*/

  //realiza o log de tempos em tempos  
  if( millis() > millis_escrita + ESCRITA_TIMEOUT ) {
    
    if( escreve && gps.location.isValid() ){

      f = SPIFFS.open("/dados.txt", "a+");
      f.println( lista_ssid( ) );
      f.close();
      
      Serial.println("escrevendo dados no arquivo...");

      pisca_escrevendo();
    }

    millis_escrita =  millis();
  }
  

  //DNS
  dnsServer.processNextRequest();
  
  //HTTP
  server.handleClient();
}


String lista_ssid() {

//ssid[32];  encryptionType;  RSSI;  lat;  lng; bat; 

 String info = "";
 vin = analogRead( A0 );
 int n = WiFi.scanNetworks();
 
 if (n > 0 && gps.location.isValid() && gps.date.isValid()) {
    for (int i = 0; i < n; i++) {
      info = info + WiFi.SSID(i) + "|";
      info = info + printEncryptionType( WiFi.encryptionType(i) ) + "|";
      info = info + WiFi.RSSI(i) + "|";
      info = info + String( gps.location.lat(), 8) + "|";
      info = info + String( gps.location.lng(), 8) + "|";
      info = info + String(vin) + "|\n";
    }
  } else {
    //info = info + "GPS INVALID"; 
    
  }

  return info;
  
}


void gpsInfo()
{
  Serial.print(F("Location: ")); 
  if (gps.location.isValid())
  {
    gpsValido = 1;
    Serial.print(gps.location.lat(), 6);
    Serial.print(F(","));
    Serial.print(gps.location.lng(), 6);
  }
  else
  {
    Serial.print(F("INVALID"));
    gpsValido = 0;
  }

  Serial.print(F("  Date/Time: "));
  if (gps.date.isValid())
  {
    Serial.print(gps.date.month());
    Serial.print(F("/"));
    Serial.print(gps.date.day());
    Serial.print(F("/"));
    Serial.print(gps.date.year());
  }
  else
  {
    Serial.print(F("INVALID"));
  }

  Serial.print(F(" "));
  if (gps.time.isValid())
  {
    if (gps.time.hour() < 10) Serial.print(F("0"));
    Serial.print(gps.time.hour());
    Serial.print(F(":"));
    if (gps.time.minute() < 10) Serial.print(F("0"));
    Serial.print(gps.time.minute());
    Serial.print(F(":"));
    if (gps.time.second() < 10) Serial.print(F("0"));
    Serial.print(gps.time.second());
    Serial.print(F("."));
    if (gps.time.centisecond() < 10) Serial.print(F("0"));
    Serial.print(gps.time.centisecond());
  }
  else
  {
    Serial.print(F("INVALID"));
  }

  Serial.println();
}


String printEncryptionType(int thisType) {
  // read the encryption type and print out the name:
  switch (thisType) {
    case ENC_TYPE_WEP:
      return String("WEP");
      break;
    case ENC_TYPE_TKIP:
      return String("WPA");
      break;
    case ENC_TYPE_CCMP:
      return String("WPA2");
      break;
    case ENC_TYPE_NONE:
      return String("None");
      break;
    case ENC_TYPE_AUTO:
      return String("Auto");
      break;
  }
}



