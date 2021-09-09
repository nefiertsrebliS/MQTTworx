<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
  </head>

  <body>
	<h1>Zones</h1>
	<h2>Konfiguration</h2>
	Es ist keine Konfiguration erforderlich.
	<h2>Funktion</h2>
	Die Zonen-Instanz braucht nur installiert werden, wenn man den Worx Landroid in verschiedenen Zonen betreiben möchte.<br>
	Alle erforderlichen Einstellungen können über das WebFront vorgenommen werden.<br>
	Darüber hinaus gibt es einen Befehlsatz zur Einstellung der Zonen per Skript.
	<h2>Mögliche PHP-Befehle</h2>
	<table>
	  <tr>
		<td>1.</td>
		<td><b><i>WRX_SetStartinZone($ID, $Zone)</i></b></td>
		<td>Zone festlegen, die der Worx beim nächsten Start anfährt.<br>
			Diesen Wert bitte nur setzen, wenn sich der Worx in der Ladestation befindet. Ansonsten wird die Zone bei der Heimkehr des Worx überschrieben</td>
	  </tr>
	  <tr>
		<td>2.</td>
		<td><b><i>WRX_SetZoneDistance($ID, $Distance)</i></b></td>
		<td>Festlegung der Entfernung zwischen Zone und Ladestation entlang des Begrenzungsdrahtes in Metern</td>
	  </tr>
	  <tr>
		<td>3.</td>
		<td><b><i>WRX_SetShareinZone($ID, $Percent)</i></b></td>
		<td>Festlegung wie häufig eine Zone angefahren wird. Die Einstellung erfolgt in 10%-Schritten</td>
	  </tr>
	</table>
  </body>
</html>

