<?php

declare(strict_types=1);

class MQTTworxZones extends IPSModule
{

#================================================================================================
    public function Create()
#================================================================================================
    {
        //Never delete this line!
        parent::Create();
		$this->ConnectParent('{FC4FCFBA-365C-5880-4C10-C224BD1E5F3D}');

		$this->RegisterAttributeInteger("lz", 0);
		$this->RegisterAttributeString("mzv", 0);
    }

#================================================================================================
    public function ApplyChanges()
#================================================================================================
    {
        //Never delete this line!
        parent::ApplyChanges();
			
#	Filter setzen
	$this->SetReceiveDataFilter(".*\"Device\":\"Zones\".*");

#	Status holen
	if($this->HasActiveParent())$this->sendJson("{}");
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
			case "mz":
				if (!IPS_VariableProfileExists('mzDistance.WRX')) {
					IPS_CreateVariableProfile('mzDistance.WRX', 1);
					IPS_SetVariableProfileIcon('mzDistance.WRX', 'Distance');
					IPS_SetVariableProfileText('mzDistance.WRX', '', ' m');
					IPS_SetVariableProfileValues('mzDistance.WRX', 0, 1000, 1);
				}
				foreach($Payload as $key => $mz){
					$this->registerVariableInteger('WRX_Mowingzone_'.$key, $this->Translate('Mowingzone').' '.($key+1), 'mzDistance.WRX', $key);
					$this->EnableAction('WRX_Mowingzone_'.$key);
					$this->SetValue('WRX_Mowingzone_'.$key, $mz);
				}
				break;
			case "mzv":
				$this->WriteAttributeString("mzv", json_encode($Payload));
				if (!IPS_VariableProfileExists('ShareinZone.WRX')) {
					IPS_CreateVariableProfile('ShareinZone.WRX', 1);
					IPS_SetVariableProfileIcon('ShareinZone.WRX', 'Intensity');
					IPS_SetVariableProfileText('ShareinZone.WRX', '', ' %');
					IPS_SetVariableProfileValues('ShareinZone.WRX', 0, 100, 10);
				}
				for($key = 0; $key <4; $key++){
					$this->registerVariableInteger('WRX_ShareinZone_'.$key, $this->Translate('Share in Zone').' '.($key+1), 'ShareinZone.WRX', $key+10);
					$this->EnableAction('WRX_ShareinZone_'.$key);
					if(isset(array_count_values($Payload)[$key])){
						$this->SetValue('WRX_ShareinZone_'.$key, ($this->GetValue('WRX_Mowingzone_'.$key)>0)?array_count_values($Payload)[$key]*10 : 0);
					}else{
						$this->SetValue('WRX_ShareinZone_'.$key, 0);
					}
				}
				$next = $this->ReadAttributeInteger("lz");
				
				if (!IPS_VariableProfileExists('StartingZone.WRX')) {
					IPS_CreateVariableProfile('StartingZone.WRX', 1);
					IPS_SetVariableProfileIcon('StartingZone.WRX', '');
					IPS_SetVariableProfileText('StartingZone.WRX', '', '');
				}
				$this->RegisterVariableInteger('WRX_StartingZone', $this->Translate('Start in Zone'), 'StartingZone.WRX', 100);

				$max = 4;
				for($i = 0; $i < 4; $i++){

					if($this->GetValue("WRX_Mowingzone_".$i) == 0){
						$max = $i;
						break;
					}
				}
				if($max == 0){
#					ohne Zonen keine Zonenauswahl
					IPS_SetHidden($this->GetIDForIdent('WRX_StartingZone'), true);
				}else{
#					sind Zonen definiert, so wird die Zonenauswahl sichtbar
					IPS_SetHidden($this->GetIDForIdent('WRX_StartingZone'), false);
					IPS_SetVariableProfileValues('StartingZone.WRX', 1, $max, 1);
				}

				$this->EnableAction('WRX_StartingZone');
				$this->SetValue('WRX_StartingZone', $Payload[$next]+1);


				break;
			case "lz":
				$this->WriteAttributeInteger("lz", $Payload);
				break;

			default:
                $this->SendDebug('Warning: ', 'Topic '.$Topic.' is not defined', 0);
				break;
		}
	}
		
