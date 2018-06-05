#include <Wire.h>
#include <BH1750.h>

BH1750 lightMeter;
int      sensor_value,
         light_very_bright = 80,
         light_bright = 60,
         light_well = 40,
         light_dim = 20;

void setup(){
  Serial.begin(115200);
  pinMode(D0, OUTPUT);
  lightMeter.begin();
}

void loop() {
  sensor_value = lightMeter.readLightLevel();
  Serial.print("> Isik Sensoru: ");
  Serial.print(sensor_value);
  sensor_value = map(sensor_value, 0, 255, 0, 100);
  if (sensor_value < 0) sensor_value = 0; else if (sensor_value > 100) sensor_value = 100;
  Serial.print(" %");
  Serial.print(sensor_value);
  Serial.print(" - ");
  
  if (sensor_value > light_very_bright) {
    Serial.println("ortam cok parlak");
  } else if (sensor_value > light_bright) {
    Serial.println("ortam parlak");
  } else if (sensor_value > light_well) {
    Serial.println("ortam aydinlik");
  } else if (sensor_value > light_dim) {
    Serial.println("ortam los isikli");
  } else {
    Serial.println("ortam karanlik");
  }

  if (sensor_value < light_dim) {
    digitalWrite(D0, HIGH);
  } else {
    digitalWrite(D0, LOW);
  }
  
  delay(1000);
}
