<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
  </head>

  <body>
	<h1>IPS-Modul zur Einbindung von Worx-Landroid-Mährobotern in IP-Symcon via Landroid-Bridge</h1>
	<h2>Grundsätzliches</h2>
	Das Modul regelt die Kommunikation zwischen der <a href="https://github.com/nefiertsrebliS/mqtt-landroid-bridge">MQTT-Landroid-Bridge</a> und IP-Symcon.<br>
	Die MQTT-Landroid-Bridge stellt die Verbindung zur WorxCloud her. <b>Die Funktion der MQTT-Landroid-Bridge kann nur sichergestellt werden, solange Worx die Cloud-Schnittstelle nicht verändert.</b><br>
	<b>Ab der Version V3.03 wird die bisherige Landroid-Bridge nicht mehr unterstützt! BITTE DIE BISHERIGE <a href="https://github.com/nefiertsrebliS/landroid-bridge">LANDROID-BRIDGE</a> DURCH DIE  <a href="https://github.com/nefiertsrebliS/mqtt-landroid-bridge">MQTT-LANDROID-BRIDGE</a> ERSETZEN!</b>
	<h2>Changelog</h2>
	<table>
	  <tr>
		<td>V1.0 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Grundversion für Mosquitto</td>
	  </tr>
	  <tr>
		<td>V1.01 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Fix: Statusabfrage nach Erstellung<br>
			Fix: Status-Button startet Landroid</td>
	  </tr>
	  <tr>
		<td>V1.02 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Neu: zusätzliche Variablen<br>
			Fix: Error/Status-Code jetzt als Integerwert</td>
	  </tr>
	  <tr>
		<td>V2.00 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Neu: Anbindung über Landroid-Bridge</td>
	  </tr>
	  <tr>
		<td>V2.01 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Neu: Mähzone und Gierwinkel</td>
	  </tr>
	  <tr>
		<td>V2.02 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Fix: WRX_Start() ohne Funktion</td>
	  </tr>
	  <tr>
		<td>V2.03 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Fix: Topic-Fehler bei Befehlen</td>
	  </tr>
	  <tr>
		<td>V3.00 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Neue Struktur mit <br>
			- Gateway<br>
			- Info<br>
			- Zonen<br>
			- Zeitplaner</td>
	  </tr>
	  <tr>
		<td>V3.01 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Neu: Kompatibilitätsmodus für ältere Modelle/Firmware<br>
			Neu: Winkel-Informationen</td>
	  </tr>
	  <tr>
		<td>V3.02 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Fix: WRX_SetSchedule für 2. Zeitbereich</td>
	  </tr>
	  <tr>
		<td>V3.03 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Neu: vollständige Unterstützung der neuen <a href="https://github.com/nefiertsrebliS/mqtt-landroid-bridge">MQTT-Landroid-Bridge</a><br>
			Neu: online-Kontrolle für Mäher und Bridge</td>
	  </tr>
	  <tr>
		<td>V3.04 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Fix: Fehlermeldung Scheduler unter Symcon V6</td>
	  </tr>
	  <tr>
		<td>V3.05 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Neu: Experteneinstellungen (Autolock, Drehmoment)</td>
	  </tr>
	</table>
  </body>
</html>

