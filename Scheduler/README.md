<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
  </head>

  <body>
	<h1>Scheduler</h1>
	<h2>Konfiguration</h2>
	Es ist keine Konfiguration erforderlich.
	<h2>Funktion</h2>
	Über die Scheduler-Instanz wird der Worx Landroid aus IP-Symcon gesteuert.<br>
	Alle erforderlichen Einstellungen können über das WebFront vorgenommen werden.<br>
	Darüber hinaus gibt es einen Befehlsatz zum Starten, Stoppen, nach Hause schicken und zur Einstellung des Zeitplans oder der Regenverzögerung per Skript.
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
		<td><b><i>WRX_SetSchedule($ID, $Pos, $Value)</i></b></td>
		<td>Zeitplan festlegen<br>
			$Pos = 1: 1. Zeitfenster am Tag, $Pos = 2: 2. Zeitfenster am Tag<br>
			JSON-Format für $Value:'[["12:00",200,1],["Startzeit",Mähdauer,Kantenschnitt],["10:30",118,0],["11:00",120,1],["11:00",120,0],["11:00",120,0],["11:00",0,0]]'</td>
	  </tr>
	  <tr>
		<td>8.</td>
		<td><b><i>WRX_SetPartyMode($ID, $Value)</i></b></td>
		<td>Party-Modus festlegen<br>
			$Value = 0: Party-Modus für eine bestimmte Zeit<br>
			$Value = 1: Party-Modus aus<br>
			$Value = 2: Party-Modus dauerhaft an</td>
	  </tr>
	  <tr>
		<td>9.</td>
		<td><b><i>WRX_SetPartyDuration($ID, $Value)</i></b></td>
		<td>Dauer des Party-Modus festlegen<br>
			Hat nur einen Effekt, wenn der Party-Modus für eine bestimmte Zeit eingestellt ist (siehe WRX_SetPartyMode)</td>
	  </tr>
	  <tr>
		<td>10.</td>
		<td><b><i>WRX_GetSchedule($ID, $Pos)</i></b></td>
		<td>Zeitplan holen<br>
			$Pos = 1: 1. Zeitfenster am Tag, $Pos = 2: 2. Zeitfenster am Tag<br>
			Rückgabe im JSON-Format: '[["12:00",200,1],["Startzeit",Mähdauer,Kantenschnitt],["10:30",118,0],["11:00",120,1],["11:00",120,0],["11:00",120,0],["11:00",0,0]]'</td>
	  </tr>
	</table>
  </body>
</html>