#================================================================================================
    public function RequestAction($Ident, $Value)
#================================================================================================
    {
		$split = explode("_", $Ident);
		if(count($split)>1){
		    switch ($split[1]) {
		        case 'Mowingzone':
		            $this->SetZoneDistance((int)$split[2], $Value);
		            break;
		        case 'ShareinZone':
		            $this->SetShareinZone((int)$split[2], $Value);
		            break;
		        case 'StartingZone':
		            $this->SetStartinZone((int)$Value);
		            break;
		        default:
		            $this->SendDebug('Request Action', 'No Action defined: ' . $Ident, 0);
		            break;
		    }
	    }else{
            $this->SendDebug('Request Action', 'No Action defined: ' . $Ident, 0);
	    }
    }
		
#================================================================================================
    public function SetZoneDistance(int $zone, int $value) 
#================================================================================================
	{
		$mz = array();
		$this->SetValue('WRX_Mowingzone_'.$zone, $value);
		for($i = 0; $i < 4; $i++){
			if($this->GetValue('WRX_Mowingzone_'.$i) != 0)$mz[] = $this->GetValue('WRX_Mowingzone_'.$i);
		}
		sort($mz);
		$num = count($mz);
		for($i = $num; $i < 4; $i++){
			$mz[] = 0;
		}
		$obj["mz"] = $mz;		
		$this->sendJson(json_encode($obj));
		IPS_Sleep(2000);

		if($value == 0){
			$zone = array(0,0,0,0);
			if($num>0){
				for($i = 0; $i <$num; $i++){
					$zone[$i] = floor(10/$num);
				}
				$zone[0] += 10%$num;
			}
			$mzv = array();
			$i = 0;
			if(array_sum($zone) == 10 || array_sum($zone) == 0)do{
				if(isset(array_count_values($mzv)[$i])){
				    if(array_count_values($mzv)[$i]<$zone[$i])$mzv[] = $i;
				}elseif($zone[$i]>0){
				    $mzv[] = $i;
				}
				if(array_sum($zone) == 0)$mzv[] = 0;
				$i++;
				if($i == 4)$i -= 4;
			}while(count($mzv) < 10);
			unset($obj);
			$obj["mzv"] = $mzv;		
			$this->sendJson(json_encode($obj));
		}
    }
		
#================================================================================================
    public function SetShareinZone(int $zone, int $value) 
#================================================================================================
	{
		$mzv = array();
		if($this->GetValue('WRX_Mowingzone_'.$zone) == 0){
			$this->SetValue('WRX_ShareinZone_'.$zone, 0);
			return;
		}
		$this->SetValue('WRX_ShareinZone_'.$zone, $value);
		$zone = array();
		for($i = 0; $i < 4; $i++){
			$zone[$i] = $this->GetValue('WRX_ShareinZone_'.$i)/10;
		}
		$zone[0] += 10 - array_sum($zone);
		$mzv = array();
		$i = 0;
		if(array_sum($zone) == 10 || array_sum($zone) == 0)do{
			if(isset(array_count_values($mzv)[$i])){
			    if(array_count_values($mzv)[$i]<$zone[$i])$mzv[] = $i;
			}elseif($zone[$i]>0){
			    $mzv[] = $i;
			}
			if(array_sum($zone) == 0)$mzv[] = 0;
			$i++;
			if($i == 4)$i -= 4;
		}while(count($mzv) < 10);
		$obj["mzv"] = $mzv;		
		$this->sendJson(json_encode($obj));
    }
		
#================================================================================================
    public function SetStartinZone(int $value) 
#================================================================================================
	{
		$value -= 1;
		$mzv = json_decode($this->ReadAttributeString("mzv"));
		$next = $this->ReadAttributeInteger("lz");
		$old = $mzv[$next];
		$pos = array_search($value, $mzv);
		$mzv[$pos] = $old;
		$mzv[$next] = $value;

		$obj["mzv"] = $mzv;		
		$this->sendJson(json_encode($obj));
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
