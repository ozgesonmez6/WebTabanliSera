#include <ArduinoJson.h>

/*
 * NODE 1
 * ---------
 * 
 * Sensörler ve Parçalar: WeMos (ESP8266 WiFi Modülü ile) => Toprak sensörü + Su motoru
 * 
 * Kullanım Bölgesi: İç ve dış mekanlar
 * Kullanım Adedi: En az bir tane olmak üzere birden fazla da olabilir
 */

#include <ESP8266WiFi.h>
#include <ArduinoJson.h>
 
String MAC_ADDRESS = "9C-2A-70-15-18-76";
int    DB_NO = 2478,
//     RAIN_SENSOR = 1,
	     SOIL_MOISTURE_SENSOR = 2,
//     GAS_SENSOR = 3,
//     LIGHT_SENSOR = 4,
//     MOISTURE_SENSOR = 5,
//     TEMPERATURE_SENSOR = 6,
//     HEAT_SENSOR = 7,
	     soil_moisture_threshold = 20,
	     sensor_value;

const char* ssid     = "TurkTelekom_T27B3";//knaka bunlar doğrumu
const char* password = "1453gs1453";

IPAddress host(192,168,1,105);



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
  pinMode(D0, OUTPUT);

  delay(10);

  Serial.println();
  Serial.println();
  Serial.println("> Connecting to \"" + (String)ssid + "\" WiFi");
  // bunları seriale basmıyor
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
}

int counter = 0;

void loop() {


  sensor_value = analogRead(A0);
  Serial.print("Toprak Sensoru: ");
  Serial.print(sensor_value);
  sensor_value = map(sensor_value, 948, 200, 0, 100);
  if (sensor_value < 0) sensor_value = 0; else if (sensor_value > 100) sensor_value = 100;
  Serial.print(" %");
  Serial.print(sensor_value);
  Serial.print(" - ");
  
  if (sensor_value < soil_moisture_threshold) {
    digitalWrite(D0, LOW);
    Serial.println("su motoru acik");
  } else {
    digitalWrite(D0, HIGH);
    Serial.println("su motoru kapali");
  }

  Serial.println("");
  Serial.print("> Connecting to ");
  Serial.println(host);

  WiFiClient client;
  const int httpPort = 80; // benimki 80 olabilir

  if (!client.connect(host, httpPort)) {
    Serial.println("> Connection failed");
    return;
  }

//  data["sensor_type_id"] = SOIL_MOISTURE_SENSOR;
//  data["value"] = sensor_value;
//
//  String json_data;
//  root.printTo(json_data);
  
  Serial.println("> Client Request: ");
  senddata(client, "/webservice/getdata.php", "database_number=" + (String)DB_NO + "&mac_address=" + MAC_ADDRESS + "&sensor=" + sensor_value);

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
  
//  root.printTo(Serial);
  Serial.println("");
//
//  jsonBuffer.clear();
  counter++;
  delay(1000);
}

