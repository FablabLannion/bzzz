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

#include <SoftwareSerial.h>
#include <string.h>         //Used for string manipulations

SoftwareSerial cell(2,3);  //Create a 'fake' serial port. Pin 2 is the Rx pin, pin 3 is the Tx pin.

//
// SMS vars
//

String phoneNumber = "\"+33645117979\"";
String smsText = "It is alive, ALIVE !!!";
String pinCode = "0000";

#define TEMPO				500

//
// State machine
//
#define STATE_NOTREADY		0
#define STATE_READY			1
#define STATE_SMS_INIT		2
#define STATE_SMS_READY		3
#define STATE_SMS_SENT		4
#define STATE_SMS_DELETE	5
#define STATE_SMS_LOOP		6
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
#define AT_CMD_AT				"AT\r"
#define AT_CMD_ENTER_PIN		"AT+CPIN=\""
#define AT_CMD_INIT_SMS			"AT+CMGF=1\r"
#define AT_CMD_SEND_SMS			"AT+CMGS="
#define AT_CMD_DEL_SMS			"AT+CMGD=1,4\r"
#define AT_RESULT_UP			"+SIND: 7"
#define AT_RESULT_READY			"+SIND: 4"
#define AT_RESULT_OK			"OK"
#define AT_RESULT_NEED_TEXT		"> "
#define END_OF_FILE				"\x1A"

void setup()
{
  //Initialize serial ports for communication.
  Serial.begin(9600);
  cell.begin(9600);
  //Let's get started!
  Serial.println("Starting SM5100B Communication...");
}

void loop() {
  //If a character comes in from the cellular module...
	if(cell.available() >0){
		cellBuff = getSerialCellString();
		Serial.println(cellBuff);
		
		if ( cellBuff == AT_RESULT_UP ){
			Serial.println("Entering PIN code !");
			cell.print(AT_CMD_ENTER_PIN); cell.print(pinCode); cell.println('"');
			Serial.print(AT_CMD_ENTER_PIN); Serial.print(pinCode); Serial.println('"');
			delay(TEMPO);
		}
		else if ( cellBuff == AT_RESULT_READY || state == STATE_SMS_LOOP){
			Serial.println("Ready !");
			Serial.println("Send SMS");
			state = STATE_READY;
			cell.print(AT_CMD_AT);
			Serial.println(AT_CMD_AT);
			delay(TEMPO);
		}
		else if ( cellBuff == AT_RESULT_OK && state == STATE_READY ){
			state = STATE_SMS_INIT;
			cell.print(AT_CMD_INIT_SMS); 
			Serial.println(AT_CMD_INIT_SMS);
			delay(TEMPO);
		}
		else if ( cellBuff == AT_RESULT_OK && state == STATE_SMS_INIT ){
			state = STATE_SMS_READY;
			cell.print(AT_CMD_SEND_SMS); cell.println(phoneNumber);
			Serial.print(AT_CMD_SEND_SMS); Serial.println(phoneNumber);
			delay(TEMPO);
		}
		else if ( cellBuff == AT_RESULT_NEED_TEXT && state == STATE_SMS_READY ){
			state = STATE_SMS_SENT;
			cell.print(smsText);cell.print(END_OF_FILE);
			Serial.print(smsText);Serial.println(END_OF_FILE);
			delay(TEMPO);
		}
		else if ( cellBuff == AT_RESULT_OK && state == STATE_SMS_SENT ){
			Serial.println("SMS Sent, delete history");
			cell.print(AT_CMD_DEL_SMS); 
			Serial.println(AT_CMD_DEL_SMS);
			delay(TEMPO);
			state = STATE_SMS_DELETE;
		}
		else if ( cellBuff == AT_RESULT_OK && state == STATE_SMS_DELETE ){
			Serial.println("SMS Deleted, loop");
			state = STATE_SMS_LOOP;
			delay(5000);
		}
	}
  
  //If a character is coming from the terminal to the Arduino...
	if(Serial.available() >0)
	{
		inChar=Serial.read();  //Get the character coming from the terminal
		Serial.print(inChar);
		cell.print(inChar);    //Send the character to the cellular module.
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
