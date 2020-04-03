<?php

declare(strict_types=1);

class MQTTworx extends IPSModule
{

    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Topic', "");

		$this->ConnectParent('{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}');
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        $this->RegisterMessage(0, IM_CHANGESTATUS);
        $this->RegisterMessage(0, IM_CONNECT);

			
#		Filter setzen
		$this->SetReceiveDataFilter(".*\"Topic\":\"".$this->ReadPropertyString("Topic")."/.*");

#		Status holen
		if($this->HasActiveParent())$this->Status();

    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        $this->SendDebug(__FUNCTION__, 'SenderID: ' . $SenderID . ' MessageID:' . $Message . 'Data: ' . print_r($Data, true), 0);
        switch ($Message) {
            case IM_CHANGESTATUS:
				if($SenderID == @IPS_GetInstance($this->InstanceID)['ConnectionID']){
				}
				if($SenderID == $this->InstanceID){
				}
                break;
        }
    }

	public function ReceiveData($JSONString)
    {
		$this->SendDebug("Received", $JSONString, 0);
		$data = json_decode($JSONString);

#----------------------------------------------------------------
#		Daten in den Objektbaum schreiben
#----------------------------------------------------------------

	    $Payload = json_decode($data->Payload);
		if (property_exists($Payload, 'dat')) {
			if (property_exists($Payload->dat, 'fw')){
				$this->RegisterVariableFloat('WRX_Firmware', 'Firmware', '', 0);
				SetValue($this->GetIDForIdent('WRX_Firmware'), $Payload->dat->fw);
			}
			if (property_exists($Payload->dat, 'mac')){
				$this->RegisterVariableString('WRX_mac', $this->Translate('MAC-Adress'), '', 0);
				SetValue($this->GetIDForIdent('WRX_mac'), $Payload->dat->mac);
			}
			if (property_exists($Payload->dat, 'bt')){
				if (property_exists($Payload->dat->bt, 't')){
					$this->RegisterVariableFloat('WRX_bt_t', $this->Translate('Battery-Temperature'), '~Temperature', 0);
					SetValue($this->GetIDForIdent('WRX_bt_t'), $Payload->dat->bt->t);
				}
				if (property_exists($Payload->dat->bt, 'v')){
					$this->RegisterVariableFloat('WRX_bt_v', $this->Translate('Battery-Voltage'), '~Volt', 0);
					SetValue($this->GetIDForIdent('WRX_bt_v'), $Payload->dat->bt->v);
				}
				if (property_exists($Payload->dat->bt, 'p')){
					$this->RegisterVariableInteger('WRX_bt_p', $this->Translate('Battery-Load'), '~Intensity.100', 0);
					SetValue($this->GetIDForIdent('WRX_bt_p'), $Payload->dat->bt->p);
				}
				if (property_exists($Payload->dat->bt, 'nr')){
					$this->RegisterVariableFloat('WRX_bt_nr', $this->Translate('Battery-Loadcycles'), '', 0);
					SetValue($this->GetIDForIdent('WRX_bt_nr'), $Payload->dat->bt->nr);
				}
				if (property_exists($Payload->dat->bt, 'c')){
					$this->RegisterVariableBoolean('WRX_bt_c', $this->Translate('Battery-Charging'), '~Switch', 0);
					SetValue($this->GetIDForIdent('WRX_bt_c'), $Payload->dat->bt->c);
				}
			}
			if (property_exists($Payload->dat, 'st')){
				if (property_exists($Payload->dat->st, 'b')){
					if (!IPS_VariableProfileExists('min.WRX')) {
						IPS_CreateVariableProfile('min.WRX', 1);
						IPS_SetVariableProfileIcon('min.WRX', 'Clock');
						IPS_SetVariableProfileText('min.WRX', '', ' min');
					}
					$this->RegisterVariableInteger('WRX_st_b', $this->Translate('Mowing Time'), 'min.WRX', 0);
					SetValue($this->GetIDForIdent('WRX_st_b'), $Payload->dat->st->b);
				}
				if (property_exists($Payload->dat->st, 'wt')){
					if (!IPS_VariableProfileExists('min.WRX')) {
						IPS_CreateVariableProfile('min.WRX', 1);
						IPS_SetVariableProfileIcon('min.WRX', 'Clock');
						IPS_SetVariableProfileText('min.WRX', '', ' min');
					}
					$this->RegisterVariableInteger('WRX_st_wt', $this->Translate('Working Time'), 'min.WRX', 0);
					SetValue($this->GetIDForIdent('WRX_st_wt'), $Payload->dat->st->wt);
				}
				if (property_exists($Payload->dat->st, 'd')){
					if (!IPS_VariableProfileExists('Distance.WRX')) {
						IPS_CreateVariableProfile('Distance.WRX', 1);
						IPS_SetVariableProfileIcon('Distance.WRX', 'Distance');
						IPS_SetVariableProfileText('Distance.WRX', '', ' m');
					}
					$this->RegisterVariableInteger('WRX_st_d', $this->Translate('Distance'), 'Distance.WRX', 0);
					SetValue($this->GetIDForIdent('WRX_st_d'), $Payload->dat->st->d);
				}
			}
			if (property_exists($Payload->dat, 'le')){
				if (!IPS_VariableProfileExists('Error.WRX')) {
					IPS_CreateVariableProfile('Error.WRX', 1);
					IPS_SetVariableProfileAssociation("Error.WRX", 0, $this->Translate('NONE'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 1, $this->Translate('TRAPPED'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 2, $this->Translate('LIFTED'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 3, $this->Translate('WIRE MISSING'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 4, $this->Translate('OUTSIDE WIRE'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 5, $this->Translate('RAINING'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 8, $this->Translate('MOTOR BLADE FAULT'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 9, $this->Translate('MOTOR WHEELS FAULT'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 10, $this->Translate('TRAPPED TIMEOUT FAULT'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 11, $this->Translate('UPSIDE DOWN'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 12, $this->Translate('BATTERY LOW'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 13, $this->Translate('REVERSE WIRE'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 14, $this->Translate('BATTERY CHARGE ERROR'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 15, $this->Translate('HOME FIND TIMEOUT'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 16, $this->Translate('LOCK'), "", -1);
					IPS_SetVariableProfileAssociation("Error.WRX", 17, $this->Translate('BATTERY OVERTEMP'), "", -1);
				};
				$this->RegisterVariableInteger('WRX_Error', $this->Translate('Error'), 'Error.WRX', 0);
				SetValue($this->GetIDForIdent('WRX_Error'), $Payload->dat->le);
			}
			if (property_exists($Payload->dat, 'ls')){
				if (!IPS_VariableProfileExists('Status.WRX')) {
					IPS_CreateVariableProfile('Status.WRX', 1);
					IPS_SetVariableProfileAssociation("Status.WRX", 0, $this->Translate('IDLE'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 1, $this->Translate('HOME'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 2, $this->Translate('START SEQUENCE'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 3, $this->Translate('LEAVE HOUSE'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 4, $this->Translate('FOLLOW WIRE'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 5, $this->Translate('SEARCHING HOME'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 6, $this->Translate('SEARCHING WIRE'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 7, $this->Translate('GRASS CUTTING'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 8, $this->Translate('LIFT RECOVERY'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 9, $this->Translate('TRAPPED RECOVERY'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 10, $this->Translate('BLADE BLOCKED RECOVERY'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 11, $this->Translate('DEBUG'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 12, $this->Translate('REMOTE CONTROL'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 30, $this->Translate('WIRE GOING HOME'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 31, $this->Translate('WIRE AREA TRAINING'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 32, $this->Translate('WIRE BORDER CUT'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 33, $this->Translate('WIRE AREA SEARCH'), "", -1);
					IPS_SetVariableProfileAssociation("Status.WRX", 34, $this->Translate('PAUSE'), "", -1);
				};
				$this->RegisterVariableInteger('WRX_Status', $this->Translate('Status'), 'Status.WRX', 0);
				SetValue($this->GetIDForIdent('WRX_Status'), $Payload->dat->ls);
			}
			if (property_exists($Payload->dat, 'rsi')){
				if (!IPS_VariableProfileExists('Intensity.dB.WRX')) {
					IPS_CreateVariableProfile('Intensity.dB.WRX', 1);
					IPS_SetVariableProfileIcon('Intensity.dB.WRX', 'Intensity');
					IPS_SetVariableProfileText('Intensity.dB.WRX', '', ' dB');
				}
				$this->RegisterVariableInteger('WRX_RSSI', 'RSSI', 'Intensity.dB.WRX', 0);
				SetValue($this->GetIDForIdent('WRX_RSSI'), $Payload->dat->rsi);
			}
			if (property_exists($Payload->dat, 'lk')){
				$this->RegisterVariableBoolean('WRX_Lock', $this->Translate('Lock'), '~Lock', 0);
				SetValue($this->GetIDForIdent('WRX_Lock'), $Payload->dat->lk);
			}
		}

		if (property_exists($Payload, 'cfg')) {
			if (property_exists($Payload->cfg, 'cmd')){
				if (!IPS_VariableProfileExists('Command.WRX')) {
					IPS_CreateVariableProfile('Command.WRX', 1);
					IPS_SetVariableProfileIcon('Command.WRX', 'Power');
					IPS_SetVariableProfileAssociation('Command.WRX', 0, $this->Translate('Status'), '',-1);
					IPS_SetVariableProfileAssociation('Command.WRX', 1, $this->Translate('Start'), '',-1);
					IPS_SetVariableProfileAssociation('Command.WRX', 2, $this->Translate('Stop'), '',-1);
					IPS_SetVariableProfileAssociation('Command.WRX', 3, $this->Translate('go Home'), '',-1);
					IPS_SetVariableProfileValues('Command.WRX', 0, 3, 1);
				}
				$this->RegisterVariableInteger('WRX_Command', $this->Translate('Command'), 'Command.WRX', 0);
				SetValue($this->GetIDForIdent('WRX_Command'), $Payload->cfg->cmd);
				$this->EnableAction('WRX_Command');
			}
			if (property_exists($Payload->cfg, 'sc')){
				$this->RegisterVariableString('WRX_schedule', $this->Translate('Schedule'), '', 0);
				SetValue($this->GetIDForIdent('WRX_schedule'), json_encode($Payload->cfg->sc->d));

				if (!IPS_VariableProfileExists('TimeExtension.WRX')) {
					IPS_CreateVariableProfile('TimeExtension.WRX', 1);
					IPS_SetVariableProfileIcon('TimeExtension.WRX', 'Intensity');
					IPS_SetVariableProfileText('TimeExtension.WRX', '', '.%');
					IPS_SetVariableProfileValues('TimeExtension.WRX', -100, 100, 10);
				}
				$this->RegisterVariableInteger('WRX_TimeExtension', $this->Translate('Time Extension'), 'TimeExtension.WRX', 0);
				SetValue($this->GetIDForIdent('WRX_TimeExtension'), json_encode($Payload->cfg->sc->p));
				$this->EnableAction('WRX_TimeExtension');
			}
			if (property_exists($Payload->cfg, 'rd')){
				if (!IPS_VariableProfileExists('Raindelay.WRX')) {
					IPS_CreateVariableProfile('Raindelay.WRX', 1);
					IPS_SetVariableProfileIcon('Raindelay.WRX', 'Clock');
					IPS_SetVariableProfileText('Raindelay.WRX', '', ' min');
					IPS_SetVariableProfileValues('Raindelay.WRX', 0, 720, 30);
				}
				$this->RegisterVariableInteger('WRX_Raindelay', $this->Translate('Raindelay'), 'Raindelay.WRX', 0);
				$this->EnableAction('WRX_Raindelay');
				SetValue($this->GetIDForIdent('WRX_Raindelay'), $Payload->cfg->rd);
			}

		}
	}
		
    public function RequestAction($Ident, $Value)
    {
        switch ($Ident) {
            case 'WRX_Command':
                $this->Command($Value);
                break;
            case 'WRX_Raindelay':
                $this->SetRainDelay($Value);
                break;
            case 'WRX_TimeExtension':
                $this->SetTimeExtension($Value);
                break;
            default:
                $this->SendDebug('Request Action', 'No Action defined: ' . $Ident, 0);
                break;
        }
    }

    public function Command(int $value) 
	{
		switch ($value) {
			case 0:
				$this->Status();
				break;
			case 1:
				$this->Start();
				break;
			case 2:
				$this->Stop();
				break;
			case 3:
				$this->Home();
				break;
			default:
                $this->SendDebug('WRX_Command', 'No Action defined: ' . $value, 0);
				break;
		}
    }

    public function Start() 
	{
		$msg["cmd"] = 1;
		$this->SendData(json_encode($msg));
    }
		
    public function Stop() 
	{
		$this->sendMQTT('landroid/set/pause', "");
    }
		
    public function Home() 
	{
		$this->sendMQTT('landroid/set/stop', "");
    }
		
    public function Status() 
	{
		$this->sendMQTT('landroid/set/poll', "");
    }
		
    public function SetRainDelay(int $value) 
	{
		$value = round($value/30, 0)*30;
		$this->sendMQTT('landroid/set/rainDelay', (string) $value);
    }
		
    public function SetTimeExtension(int $value) 
	{
		$value = round($value, -1);
		if($value < -100)$value = -100;
		if($value > 100)$value = 100;
		$this->sendMQTT('landroid/set/timeExtension', (string) $value);
    }
		
    public function SetSchedule(string $value) 
	{
		$this->sendMQTT('landroid/set/schedule', (string) $value);
    }

	protected function sendMQTT($Topic, $Payload)
	{
	    $Data['DataID'] = '{043EA491-0325-4ADD-8FC2-A30C8EEB4D3F}';
	    $Data['PacketType'] = 3;
	    $Data['QualityOfService'] = 0;
	    $Data['Retain'] = false;
	    $Data['Topic'] = $Topic;
	    $Data['Payload'] = $Payload;

	    $DataJSON = json_encode($Data, JSON_UNESCAPED_SLASHES);
		$this->SendDebug("Sended", $DataJSON, 0);
	    $this->SendDataToParent($DataJSON);
    }
}
