int rainPin = A0;

void setup(){
  pinMode(rainPin, INPUT);
  Serial.begin(115200);
}

void loop() {
  int sensorValue = analogRead(rainPin);
  
  Serial.print("> Yagmur Sensoru: ");
  Serial.print(sensorValue);
  
  if(sensorValue < 300){
    Serial.println(" - hava saganak yagisli");
  } else if (sensorValue < 500) {
    Serial.println(" - hava hafif yagisli");
  } else {
     Serial.println(" - hava gunesli");
  }
  
  delay(1000);
}
