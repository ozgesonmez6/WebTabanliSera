#include <DHT.h>


/*
 * NODE 3
 * ---------
 * 
 * Sensörler ve Parçalar: WeMos (ESP8266 WiFi modülü ile) => Yağmur sensörü + Sıcaklık Nem sensörü
 * 
 * Kullanım Bölgesi: Genellikle dış mekan
 * Kullanım Adedi: Bir tane yeterli
 */

#include "DHT.h"

#define DHTPIN D5
#define DHTTYPE DHT22

DHT dht(DHTPIN, DHTTYPE);

int sensor_value, 
    rain_heavy = 70,
    rain_moderate = 40;

void setup() {
  Serial.begin(115200);
  dht.begin();
}

void loop() {
  sensor_value = analogRead(A0);
  Serial.print("> Yagmur Sensoru: ");
  Serial.print(sensor_value);
  sensor_value = map(sensor_value, 1024, 200, 0, 100);
  if (sensor_value < 0) sensor_value = 0; else if (sensor_value > 100) sensor_value = 100;
  Serial.print(" %");
  Serial.print(sensor_value);
  Serial.print(" - ");
  
  if (sensor_value > rain_heavy) {
    Serial.println("hava saganak yagisli");
  } else if (sensor_value > rain_moderate) {
    Serial.println("hava hafif yagisli");
  } else {
    Serial.println("hava gunesli");
  }

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
  }
  
  delay(1000);
}

