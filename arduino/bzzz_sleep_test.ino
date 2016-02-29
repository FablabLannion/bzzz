

#include <SoftwareSerial.h>
#include <string.h>         //Used for string manipulations
SoftwareSerial cell(7,8);  //Create a 'fake' serial port. Pin 2 is the Rx pin, pin 3 is the Tx pin.
#include <avr/sleep.h>
#include <avr/wdt.h>

#ifndef cbi
#define cbi(sfr, bit) (_SFR_BYTE(sfr) &= ~_BV(bit))
#endif
#ifndef sbi
#define sbi(sfr, bit) (_SFR_BYTE(sfr) |= _BV(bit))
#endif

//
// SMS vars
//

String phoneNumber = "\"+33683722040\"";
String smsText = "It is alive, ALIVE !!!";
String pinCode = "0000";

//
// State machine
//
#define STATE_NOTREADY		0
#define STATE_READY			1
#define STATE_SMS_INIT		2
#define STATE_SMS_READY		3
#define STATE_SMS_SENT		4
int state = STATE_NOTREADY;

//
// Serial read
//

#define INBUFFERSIZE 32
char commandbuffer[INBUFFERSIZE];
String buffStr;
String cellBuff;
char inChar=-1; // Where to store the character read
char outChar=-1; // Where to store the character read
char indexBuff=0;

//
// AT COMMAND
//
#define AT_CMD_AT			"AT"
#define AT_CMD_ENTER_PIN		"AT+CPIN=\""
#define AT_CMD_INIT_SMS			"AT+CMGF=1"
#define AT_CMD_SEND_SMS			"AT+CMGS="
#define AT_RESULT_UP			"+SIND: 7"
#define AT_RESULT_READY			"+SIND: 4"
#define AT_RESULT_OK			"OK"
#define AT_RESULT_NEED_TEXT		"> "
#define END_OF_FILE			"\x1A"

volatile boolean f_wdt=1;
int cnt = 0;

void setup()
{
  //Initialize serial ports for communication.
	Serial.begin(9600);
	//~ cell.begin(9600);
	//~ Serial.println("Starting SM5100B Communication...");
	
	// CPU Sleep Modes 
	// SM2 SM1 SM0 Sleep Mode
	// 0    0  0 Idle
	// 0    0  1 ADC Noise Reduction
	// 0    1  0 Power-down
	// 0    1  1 Power-save
	// 1    0  0 Reserved
	// 1    0  1 Reserved
	// 1    1  0 Standby(1)

	cbi( SMCR,SE );      // sleep enable, power down mode
	cbi( SMCR,SM0 );     // power down mode
	sbi( SMCR,SM1 );     // power down mode
	cbi( SMCR,SM2 );     // power down mode

	setup_watchdog(7);
	
	pinMode(13, OUTPUT);      // sets the digital pin as output
}

void loop() {
	if (f_wdt==1) {  // wait for timed out watchdog / flag is set when a watchdog timeout occurs
		f_wdt=0;       // reset flag
		Serial.print("x");
		digitalWrite(13, HIGH);   // sets the LED on
		system_sleep();
		//If a character comes in from the cellular module...
		if(cell.available() >0){
			cellBuff = getSerialCellString();
			Serial.println(cellBuff);
			if ( cellBuff == AT_RESULT_UP ){
				
				Serial.println("AT+CFUN=4");
				cell.println("AT+CFUN=4");
				Serial.println("Entering PIN code !");
				cell.print(AT_CMD_ENTER_PIN); cell.print(pinCode); cell.println('"');
				Serial.print(AT_CMD_ENTER_PIN); Serial.print(pinCode); Serial.println('"');
			}
			else if ( cellBuff == AT_RESULT_READY ){
				Serial.println("Ready !");
				Serial.println("Send SMS");
				state = STATE_READY;
				cell.println(AT_CMD_AT);
				Serial.println(AT_CMD_AT);
			}
			else if ( cellBuff == AT_RESULT_OK && state == STATE_READY ){
				state = STATE_SMS_INIT;
				cell.println(AT_CMD_INIT_SMS); 
				Serial.println(AT_CMD_INIT_SMS);
			}
			else if ( cellBuff == AT_RESULT_OK && state == STATE_SMS_INIT ){
				state = STATE_SMS_READY;
				cell.print(AT_CMD_SEND_SMS); cell.println(phoneNumber);
				Serial.print(AT_CMD_SEND_SMS); Serial.println(phoneNumber);
			}
			else if ( cellBuff == AT_RESULT_NEED_TEXT && state == STATE_SMS_READY ){
				state = STATE_SMS_SENT;
				cell.print(smsText);cell.println(END_OF_FILE);
				Serial.print(smsText);Serial.println(END_OF_FILE);
			}
			else if ( cellBuff == AT_RESULT_OK && state == STATE_SMS_SENT ){
				state = STATE_NOTREADY;
				system_sleep();
				Serial.println("SMS Sent");
			}
		}
		//~ //If a character is coming from the terminal to the Arduino...
		if(Serial.available() >0)
		{
			inChar=Serial.read();  //Get the character coming from the terminal
			cell.print(inChar);    //Send the character to the cellular module.
		}
	}
}

