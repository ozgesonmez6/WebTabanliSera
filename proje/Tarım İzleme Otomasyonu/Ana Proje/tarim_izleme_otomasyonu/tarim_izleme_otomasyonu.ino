#include <ESP8266WiFi.h>
//#include <Wire.h>
#include <Adafruit_ADS1015.h>
#include "DHT.h"

#define DHTPIN D5
#define DHTTYPE DHT22

const char* ssid     = "Gmaker";
const char* password = "inspectorgadget";

IPAddress host(192, 168, 43, 217);

Adafruit_ADS1115 ads(0x48);
int16_t sensorValue;

DHT dht(DHTPIN, DHTTYPE);

void senddata(WiFiClient client, String location, String data) {
  Serial.println("POST " + location + " HTTP/1.1");
  Serial.println("Host: " + (String)host);
  Serial.println("Content-Type: application/x-www-form-urlencoded");
  Serial.println("Content-Length: " + (String)data.length());
  Serial.println("");
  Serial.println(data);

  
  client.println("POST " + location + " HTTP/1.1");
  client.println("Host: " + (String)host);
  client.println("Content-Type: application/x-www-form-urlencoded");
  client.println("Content-Length: " + (String)data.length());
  client.println("");
  client.print(data);
}

void setup() {
  Serial.begin(115200);
  delay(10);

  Serial.println();
  Serial.println();
  Serial.println("> Connecting to \"" + (String)ssid + "\" WiFi");

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("");
  Serial.println("> WiFi connected");  
  Serial.print("> IP address: ");
  Serial.println(WiFi.localIP());

  ads.begin();
  dht.begin();
}

int counter = 0;

void loop() {
  /*
   * 0 => Yağmur
   * 1 => Toprak
   * 2 => Gaz
   * 3 => Işık
   */

  
  sensorValue = ads.readADC_SingleEnded(0);
  sensorValue = map(sensorValue, 26482, 6666, 0, 100);
  if (sensorValue < 0) sensorValue = 0; else if (sensorValue > 100) sensorValue = 100;
  Serial.print("Yagmur Sensoru: %");
  Serial.print(sensorValue);
  
  if(sensorValue < 30){
    Serial.println(" - It's heavy rain");
  } else if (sensorValue < 50) {
    Serial.println(" - It's moderate rain");
  } else {
     Serial.println(" - It's sunny");
  }

  Serial.println("");

  /*sensorValue = ads.readADC_SingleEnded(1);
  sensorValue = map(sensorValue, 26481, 7558, 0, 100);
   if (sensorValue < 0) sensorValue = 0; else if (sensorValue > 100) sensorValue = 100;
  Serial.print("Toprak Sensoru: %");
  Serial.println(sensorValue);  

  sensorValue = ads.readADC_SingleEnded(2);
  sensorValue = map(sensorValue, 2934, 18127, 0, 100);
   if (sensorValue < 0) sensorValue = 0; else if (sensorValue > 100) sensorValue = 100;
  Serial.print("Gaz Sensoru: %");
  Serial.println(sensorValue);  

  sensorValue = ads.readADC_SingleEnded(3);
  sensorValue = map(sensorValue, 0, 25472, 0, 100);
   if (sensorValue < 0) sensorValue = 0; else if (sensorValue > 100) sensorValue = 100;
  Serial.print("Isik Sensoru: %");
  Serial.println(sensorValue);  

  float h = dht.readHumidity(); // Nem
  float t = dht.readTemperature(); // Sıcaklık (Santigrad)
  float f = dht.readTemperature(true); // Sıcaklık (Fahrenhayt)
  float hif = dht.computeHeatIndex(f, h); // Isı Indeksi (Fahrenhayt)
  float hic = dht.computeHeatIndex(t, h, false); // Isı Indeksi (Santigrad)

  Serial.print("Humidity: ");
  Serial.print(h);
  Serial.print(" %\t");
  Serial.print("Temperature: ");
  Serial.print(t);
  Serial.print(" *C ");
  Serial.print(f);
  Serial.print(" *F\t");
  Serial.print("Heat index: ");
  Serial.print(hic);
  Serial.print(" *C ");
  Serial.print(hif);
  Serial.println(" *F");
  Serial.println("");
  Serial.println("");*/
  
  /*Serial.println("");
  Serial.print("> Connecting to ");
  Serial.println(host);

  WiFiClient client;
  const int httpPort = 80;
  
  if (!client.connect(host, httpPort)) {
    Serial.println("> Connection failed");
    return;
  }

  String url = "/projects/webservice/index.php?counter=" + (String)counter;

  Serial.println("> Client Request: ");

  senddata(client, "/projects/webservice/index.php", "counter=" + (String)counter);
  
  delay(10);
  
  Serial.println();
  Serial.println("> Server Response:");
  while(client.available()){
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }

  Serial.println();
  Serial.println();
  Serial.println("> Closing connection");
  Serial.println("");
  
  ++counter;*/
  
  delay(1000);  
}

