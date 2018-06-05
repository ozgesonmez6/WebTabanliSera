void setup()
{
  Serial.begin(115200);
}
void loop()
{
  float sensorVoltage; 
  float sensorValue;
 
  sensorValue = analogRead(A0);
  sensorVoltage = sensorValue/1024*5.0;
 
  Serial.print("Sensor voltage = ");
  Serial.print(sensorVoltage);
  Serial.println(" V");
  
  delay(1000);
}
