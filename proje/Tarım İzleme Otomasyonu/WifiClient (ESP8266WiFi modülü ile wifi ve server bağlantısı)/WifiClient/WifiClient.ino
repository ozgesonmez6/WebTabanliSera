#include <ESP8266WiFi.h>
#include <SPI.h>
#include <Ethernet.h>
/*const char* ssid     = "Gmaker";
const char* password = "inspectorgadget";

IPAddress host(192, 168, 43, 217);
//char host[] = "10.28.2.219";
*/
const char* ssid     = "TurkTelekom_T27B3";//knaka bunlar doğrumu
const char* password = "1453gs1453";

IPAddress host(192,168,1,105);

void setup() {
  Serial.begin(115200);
  delay(10);

  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");  
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

int counter = 0;

void loop() {
  Serial.print("connecting to ");
  Serial.println(host);

  WiFiClient client;
  const int httpPort = 80;
  
  if (!client.connect(host, httpPort)) {
    Serial.println("connection failed");
    return;
  }

  String url = "/webservice/getdata.php?sensor=" + (String)counter;

  Serial.print("Requesting URL: ");
  Serial.println(url);

  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n");
  delay(10);

  Serial.println("Respond:");
  while(client.available()){
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }

  Serial.println();
  Serial.println("closing connection");
  
  
  ++counter;
  delay(5000);  
}
