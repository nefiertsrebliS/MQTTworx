<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
  </head>

  <body>
	<h1>Worx-Landroid-Modul</h1>
	<h2>Konfiguration</h2>
	Als Topic ist "landroid" (bzw. dasselbe Topic wie in der Landroid-Bridge) im Modul einzutragen.
	<h2>Funktion</h2>
	Über das WebFront kann der Worx Landroid gestartet, gestoppt und eingestellt werden.<br>
	Darüber hinaus gibt es einen Befehlsatz zur Ansteuerung des Worx Landroid per Skript.
	<h2>Mögliche PHP-Befehle</h2>
	<table>
	  <tr>
		<td>1.</td>
		<td><b><i>WRX_Start($ID)</i></b></td>
		<td>Landroid starten</td>
	  </tr>
	  <tr>
		<td>2.</td>
		<td><b><i>WRX_Stop($ID)</i></b></td>
		<td>Landroid stoppen</td>
	  </tr>
	  <tr>
		<td>3.</td>
		<td><b><i>WRX_Home($ID)</i></b></td>
		<td>Landroid zur Ladestation zurückschicken</td>
	  </tr>
	  <tr>
		<td>4.</td>
		<td><b><i>WRX_Status($ID)</i></b></td>
		<td>Holt den Status des Landroid</td>
	  </tr>
	  <tr>
		<td>5.</td>
		<td><b><i>WRX_SetRainDelay($ID, $Value)</i></b></td>
		<td>Regenverzögerung einstellen</td>
	  </tr>
	  <tr>
		<td>6.</td>
		<td><b><i>WRX_SetTimeExtension($ID, $Value)</i></b></td>
		<td>saisonale Zeitanpassung einstellen</td>
	  </tr>
	  <tr>
		<td>7.</td>
		<td><b><i>WRX_SetSchedule($ID, $Value)</i></b></td>
		<td>Zeitplan festlegen<br>
			Format:'[["12:00",200,1],["Startzeit",Mähdauer,Kantenschnitt],["10:30",118,0],["11:00",120,1],["11:00",120,0],["11:00",120,0],["11:00",0,0]]'</td>
	  </tr>
	</table>
  </body>
</html>

