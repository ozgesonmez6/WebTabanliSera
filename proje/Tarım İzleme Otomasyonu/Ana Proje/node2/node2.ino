

#include <DHT.h>
#include <Adafruit_Sensor.h>

/*
 * NODE 2
 * ---------
 * 
 * Sensörler ve Parçalar: WeMos (ESP8266 WiFi modülü ile) => Gaz sensörü + Sıcaklık nem sensörü
 * 
 * Kullanım Bölgesi: Genellikle iç mekan
 * Kullanım Adedi: Bir tane yeterli
 */

#include "DHT.h"
#include <ESP8266WiFi.h>
#include <ArduinoJson.h>

#define DHTPIN D5
#define DHTTYPE DHT22

DHT dht(DHTPIN, DHTTYPE);

String MAC_ADDRESS = "9C-2A-70-15-18-76";
int    DB_NO = 2478,
//     RAIN_SENSOR = 1,
//     SOIL_MOISTURE_SENSOR = 2,
       GAS_SENSOR = 3,
//     LIGHT_SENSOR = 4,
       MOISTURE_SENSOR = 5,
       TEMPERATURE_SENSOR = 6,
       HEAT_SENSOR = 7,
       gas_warning_level = 50,
       gas_emergency_level = 20,
       sensor_value;

const char* ssid     = "G4_9351";
const char* password = "omer1234";

IPAddress host(192, 168, 43, 12);

StaticJsonBuffer<300> jsonBuffer;

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
  dht.begin();

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
}

void loop() {
  JsonArray& root = jsonBuffer.createArray();
  
  sensor_value = analogRead(A0);
  Serial.print("> Gaz Sensoru: ");
  Serial.print(sensor_value);
  sensor_value = map(sensor_value, 100, 1024, 0, 100);
  if (sensor_value < 0) sensor_value = 0; else if (sensor_value > 100) sensor_value = 100;
  Serial.print(" %");
  Serial.print(sensor_value);
  Serial.print(" - ");
  
  if (sensor_value > gas_emergency_level) {
    Serial.println("gaz seviyesi kritik duzeyde");
  } else if (sensor_value > gas_warning_level) {
    Serial.println("gaz seviyesi uyari duzeyinde");
  } else {
    Serial.println("gaz seviyesi normal duzeyde");
  }
  
  JsonObject& data = root.createNestedObject();
  data["sensor_type_id"] = GAS_SENSOR;
  data["value"] = sensor_value;

  float h = dht.readHumidity(); // Nem
  float t = dht.readTemperature(); // Sıcaklık (Santigrad)
  float f = dht.readTemperature(true); // Sıcaklık (Fahrenhayt)

  if (isnan(h) || isnan(t) || isnan(f)) {
    Serial.println("Failed to read from DHT sensor!");
    Serial.println("");
  } else {
    float hif = dht.computeHeatIndex(f, h); // Isı Indeksi (Fahrenhayt)
    float hic = dht.computeHeatIndex(t, h, false); // Isı Indeksi (Santigrad)
  
    Serial.print("> Sicaklik - Nem Sensoru: ");
    Serial.print("Nem: ");
    Serial.print(h);
    Serial.print(" %\t");
    Serial.print("Sicaklik: ");
    Serial.print(t);
    Serial.print(" *C ");
    Serial.print(f);
    Serial.print(" *F\t");
    Serial.print("Isi Indeksi: ");
    Serial.print(hic);
    Serial.print(" *C ");
    Serial.print(hif);
    Serial.print(" *F");
    Serial.println("");
    Serial.println("");
    
    JsonObject& data2 = root.createNestedObject();
    data2["sensor_type_id"] = MOISTURE_SENSOR;
    data2["value"] = h;

    JsonObject& data3 = root.createNestedObject();
    data3["sensor_type_id"] = TEMPERATURE_SENSOR;
    data3["value"] = t;

    JsonObject& data4 = root.createNestedObject();
    data4["sensor_type_id"] = HEAT_SENSOR;
    data4["value"] = hic;
  }

  root.printTo(Serial);
  Serial.println("");

  Serial.println("");
  Serial.print("> Connecting to ");
  Serial.println(host);

  WiFiClient client;
  const int httpPort = 80;

  if (!client.connect(host, httpPort)) {
    Serial.println("> Connection failed");
    return;
  }
  
  String json_data;
  root.printTo(json_data);
  
  Serial.println("> Client Request: ");
  senddata(client, "/webservice/getdata.php", "database_number=" + (String)DB_NO + "&mac_address=" + MAC_ADDRESS + "&data=" + json_data);

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
  
  jsonBuffer.clear();
  delay(1000);
}

