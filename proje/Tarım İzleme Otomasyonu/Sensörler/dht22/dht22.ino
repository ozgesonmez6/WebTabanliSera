#include "DHT.h"

#define DHTPIN D5
#define DHTTYPE DHT22

DHT dht(DHTPIN, DHTTYPE);

void setup() {
  Serial.begin(115200);
  Serial.println("DHT22 test!");

  dht.begin();
}

void loop() {
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
