<?php

declare(strict_types=1);

class MQTTworx extends IPSModule
{

    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Topic', "");

		if (in_array('{EE0D345A-CF31-428A-A613-33CE98E752DD}', IPS_GetModuleList())) {
	        $this->ConnectParent('{EE0D345A-CF31-428A-A613-33CE98E752DD}');
		}
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        $this->RegisterMessage(0, IM_CHANGESTATUS);
        $this->RegisterMessage(0, IM_CONNECT);

			
#		Filter setzen
		$this->SetReceiveDataFilter('.*'.preg_quote('\"Topic\":\"').$this->ReadPropertyString("Topic").preg_quote('\"').'.*');

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
        $Buffer = json_decode($JSONString)->Buffer;
        $this->SendDebug('Received', $Buffer, 0);

        $data = json_decode(utf8_decode($Buffer));

#----------------------------------------------------------------
#		Daten in den Objektbaum schreiben
#----------------------------------------------------------------

		if($data->SENDER =='MQTT_GET_PAYLOAD') {
		    $Payload = json_decode(strstr($data->Payload, "{"));
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
				}
				if (property_exists($Payload->dat, 'le')){
					$this->RegisterVariableString('WRX_Error', $this->Translate('Error'), '', 0);
					$errors = array(0=>"NONE", 
									1=>"TRAPPED", 
									2=>"LIFTED", 
									3=>"WIRE MISSING", 
									4=>"OUTSIDE WIRE", 
									5=>"RAINING", 
									8=>"MOTOR BLADE FAULT", 
									9=>"MOTOR WHEELS FAULT",
									10=>"TRAPPED TIMEOUT FAULT",
									11=>"UPSIDE DOWN",
									12=>"BATTERY LOW",
									13=>"REVERSE WIRE",
									14=>"BATTERY CHARGE ERROR",
									15=>"HOME FIND TIMEOUT",
									16=>"LOCK",
									17=>"BATTERY OVERTEMP");
					SetValue($this->GetIDForIdent('WRX_Error'), $this->Translate($errors[(int)$Payload->dat->le]));
				}
				if (property_exists($Payload->dat, 'ls')){
					$this->RegisterVariableString('WRX_Status', $this->Translate('Status'), '', 0);
					$status= array(	0=>"IDLE",
									1=>"HOME",
									2=>"START SEQUENCE",
									3=>"LEAVE HOUSE",
									4=>"FOLLOW WIRE",
									5=>"SEARCHING HOME",
									6=>"SEARCHING WIRE",
									7=>"GRASS CUTTING",
									8=>"LIFT RECOVERY",
									9=>"TRAPPED RECOVERY",
									10=>"BLADE BLOCKED RECOVERY",
									11=>"DEBUG",
									12=>"REMOTE CONTROL",
									30=>"WIRE GOING HOME",
									31=>"WIRE AREA TRAINING",
									32=>"WIRE BORDER CUT",
									33=>"WIRE AREA SEARCH",
									34=>"PAUSE");
					SetValue($this->GetIDForIdent('WRX_Status'), $this->Translate($status[(int)$Payload->dat->ls]));
				}
				if (property_exists($Payload->dat, 'rsi')){
					$this->RegisterVariableInteger('WRX_RSSI', 'RSSI', 'Intensity.dB.WRX', 0);
					SetValue($this->GetIDForIdent('WRX_RSSI'), $Payload->dat->rsi);

					if (!IPS_VariableProfileExists('Intensity.dB.WRX')) {
						IPS_CreateVariableProfile('Intensity.dB.WRX', 1);
						IPS_SetVariableProfileIcon('Intensity.dB.WRX', 'Intensity');
						IPS_SetVariableProfileText('Intensity.dB.WRX', '', ' dB');
					}
				}
				if (property_exists($Payload->dat, 'lk')){
					$this->RegisterVariableBoolean('WRX_Lock', $this->Translate('Lock'), '~Switch', 0);
					SetValue($this->GetIDForIdent('WRX_Lock'), $Payload->dat->lk);
				}
			}

			if (property_exists($Payload, 'cfg')) {
				if (property_exists($Payload->cfg, 'cmd')){
					$this->RegisterVariableInteger('WRX_Command', $this->Translate('Command'), 'Command.WRX', 0);
					SetValue($this->GetIDForIdent('WRX_Command'), $Payload->cfg->cmd);
					$this->EnableAction('WRX_Command');
					if (!IPS_VariableProfileExists('Command.WRX')) {
						IPS_CreateVariableProfile('Command.WRX', 1);
						IPS_SetVariableProfileIcon('Command.WRX', 'Power');
						IPS_SetVariableProfileAssociation('Command.WRX', 0, $this->Translate('Status'), '',-1);
						IPS_SetVariableProfileAssociation('Command.WRX', 1, $this->Translate('Start'), '',-1);
						IPS_SetVariableProfileAssociation('Command.WRX', 2, $this->Translate('Stop'), '',-1);
						IPS_SetVariableProfileAssociation('Command.WRX', 3, $this->Translate('go Home'), '',-1);
						IPS_SetVariableProfileValues('Command.WRX', 0, 3, 1);
					}
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
					$this->RegisterVariableInteger('WRX_Raindelay', $this->Translate('Raindelay'), 'min.WRX', 0);
					$this->EnableAction('WRX_Raindelay');
					SetValue($this->GetIDForIdent('WRX_Raindelay'), $Payload->cfg->rd);

					if (!IPS_VariableProfileExists('min.WRX')) {
						IPS_CreateVariableProfile('min.WRX', 1);
						IPS_SetVariableProfileIcon('min.WRX', 'Clock');
						IPS_SetVariableProfileText('min.WRX', '', ' min');
						IPS_SetVariableProfileValues('min.WRX', 0, 720, 30);
					}
				}

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
		if($value <= 0){
			$this->Status();
		}else{
			if($value > 3)$value = 3;
			$msg["cmd"] = $value;
			$this->SendData(json_encode($msg));
		}
    }

    public function Start() 
	{
		$msg["cmd"] = 1;
		$this->SendData(json_encode($msg));
    }
		
    public function Stop() 
	{
		$msg["cmd"] = 2;
		$this->SendData(json_encode($msg));
    }
		
    public function Home() 
	{
		$msg["cmd"] = 3;
		$this->SendData(json_encode($msg));
    }
		
    public function Status() 
	{
		$this->SendData("{}");
    }
		
    public function SetRainDelay(int $value) 
	{
		$value = round($value/30, 0)*30;
		$msg["rd"] = $value;
		$this->SendData(json_encode($msg));
    }
		
    public function SetTimeExtension(int $value) 
	{
		$value = round($value, -1);
		if($value < -100)$value = -100;
		if($value > 100)$value = 100;
		$msg["sc"] = array("p"=>$value);
		$this->SendData(json_encode($msg));
    }
		
    public function SetSchedule(string $value) 
	{
		$msg["sc"] = array("d"=>json_decode($value));
		$this->SendData(json_encode($msg));
    }
		
    protected function SendData(string $msg) 
	{
		$buffer["Topic"] = "pub";
		$buffer["Payload"] = $msg;
		$buffer["Retain"] = 0;
	    $data['Buffer'] = json_encode($buffer, JSON_UNESCAPED_SLASHES);
		$data['DataID'] ='{97475B04-67C3-A74D-C970-E9409B0EFA1D}';
	    $DataJSON = json_encode($data, JSON_UNESCAPED_SLASHES);
		$this->SendDataToParent($DataJSON);
    }

}
