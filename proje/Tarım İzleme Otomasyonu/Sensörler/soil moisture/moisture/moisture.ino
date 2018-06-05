int sensor_pin = A0; 
int output_value ;

void setup() {
  Serial.begin(115200);
  Serial.println("Reading From the Sensor ...");
}

void loop() {
  output_value = analogRead(sensor_pin);
  output_value = 1024 - output_value;
  output_value = map(output_value, 0, 1024, 0, 100);
  
  Serial.print("Moisture: ");
  Serial.print(output_value);
  Serial.println("%");
  
  delay(1000);
}
