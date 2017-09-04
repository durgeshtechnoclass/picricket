#!/usr/bin/python
# Example using a character LCD connected to a Raspberry Pi or BeagleBone Black.
import time
import Adafruit_CharLCD as LCD

# Raspberry Pi pin configuration:
lcd_rs        = 25
lcd_en        = 24
lcd_d4        = 23
lcd_d5        = 17
lcd_d6        = 18
lcd_d7        = 22
lcd_backlight = 4

# Define LCD column and row size for 16x2 LCD.
lcd_columns = 16
lcd_rows    = 2

# Initialize the LCD using the pins above.
lcd = LCD.Adafruit_CharLCD(lcd_rs, lcd_en, lcd_d4, lcd_d5, lcd_d6, lcd_d7,
                           lcd_columns, lcd_rows, lcd_backlight)
while True:
	lcd.clear()
	f1 = open("/var/www/html/country1.txt","r");
	f2 = open("/var/www/html/country2.txt","r");
	
	lcd.message(f1.read());	
	time.sleep(5.0)
	for i in range(lcd_columns-len(f1.read())):
		time.sleep(0.5)
		lcd.move_left()
	f1.close();		
	lcd.clear()
	
	lcd.message(f2.read());	
	time.sleep(5.0)
	for i in range(lcd_columns-len(f2.read())):
		time.sleep(0.5)
		lcd.move_left()
	f2.close();	
	lcd.clear()
