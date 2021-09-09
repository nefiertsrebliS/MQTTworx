<?php

declare(strict_types=1);

class MQTTworxSettings extends IPSModule
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
		$this->SetReceiveDataFilter(".*\"Device\":\"Settings\".*");

#		Status holen
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
			case "tq":
				if (!IPS_VariableProfileExists('Torque.WRX')) {
					IPS_CreateVariableProfile('Torque.WRX', 1);
					IPS_SetVariableProfileIcon('Torque.WRX', 'Intensity');
					IPS_SetVariableProfileText('Torque.WRX', '', '.%');
					IPS_SetVariableProfileValues('Torque.WRX', -50, 50, 10);
				}
				$this->RegisterVariableInteger('WRX_Torque', $this->Translate('Torque Adjustment'), 'Torque.WRX', 10);
				$this->SetValue('WRX_Torque', $Payload);
				$this->EnableAction('WRX_Torque');
				break;

			case "lvl":
				$this->RegisterVariableBoolean('WRX_Autolock', $this->Translate('Autolock'), 'Switch', false);
				$this->EnableAction('WRX_Autolock');
				$this->SetValue('WRX_Autolock', $Payload);
				break;

			case "t":
				if (!IPS_VariableProfileExists('LockDelay.WRX')) {
					IPS_CreateVariableProfile('LockDelay.WRX', 1);
					IPS_SetVariableProfileIcon('LockDelay.WRX', 'Clock');
					IPS_SetVariableProfileText('LockDelay.WRX', '', ' s');
					IPS_SetVariableProfileValues('LockDelay.WRX', 0, 600, 60);
				}
				$this->RegisterVariableInteger('WRX_AutolockDelay', $this->Translate('Autolock-Delay'), 'LockDelay.WRX', 0);
				$this->EnableAction('WRX_AutolockDelay');
				$this->SetValue('WRX_AutolockDelay', $Payload);
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
        switch ($Ident) {
	        case 'WRX_Torque':
	            $this->SetTorque((int)$Value);
	            break;
			case 'WRX_Autolock':
				$this->SetAutolock($Value);
				break;
			case 'WRX_AutolockDelay':
				$this->SetAutolockDelay($Value);
				break;
			default:
                $this->SendDebug('Request Action', 'No Action defined: ' . $Ident, 0);
                break;
        }
    }
		
#================================================================================================
public function SetTorque(int $Value) 
#================================================================================================
	{
		$Value = round($Value, -1);
		if($Value < -50)$Value = -50;
		if($Value > 50)$Value = 50;
		$this->sendJson('{ "tq": '.$Value.' }');
    }
		
#================================================================================================
public function SetAutolock(bool $Value) 
#================================================================================================
	{
		$this->sendJson('{ "al": { "lvl": '.($Value?1:0).'} }');
    }
		
#================================================================================================
public function SetAutolockDelay(int $Value) 
#================================================================================================
	{
		$Value = round($Value/60)*60;
		if($Value < 0)$Value = 0;
		if($Value > 600)$Value = 600;
		$this->sendJson('{ "al": { "t": '.$Value.'} }');
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