String getSerialCellString()
{
	indexBuff=0;
	while (cell.available() > 0 && indexBuff < INBUFFERSIZE-1) 
		{
			outChar = cell.read(); // Read a character
			// At EOL, break
			if ( outChar == '\r' ) break;
			// For the first char if \n, skip
			if ( !(outChar == '\n' && indexBuff == 0)){
				commandbuffer[indexBuff] = outChar; // Store it
				indexBuff++; // Increment where to write next
				commandbuffer[indexBuff] = '\0'; // Null terminate the string
			}
		}
	buffStr = String (commandbuffer);
	for (int i=0;i<INBUFFERSIZE-1;i++) {
	   commandbuffer[i]=0;
	}
	indexBuff=0;
	return buffStr;
}

//****************************************************************  
// set system into the sleep state 
// system wakes up when wtchdog is timed out
void system_sleep() {

  cbi(ADCSRA,ADEN);                    // switch Analog to Digitalconverter OFF

  set_sleep_mode(SLEEP_MODE_PWR_DOWN); // sleep mode is set here
  sleep_enable();

  sleep_mode();                        // System sleeps here

    sleep_disable();                     // System continues execution here when watchdog timed out 
    sbi(ADCSRA,ADEN);                    // switch Analog to Digitalconverter ON

}

//****************************************************************
// 0=16ms, 1=32ms,2=64ms,3=128ms,4=250ms,5=500ms
// 6=1 sec,7=2 sec, 8=4 sec, 9= 8sec
void setup_watchdog(int ii) {

  byte bb;
  int ww;
  if (ii > 9 ) ii=9;
  bb=ii & 7;
  if (ii > 7) bb|= (1<<5);
  bb|= (1<<WDCE);
  ww=bb;
  Serial.println(ww);


  MCUSR &= ~(1<<WDRF);
  // start timed sequence
  WDTCSR |= (1<<WDCE) | (1<<WDE);
  // set new watchdog timeout value
  WDTCSR = bb;
  WDTCSR |= _BV(WDIE);


}
//****************************************************************  
// Watchdog Interrupt Service / is executed when  watchdog timed out
ISR(WDT_vect) {
	Serial.print("Interrupt:");
	Serial.println(cnt);
	if ( cnt == 3 ){
		digitalWrite(13, LOW);   // sets the LED on
		delay(3000);
		cnt = 0;
		f_wdt=1;  // set global flag
	}else{
		cnt++;
		f_wdt=1;  // set global flag
	}
}

/*
SparkFun Cellular Shield - Pass-Through Sample Sketch
SparkFun Electronics
Written by Ryan Owens
3/8/10

Description: This sketch is written to interface an Arduino Duemillanove to a  Cellular Shield from SparkFun Electronics.
The cellular shield can be purchased here: http://www.sparkfun.com/commerce/product_info.php?products_id=9607
In this sketch serial commands are passed from a terminal program to the SM5100B cellular module; and responses from the cellular
module are posted in the terminal. More information is found in the sketch comments.

An activated SIM card must be inserted into the SIM card holder on the board in order to use the device!

This sketch utilizes the NewSoftSerial library written by Mikal Hart of Arduiniana. The library can be downloaded at this URL:
http://arduiniana.org/libraries/NewSoftSerial/

This code is provided under the Creative Commons Attribution License. More information can be found here:
http://creativecommons.org/licenses/by/3.0/

(Use our code freely! Please just remember to give us credit where it's due. Thanks!)
*/
/* SM5100B Quck Reference for AT Command Set
*Unless otherwise noted AT commands are ended by pressing the 'enter' key.

1.) Make sure the proper GSM band has been selected for your country. For the US the band must be set to 7.
To set the band, use this command: AT+SBAND=7

2.) After powering on the Arduino with the shield installed, verify that the module reads and recognizes the SIM card.
With a terimal window open and set to Arduino port and 9600 buad, power on the Arduino. The startup sequence should look something
like this:

Starting SM5100B Communication...
    
+SIND: 1
+SIND: 10,"SM",1,"FD",1,"LD",1,"MC",1,"RC",1,"ME",1

Communication with the module starts after the first line is displayed. The second line of communication, +SIND: 10, tells us if the module
can see a SIM card. If the SIM card is detected every other field is a 1; if the SIM card is not detected every other field is a 0.

3.) Wait for a network connection before you start sending commands. After the +SIND: 10 response the module will automatically start trying
to connect to a network. Wait until you receive the following repsones:

+SIND: 11
+SIND: 3
+SIND: 4

The +SIND response from the cellular module tells the the modules status. Here's a quick run-down of the response meanings:
0 SIM card removed
1 SIM card inserted
2 Ring melody
3 AT module is partially ready
4 AT module is totally ready
5 ID of released calls
6 Released call whose ID=<idx>
7 The network service is available for an emergency call
8 The network is lost
9 Audio ON
10 Show the status of each phonebook after init phrase
11 Registered to network

After registering on the network you can begin interaction. Here are a few simple and useful commands to get started:

To make a call:
AT command - ATDxxxyyyzzzz
Phone number with the format: (xxx)yyy-zzz

If you make a phone call make sure to reference the devices datasheet to hook up a microphone and speaker to the shield.

To send a txt message:
AT command - AT+CMGF=1
This command sets the text message mode to 'text.'
AT command = AT+CMGS="xxxyyyzzzz"(carriage return)'Text to send'(CTRL+Z)
This command is slightly confusing to describe. The phone number, in the format (xxx)yyy-zzzz goes inside double quotations. Press 'enter' after closing the quotations.
Next enter the text to be send. End the AT command by sending CTRL+Z. This character can't be sent from Arduino's terminal. Use an alternate terminal program like Hyperterminal,
Tera Term, Bray Terminal or X-CTU.

The SM5100B module can do much more than this! Check out the datasheets on the product page to learn more about the module.*/



