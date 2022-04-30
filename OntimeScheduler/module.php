<?php

declare(strict_types=1);

class MQTTworxOTS extends IPSModule
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
		$this->SetBuffer('lastset', 0);
			
#		Filter setzen
		$this->SetReceiveDataFilter(".*\"Device\":\"OTS\".*");

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
			case "ots":
				if(time() - $this->GetBuffer('lastset') < 60)break;
				if(!IPS_VariableProfileExists('StartOTS.WRX')) {
					IPS_CreateVariableProfile('StartOTS.WRX', 1);
					IPS_SetVariableProfileIcon('StartOTS.WRX', 'Power');
					IPS_SetVariableProfileValues('StartOTS.WRX', 0, 0, 0);
					IPS_SetVariableProfileAssociation('StartOTS.WRX', 0, $this->Translate('Start'), '',0x00FF00);
				}
				$this->RegisterVariableInteger('WRX_StartOTS', $this->Translate('Task'), 'StartOTS.WRX', 10);
				$this->EnableAction('WRX_StartOTS');

				if(!IPS_VariableProfileExists('Mowduration.WRX')) {
					IPS_CreateVariableProfile('Mowduration.WRX', 1);
					IPS_SetVariableProfileIcon('Mowduration.WRX', 'Clock');
					IPS_SetVariableProfileText('Mowduration.WRX', '', ' min');
					IPS_SetVariableProfileValues('Mowduration.WRX', 0, 480, 30);
				}
				$this->RegisterVariableInteger('WRX_Mowduration', $this->Translate('Mowduration'), 'Mowduration.WRX', 0);
				$this->EnableAction('WRX_Mowduration');
				$this->SetValue('WRX_Mowduration', $Payload->wtm);

				$this->RegisterVariableBoolean('WRX_Bordercut', $this->Translate('Bordercut'), 'Switch', false);
				$this->EnableAction('WRX_Bordercut');
				$this->SetValue('WRX_Bordercut', $Payload->bc);
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
	        case 'WRX_Mowduration':
			case 'WRX_Bordercut':
				$this->SetBuffer('lastset', time());
				$this->SetValue($Ident, $Value);
				break;
			case 'WRX_StartOTS':
				$bc = ($this->GetValue('WRX_Bordercut'))?1:0;
				$wtm = $this->GetValue('WRX_Mowduration');
				$this->SetBuffer('lastset', 0);
				$this->StartOTS($bc, $wtm);
				break;
			default:
                $this->SendDebug('Request Action', 'No Action defined: ' . $Ident, 0);
                break;
        }
    }
		
#================================================================================================
public function StartOTS(int $bc, int $wtm) 
#================================================================================================
	{
		$wtm = round($wtm / 30) * 30;
		if($wtm < 0)$wtm = 0;
		if($wtm > 480)$wtm = 480;
		$this->sendJson('{ "sc": { "ots": { "bc": '.$bc.', "wtm": '.$wtm.'} }}');
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
