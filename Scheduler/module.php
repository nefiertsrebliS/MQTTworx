<?php

declare(strict_types=1);

class MQTTworxScheduler extends IPSModule
{

#================================================================================================
    public function Create()
#================================================================================================
    {
        //Never delete this line!
        parent::Create();
		$this->ConnectParent('{FC4FCFBA-365C-5880-4C10-C224BD1E5F3D}');
    }

#================================================================================================
    public function ApplyChanges()
#================================================================================================
    {
        //Never delete this line!
        parent::ApplyChanges();
			
#		Filter setzen
		$this->SetReceiveDataFilter(".*\"Device\":\"Scheduler\".*");

#		Status holen
		if($this->HasActiveParent())$this->sendJson("{}");

#		Puffer initieren
		$this->SetBuffer("dd", "false");
    }

#================================================================================================
	public function ReceiveData($JSONString)
#================================================================================================
    {
		$this->SendDebug("Received", $JSONString, 0);
		$data = json_decode($JSONString);
		$Topic= $data->Topic;
		$Payload= json_decode($data->Payload);
		$this->SendDebug($Topic, $data->Payload, 0);

#----------------------------------------------------------------
#		Daten in den Objektbaum schreiben
#----------------------------------------------------------------

		switch($Topic){
			case "dd":
			case "d":
				$this->SetBuffer("dd", ($Topic == "dd")?"true":"false");
				if(!@$this->GetIDForIdent("WRX_Scheduler")){
					$id = IPS_CreateEvent(2);
					IPS_SetParent($id, $this->InstanceID );
					IPS_SetPosition($id, 1);
					IPS_SetIdent($id, "WRX_Scheduler");
					IPS_SetEventActive($id, true);
					IPS_SetEventScheduleAction($id, 1, "Pause", 0x000000, "");
					IPS_SetEventScheduleAction($id, 2, "Mähen", 0x00FF00, "");
					IPS_SetEventScheduleAction($id, 3, "Mähen+Kante", 0xFFCC00, "");
				}
				$this->RegisterMessage ($this->GetIDForIdent("WRX_Scheduler"), EM_REMOVESCHEDULEGROUPPOINT);

				$this->ClearIPSSchedule();
				$this->SetIPSSchedule(1, json_encode($Payload[0]));
				if($Topic == "dd")$this->SetIPSSchedule(2, json_encode($Payload[1]));
				break;

			case "p":
				if (!IPS_VariableProfileExists('TimeExtension.WRX')) {
					IPS_CreateVariableProfile('TimeExtension.WRX', 1);
					IPS_SetVariableProfileIcon('TimeExtension.WRX', 'Intensity');
					IPS_SetVariableProfileText('TimeExtension.WRX', '', '.%');
					IPS_SetVariableProfileValues('TimeExtension.WRX', -100, 100, 10);
				}
				$this->RegisterVariableInteger('WRX_TimeExtension', $this->Translate('Time Extension'), 'TimeExtension.WRX', 10);
				SetValue($this->GetIDForIdent('WRX_TimeExtension'), $Payload);
				$this->EnableAction('WRX_TimeExtension');
				break;

			case "rd":
				if (!IPS_VariableProfileExists('Raindelay.WRX')) {
					IPS_CreateVariableProfile('Raindelay.WRX', 1);
					IPS_SetVariableProfileIcon('Raindelay.WRX', 'Clock');
					IPS_SetVariableProfileText('Raindelay.WRX', '', ' min');
					IPS_SetVariableProfileValues('Raindelay.WRX', 0, 1410, 30);
				}
				$this->RegisterVariableInteger('WRX_Raindelay', $this->Translate('Raindelay'), 'Raindelay.WRX', 20);
				$this->EnableAction('WRX_Raindelay');
				SetValue($this->GetIDForIdent('WRX_Raindelay'), $Payload);
				break;

			case "m":
				if (!IPS_VariableProfileExists('Partymode.WRX')) {
					IPS_CreateVariableProfile('Partymode.WRX', 1);
					IPS_SetVariableProfileIcon('Partymode.WRX', 'Clock');
					IPS_SetVariableProfileAssociation('Partymode.WRX', 0, $this->Translate('on time'), '',-1);
					IPS_SetVariableProfileAssociation('Partymode.WRX', 1, $this->Translate('off'), '',-1);
					IPS_SetVariableProfileAssociation('Partymode.WRX', 2, $this->Translate('on'), '',-1);
					IPS_SetVariableProfileValues('Partymode.WRX', 0, 2, 1);
				}
				$this->RegisterVariableInteger('WRX_Partymode', $this->Translate('Party-Mode'), 'Partymode.WRX', 0);
				$this->RegisterMessage ($this->GetIDForIdent("WRX_Partymode"), VM_UPDATE);
				$this->EnableAction('WRX_Partymode');
				SetValue($this->GetIDForIdent('WRX_Partymode'), $Payload);
				break;

			case "distm":
				if (!IPS_VariableProfileExists('Partyduration.WRX')) {
					IPS_CreateVariableProfile('Partyduration.WRX', 1);
					IPS_SetVariableProfileIcon('Partyduration.WRX', 'Clock');
					IPS_SetVariableProfileText('Partyduration.WRX', '', ' min');
					IPS_SetVariableProfileValues('Partyduration.WRX', 0, 60, 1);
				}
				$this->RegisterVariableInteger('WRX_Partyduration', $this->Translate('Party-Duration'), 'Partyduration.WRX', 0);
				$this->RegisterMessage ($this->GetIDForIdent("WRX_Partyduration"), VM_UPDATE);
				$this->EnableAction('WRX_Partyduration');
				SetValue($this->GetIDForIdent('WRX_Partyduration'), ($Payload > 7)?round($Payload/15)*15 : $Payload);
				break;

			case "cmd":
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
				SetValue($this->GetIDForIdent('WRX_Command'), $Payload);
				$this->EnableAction('WRX_Command');
				break;

			default:
                $this->SendDebug('Warning: ', 'Topic '.$Topic.' is not defined', 0);
				break;
		}
	}

#================================================================================================
    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
#================================================================================================
    {
        switch ($Message) {
		    case EM_REMOVESCHEDULEGROUPPOINT:
				if($Data[0] == 0 && $Data[1] == 0){
					if(!IPS_GetEvent($this->GetIDForIdent("WRX_Scheduler"))['EventActive']){
						IPS_SetEventActive($this->GetIDForIdent("WRX_Scheduler"), true);
						return;
					}
					if($this->GetBuffer("dd") == "true"){
						$this->sendJson('{ "sc": { "d": '.$this->GetSchedule(1).',  "dd": '.$this->GetSchedule(2).' } }');
					}else{
						$this->sendJson('{ "sc": { "d": '.$this->GetSchedule(1).'} }');
					}

				}
		        break;
		    case VM_UPDATE:
				switch ($SenderID) {
					case $this->GetIDforIdent('WRX_Partymode'):
						if($Data[1]){
							if($Data[0] == 0){
#								Wenn der Party-Zeitmodus an ist, erscheint die Zeiteinstellung
								IPS_SetHidden($this->GetIDForIdent('WRX_Partyduration'), false);
								if($this->GetValue('WRX_Partyduration')== 0) $this->SetPartyDuration(60);
							}else{
#								Wenn der Party-Zeitmodus aus ist, verschwindet die Zeiteinstellung
								IPS_SetHidden($this->GetIDForIdent('WRX_Partyduration'), true);
								if($this->GetValue('WRX_Partyduration') <> 0) $this->SetPartyDuration(0);
							}
						}
						break;
					case $this->GetIDforIdent('WRX_Partyduration'):
						if($Data[1]){
							if($Data[0] == 0){
								if($this->GetValue('WRX_Partymode')== 0) $this->SetPartyMode(1);
							}
						}
						break;
				}
		        break;
        }
    }
		
#================================================================================================
    public function RequestAction($Ident, $Value)
#================================================================================================
    {
	    switch ($Ident) {
	        case 'WRX_TimeExtension':
	            $this->SetTimeExtension((int)$Value);
	            break;
	        case 'WRX_Raindelay':
	            $this->SetRainDelay((int)$Value);
	            break;
	        case 'WRX_Partymode':
	            $this->SetPartyMode((int)$Value);
	            break;
	        case 'WRX_Partyduration':
	            $this->SetPartyDuration((int)$Value);
	            break;
            case 'WRX_Command':
                $this->SetCommand((int)$Value);
                break;
	        default:
	            $this->SendDebug('Request Action', 'No Action defined: ' . $Ident, 0);
	            break;
	    }
    }

#================================================================================================
    public function SetCommand(int $value) 
#================================================================================================
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

#================================================================================================
    public function Start() 
#================================================================================================
	{
		$this->sendJson('{"cmd":1}');
    }
		
#================================================================================================
    public function Stop() 
#================================================================================================
	{
		$this->sendJson('{"cmd":2}');
    }
		
#================================================================================================
    public function Home() 
#================================================================================================
	{
		$this->sendJson('{"cmd":3}');
    }
		
#================================================================================================
    public function Status() 
#================================================================================================
	{
		$this->sendJson('{}');
    }

#================================================================================================
	protected function ClearIPSSchedule()
#================================================================================================
	{
        $ID = $this->GetIDForIdent("WRX_Scheduler");
        for($Day = 0; $Day < 7; $Day++){
            @IPS_SetEventScheduleGroup($ID, $Day, 0);
            IPS_SetEventScheduleGroup($ID, $Day, 2**$Day);
            IPS_SetEventScheduleGroupPoint($ID, $Day, 0, 0, 0, 0, 1);
        }
    }

#================================================================================================
	protected function SetIPSSchedule(int $Position, $Payload)
#================================================================================================
	{
        $ID = $this->GetIDForIdent("WRX_Scheduler");
        foreach(json_decode($Payload) as $Day => $Times){
            $Day = ($Day == 0)?6:$Day-1;
            $Start = explode(":", $Times[0]);
            if($Times[2] == 0){
                if((int)$Start[0] + (int)$Start[1] > 0)IPS_SetEventScheduleGroupPoint($ID, $Day, 2**$Position, $Start[0], $Start[1], 0, 2);
            }else{
                if((int)$Start[0] + (int)$Start[1] > 0)IPS_SetEventScheduleGroupPoint($ID, $Day, 2**$Position, $Start[0], $Start[1], 0, 3);
            }
            $End = $Start[0]*60 + $Start[1] + $Times[1];
            $EndHour = floor($End/60);
            $EndMin  = $End - $EndHour*60;
            if((int)$Start[0] + (int)$Start[1] > 0)IPS_SetEventScheduleGroupPoint($ID, $Day, 2**$Position + 1, $EndHour, $EndMin, 0, 1);
        }
    }

#================================================================================================
    public function GetSchedule(int $Position)
#================================================================================================
	{
        switch($Position){
            case 1:
                $Pointer = 1;
                break;
            case 2:
				if($this->GetBuffer("dd") == "false")return(false);
                $Pointer = 3;
                break;
            default:
                return(false);
        }

		$ID = $this->GetIDForIdent("WRX_Scheduler");
		$Start = array();
		foreach(IPS_GetEvent($ID)['ScheduleGroups'] as $Group){
		    $Day = ($Group['ID'] == 6)?0:$Group['ID']+1;
		    for($i = 1; $i < count($Group['Points']); $i++){
		        if($i > 4)break;
		        $Point = $Group['Points'][$i];
		        $Point['Start']['Minute'] = round($Point['Start']['Minute']/15)*15;
		        if($Point['Start']['Minute'] == 60){
		            $Point['Start']['Minute'] = 0;
		            $Point['Start']['Hour'] += 1;
		        }
		        $Start[$Day][$i]['Text'] = (($Point['Start']['Hour'] < 10)?"0":"").$Point['Start']['Hour'].':'.$Point['Start']['Minute'];
		        if($Point['Start']['Minute'] == 0)$Start[$Day][$i]['Text'] .= "0";
		        $Start[$Day][$i]['Minute'] = $Point['Start']['Hour'] * 60 + $Point['Start']['Minute'];
		        $Start[$Day][$i]['Action'] = $Point['ActionID'];

		    }

		}

		$Payload  = array();
		for($i = 0; $i < 7; $i++){
		    if(isset($Start[$i][$Pointer])){
		        $Payload[]  = array($Start[$i][$Pointer]['Text'], $Start[$i][$Pointer+1]['Minute'] - $Start[$i][$Pointer]['Minute'], ($Start[$i][$Pointer]['Action'] == 3)?1:0);
		    }else{
		        $Payload[]  = array("00:00", 0, 0);
		    }
		}
        return(json_encode($Payload));
    }

#================================================================================================
    public function SetSchedule(int $pos, string $value) 
#================================================================================================
	{
		if($pos == 1)$this->sendJson('{ "sc": { "d": '.$value.' } }');
		if($pos == 2)$this->sendJson('{ "sc": { "dd": '.$value.' } }');
		if($pos <> 1 && $pos <> 2)$this->SendDebug('SetSchedule', 'Position not defined: ' . $pos, 0); 
    }
		
#================================================================================================
    public function SetPartyDuration(int $value) 
#================================================================================================
	{
		if( $value < 0) $value = 0;
		if( $value > 1440) $value = 1440;
		$this->sendJson('{ "sc": { "distm": '.$value.' } }');
    }
		
#================================================================================================
    public function SetPartyMode(int $value) 
#================================================================================================
	{
		if( $value < 0) $value = 0;
		if( $value > 2) $value = 2;
		$this->sendJson('{ "sc": { "m": '.$value.' } }');
    }
		
#================================================================================================
    public function SetRainDelay(int $value) 
#================================================================================================
	{
		$value = round($value/30, 0)*30;
		if( $value < 0) $value = 0;
		if( $value > 1410) $value = 1410;
		$this->sendJson('{ "rd": '.$value.' }');
    }
		
#================================================================================================
    public function SetTimeExtension(int $value) 
#================================================================================================
	{
		$value = round($value, -1);
		if($value < -100)$value = -100;
		if($value > 100)$value = 100;
		$this->sendJson('{ "sc": { "p": '.$value.' } }');
    }

#================================================================================================
	protected function sendJson($Payload)
#================================================================================================
	{
	    $Data['DataID'] = '{6C4F48B9-C1E4-049F-193B-A0A56D2B855E}';
	    $Data['Topic'] = '/set/json';
	    $Data['Payload'] = $Payload;

	    $DataJSON = json_encode($Data, JSON_UNESCAPED_SLASHES);
		$this->SendDebug("Sended", $DataJSON, 0);
	    $this->SendDataToParent($DataJSON);
    }
}
