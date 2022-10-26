#include <LiquidCrystal_I2C.h>

#include <WiFi.h>
#include <WiFiClient.h>
#include <WebServer.h>
#include <ESPmDNS.h>
#include <liquidCrystal_I2C.h>
#define led 2
LiquidCrystal_I2C lcd(0x27, 16, 2); // I2C address 0x3F, 16 column and 2 rows

#define TRIG_PIN 26 // ESP32 pin GIOP26 connected to Ultrasonic Sensor's TRIG pin
#define ECHO_PIN 25 // ESP32 pin GIOP25 connected to Ultrasonic Sensor's ECHO pin
#define REDLED 19
#define CW 33
#define CCW 4
float duration_us, distance_cm, minimum_depth, tank_height, current_level;

const char* ssid = "DZ";
const char* password = "0011223344";

WebServer server(80);



void handleManualON() {
  digitalWrite(led, HIGH );
  server.send(200, "text/plain", "turn motor on!");
  digitalWrite(CW,HIGH); //Motor runs clockwise//

  delay(1000); //for 1 second//

  digitalWrite(CW, LOW); //Motor stops//

  digitalWrite(CCW, HIGH);//Motor runs counter-clockwise//

  delay(1000); //For 1 second//

  digitalWrite(CCW, LOW); //Motor stops//
  measureDistance();

  //digitalWrite(led, 0);
}

void handleManualOFF() {
  digitalWrite(led, LOW );
  server.send(200, "text/plain", "turn motor off!");
  //digitalWrite(led, 0);
  digitalWrite(CCW, LOW);
  digitalWrite(CCW, LOW);
}

void handleAUTO() {
  for(int i=0;i<20;i++){   
  server.send(200, "text/plain", "write code to condition motor depending on water level!");
  digitalWrite(led, HIGH);
  delay(2000);
  digitalWrite(led, LOW);
  delay(1000);
  while(distance_cm<21) {
  digitalWrite(CW,HIGH); //Motor runs clockwise//

  delay(1000); //for 1 second//

  digitalWrite(CW, LOW); //Motor stops//

  digitalWrite(CCW, HIGH);//Motor runs counter-clockwise//

  delay(1000); //For 1 second//

  digitalWrite(CCW, LOW); //Motor stops//
  measureDistance();
  if(distance_cm>179){
    digitalWrite(CCW, LOW);
    digitalWrite(CCW, LOW);
    
  }

} 
  

  //digitalWrite(led, 0);
  }
  
}


void setup(void) {
  pinMode(led, OUTPUT);
  pinMode(CW, OUTPUT); //Set CW as an output//
  pinMode(CCW, OUTPUT); //Set CCW as an output//
  digitalWrite(led, 0);
  lcd.init();               // initialize the lcd
  lcd.backlight();          // open the backlight
  pinMode(TRIG_PIN, OUTPUT); // config trigger pin to output mode
  pinMode(ECHO_PIN, INPUT);  // config echo pin to input mode
  pinMode(REDLED, OUTPUT);
  Serial.begin(115200);
  minimum_depth = 5;
  tank_height = 200;
  Serial.begin(115200);
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  Serial.println("");

  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  if (MDNS.begin("esp32")) {
    Serial.println("MDNS responder started");
  }

  server.on("/OFF", handleManualOFF);
  server.on("/ON", handleManualON);
  server.on("/AUTO", handleAUTO);

//  server.on("/inline", []() {
//    server.send(200, "text/plain", "this works as well");
//  });

//  server.onNotFound(handleNotFound);

  server.begin();
  Serial.println("HTTP server started");
}

void loop(void) {
  server.handleClient();
  delay(2);//allow the cpu to switch to other tasks
  // generate 10-microsecond pulse to TRIG pin
  //digitalWrite(TRIG_PIN, HIGH);
  //delayMicroseconds(10);
  //digitalWrite(TRIG_PIN, LOW);

  // measure duration of pulse from ECHO pin
  //duration_us = pulseIn(ECHO_PIN, HIGH);

  // calculate the distance
  distance_cm = 0.017 * duration_us;
  current_level = tank_height - distance_cm;

// change distance to current level for deployment

  lcd.clear();
  lcd.setCursor(0, 0); // start to print at the first row
  lcd.print("Water Level: ");
  lcd.print(distance_cm); /// subtract this distance from length of tank

  delay(500);
}
float measureDistance(){
  // generate 10-microsecond pulse to TRIG pin
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);

  // measure duration of pulse from ECHO pin
  duration_us = pulseIn(ECHO_PIN, HIGH);

  // calculate the distance
  distance_cm = 0.017 * duration_us;
  current_level = tank_height - distance_cm;
  return distance_cm;

}