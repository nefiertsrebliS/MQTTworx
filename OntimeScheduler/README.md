<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
  </head>

  <body>
	<h1>One Time Scheduler</h1>
	<h2>Konfiguration</h2>
	Es ist keine Konfiguration erforderlich.
	<h2>Funktion</h2>
	Mit dem One Time Scheduler können Mähvorgänge einmalig gestartet werden. Dafür können sowohl die Mähdauer als auch der Kantenschnitt gewählt werden.
	<h2>Mögliche PHP-Befehle</h2>
	<table>
	  <tr>
		<td>1.</td>
		<td><b><i>WRX_StartOTS($ID, $Bordercut, $Mowduration)</i></b></td>
		<td>Einmaliger Mähvorgang<br>
			$Bordercut [1,0] an/aus<br>
			$Mowduration [0,480] in Minuten. Die Schrittweite beträgt 30 Minuten.</td>
	  </tr>
	</table>
  </body>
</html>

