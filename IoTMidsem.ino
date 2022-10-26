#include <LiquidCrystal_I2C.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <WiFiClient.h>
#include <WebServer.h>
#include <ESPmDNS.h>
#define led 19
#define relay 33
const char WIFI_SSID[] = "DZ";
const char WIFI_PASSWORD[] = "0011223344";
WebServer server(80);
String HOST_NAME = "http://172.16.1.140";  // change to your PC's IP address
String PATH_NAME = "/iot/insert_data.php";
String queryString = "?TankID=2&WaterLevel=219";


unsigned long previousTempMillis = 0;  // will store last time LED was updated
unsigned long tempInterval = 1000;  // will store last time LED was updated

LiquidCrystal_I2C lcd(0x27, 16, 2); // I2C address 0x3F, 16 column and 2 rows

#define TRIG_PIN 26 // ESP32 pin GIOP26 connected to Ultrasonic Sensor's TRIG pin
#define ECHO_PIN 25 // ESP32 pin GIOP25 connected to Ultrasonic Sensor's ECHO pin
#define REDLED 19
float duration_us, distance_cm, minimum_depth, tank_height, current_level;


void handleManualON() {
  Serial.println("manual on");
  while(1){
    redund();
    digitalWrite(led, HIGH);
    digitalWrite(relay,HIGH);
    }
}

void handleManualOFF() {
  Serial.println("manual off");
  while(1){
    redund();
    digitalWrite(led, LOW);
    digitalWrite(relay,LOW);
    }
}

void handleAUTO() {
  while(1){
    redund();
    if(distance_cm<20){
       while(distance_cm<=50){
        redund();
        Serial.println("manual off");
        digitalWrite(led, HIGH);
        digitalWrite(relay,HIGH);
        }
     digitalWrite(led, LOW);
     digitalWrite(relay,LOW);
      }   
    } 
  }

void setup() {

  pinMode(led, OUTPUT);
  pinMode(relay, OUTPUT);
  digitalWrite(led, 0);
  WiFi.mode(WIFI_STA);
  
  lcd.init();               // initialize the lcd
  lcd.backlight();          // open the backlight
  pinMode(TRIG_PIN, OUTPUT); // config trigger pin to output mode
  pinMode(ECHO_PIN, INPUT);  // config echo pin to input mode
  pinMode(REDLED, OUTPUT);
  Serial.begin(115200);
  minimum_depth = 5;
  tank_height = 200;
  
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(WIFI_SSID);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  if (MDNS.begin("esp32")) {
    Serial.println("MDNS responder started");
  }

  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  server.on("/OFF", handleManualOFF);
  server.on("/ON", handleManualON);
  server.on("/AUTO", handleAUTO);

  server.begin();
  Serial.println("HTTP server started");

  HTTPClient http;

  http.begin(HOST_NAME + PATH_NAME + queryString);  //HTTP
  int httpCode = http.GET();

  // httpCode will be negative on error
  if (httpCode > 0) {
    // file found at server
    if (httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println(payload);
    } else {
      // HTTP header has been send and Server response header has been handled
      Serial.printf("[HTTP] GET... code: %d\n", httpCode);
    }
  } else {
    Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
  }

  http.end();
  }

void loop() {

  redund();
  digitalWrite(led,LOW);
  digitalWrite(relay,HIGH);
}

void redund(){
      server.handleClient();
  delay(2);//allow the cpu to switch to other tasks
  // generate 10-microsecond pulse to TRIG pin
  unsigned long currentTempMillis = millis();
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);

  // measure duration of pulse from ECHO pin
  duration_us = pulseIn(ECHO_PIN, HIGH);

  // calculate the distance
  distance_cm = 0.017 * duration_us;
  current_level = tank_height - distance_cm;


 if (isnan(distance_cm) ) {
    ////Serial.println("Failed to read DHT11");
    //delay(1000);
  } else {
     

  if (currentTempMillis - previousTempMillis >= tempInterval) {
    previousTempMillis = currentTempMillis;
    HTTPClient http;

    String HOST_NAME1 = "http://172.16.1.140";  // change to your PC's IP address
    String PATH_NAME1 = "/iot/insert_data.php";
    String queryString1 = "?TankID=2&WaterLevel=" + String(distance_cm);

    http.begin(HOST_NAME1 + PATH_NAME1 + queryString1);  //HTTP
    int httpCode = http.GET();

    // httpCode will be negative on error
    if (httpCode > 0) {
      // file found at server
      if (httpCode == HTTP_CODE_OK) {
        String payload = http.getString();
        Serial.println(payload);
      } else {
        // HTTP header has been send and Server response header has been handled
        Serial.printf("[HTTP] GET... code: %d\n", httpCode);
      }
    } else {
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();
  }
}
    
  lcd.clear();
  lcd.setCursor(0, 0); // start to print at the first row
  lcd.print("WaterLevel: ");
  lcd.print(distance_cm); /// subtract this distance from length of tank

  delay(500);
  }
