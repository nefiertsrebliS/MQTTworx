<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
  </head>

  <body>
	<h1>IPS-Modul zur Einbindung von Worx-Landroid-Mährobotern in IP-Symcon via Mosquitto-Mqtt-Bridge</h1>
	<h2>Grundsätzliches</h2>
	Das Modul regelt die Kommunikation zwischen der Mosquitto-Mqtt-Bridge und IP-Symcon. Als Splitter ist der <a href="https://deinsmarthome.info/ip-symcon-module/mqttclient/">MQTT-Client von Kai Schnittcher</a> aus dem Modulstore erforderlich. Für die einwandfreie Kommunikaton der Mosquitto-Mqtt-Bridge mit der WorxCloud ist der Nutzer selbst verantwortlich.<br>
	Hinweise, wie man die Mosquitto-Mqtt-Bridge einrichten kann, erhält man im <a href="https://www.roboter-forum.com/index.php?thread/41158-landroid-%C3%BCber-eine-mosquitto-mqtt-bridge-steuern-am-besipiel-von-openhab/">Roboter-Forum</a>.
	Die Installation von Modulen wird <a href="https://www.symcon.de/service/dokumentation/komponenten/verwaltungskonsole/module-store/">hier</a> beschrieben.
	<h2>Lizenz</h2>
	<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a><br />Dieses Werk ist lizenziert unter einer <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International Lizenz</a>
	<h2>Changelog</h2>
	<table>
	  <tr>
		<td>V1.0 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Grundversion</td>
	  </tr>
	  <tr>
		<td>V1.1 &nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td>Fix: Statusabfrage nach Erstellung<br>
			Fix: Status-Button startet Landroid</td>
	  </tr>
	</table>
  </body>
</html>

