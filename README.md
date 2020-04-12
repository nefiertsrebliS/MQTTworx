<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
  </head>

  <body>
	<h1>IPS-Modul zur Einbindung von Worx-Landroid-Mährobotern in IP-Symcon via Landroid-Bridge</h1>
	<h2>Grundsätzliches</h2>
	Das Modul regelt die Kommunikation zwischen der <a href="https://github.com/nefiertsrebliS/landroid-bridge">Landroid-Bridge</a> und IP-Symcon.<br>
	Die Landroid-Bridge stellt die Verbindung zur WorxCloud her. <b>Die Funktion der Landroid-Bridge kann nur sichergestellt werden, solange Worx die Cloud-Schnittstelle nicht verändert.</b>
	<h2>Lizenz</h2>
	<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a><br />Dieses Werk ist lizenziert unter einer <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International Lizenz</a>
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
	</table>
  </body>
</html>

