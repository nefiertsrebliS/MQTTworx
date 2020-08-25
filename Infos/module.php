<?php

declare(strict_types=1);

class MQTTworxInfo extends IPSModule
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
		$this->SetReceiveDataFilter(".*\"Device\":\"Infos\".*");

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
			case "bt.t":
				$this->RegisterVariableFloat('WRX_bt_t', $this->Translate('Battery-Temperature'), '~Temperature', 16);
				SetValue($this->GetIDForIdent('WRX_bt_t'), $Payload);
				break;
			case "bt.v":
				$this->RegisterVariableFloat('WRX_bt_v', $this->Translate('Battery-Voltage'), '~Volt', 14);
				SetValue($this->GetIDForIdent('WRX_bt_v'), $Payload);
				break;
			case "bt.p":
				$this->RegisterVariableInteger('WRX_bt_p', $this->Translate('Battery-Load'), '~Intensity.100', 12);
				SetValue($this->GetIDForIdent('WRX_bt_p'), $Payload);
				break;
			case "bt.nr":
				$this->RegisterVariableFloat('WRX_bt_nr', $this->Translate('Battery-Loadcycles'), '', 18);
				SetValue($this->GetIDForIdent('WRX_bt_nr'), $Payload);
				break;
			case "bt.c":
				$this->RegisterVariableBoolean('WRX_bt_c', $this->Translate('Battery-Charging'), '~Switch', 10);
				SetValue($this->GetIDForIdent('WRX_bt_c'), $Payload);
				break;
			case "le":
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
				$this->RegisterVariableInteger('WRX_Error', $this->Translate('Error'), 'Error.WRX', 2);
				SetValue($this->GetIDForIdent('WRX_Error'), $Payload);
				break;
			case "ls":
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
				$this->RegisterVariableInteger('WRX_Status', $this->Translate('Status'), 'Status.WRX', 1);
				SetValue($this->GetIDForIdent('WRX_Status'), $Payload);
				break;
			case "st.b":
				if (!IPS_VariableProfileExists('min.WRX')) {
					IPS_CreateVariableProfile('min.WRX', 1);
					IPS_SetVariableProfileIcon('min.WRX', 'Clock');
					IPS_SetVariableProfileText('min.WRX', '', ' min');
				}
				$this->RegisterVariableInteger('WRX_st_b', $this->Translate('Mowing Time'), 'min.WRX', 100);
				SetValue($this->GetIDForIdent('WRX_st_b'), $Payload);
				break;
			case "st.wt":
				if (!IPS_VariableProfileExists('min.WRX')) {
					IPS_CreateVariableProfile('min.WRX', 1);
					IPS_SetVariableProfileIcon('min.WRX', 'Clock');
					IPS_SetVariableProfileText('min.WRX', '', ' min');
				}
				$this->RegisterVariableInteger('WRX_st_wt', $this->Translate('Working Time'), 'min.WRX', 110);
				SetValue($this->GetIDForIdent('WRX_st_wt'), $Payload);
				break;
			case "st.d":
				if (!IPS_VariableProfileExists('Distance.WRX')) {
					IPS_CreateVariableProfile('Distance.WRX', 1);
					IPS_SetVariableProfileIcon('Distance.WRX', 'Distance');
					IPS_SetVariableProfileText('Distance.WRX', '', ' m');
				}
				$this->RegisterVariableInteger('WRX_st_d', $this->Translate('Distance'), 'Distance.WRX', 120);
				SetValue($this->GetIDForIdent('WRX_st_d'), $Payload);
				break;
			case "dmp":
				if (!IPS_VariableProfileExists('Angle.WRX')) {
					IPS_CreateVariableProfile('Angle.WRX', 1);
					IPS_SetVariableProfileIcon('Angle.WRX', 'TurnLeft');
					IPS_SetVariableProfileText('Angle.WRX', '', ' °');
					IPS_SetVariableProfileValues('Angle.WRX', 0, 359, 0);
				}
				$this->RegisterVariableInteger('WRX_dmp_0', $this->Translate('Gradient'), 'Angle.WRX', 160);
				SetValue($this->GetIDForIdent('WRX_dmp_0'), round($Payload[0]));
				$this->RegisterVariableInteger('WRX_dmp_1', $this->Translate('Inclination'), 'Angle.WRX', 161);
				SetValue($this->GetIDForIdent('WRX_dmp_1'), round($Payload[1]));
				$this->RegisterVariableInteger('WRX_dmp_2', $this->Translate('Direction'), 'Angle.WRX', 162);
				SetValue($this->GetIDForIdent('WRX_dmp_2'), round($Payload[2]));
				break;
			case "rsi":
				if (!IPS_VariableProfileExists('Intensity.dB.WRX')) {
					IPS_CreateVariableProfile('Intensity.dB.WRX', 1);
					IPS_SetVariableProfileIcon('Intensity.dB.WRX', 'Intensity');
					IPS_SetVariableProfileText('Intensity.dB.WRX', '', ' dB');
				}
				$this->RegisterVariableInteger('WRX_RSSI', 'RSSI', 'Intensity.dB.WRX', 200);
				SetValue($this->GetIDForIdent('WRX_RSSI'), $Payload);
				break;
			case "lk":
				$this->RegisterVariableBoolean('WRX_Lock', $this->Translate('Lock'), '~Lock', 210);
				SetValue($this->GetIDForIdent('WRX_Lock'), $Payload);
				break;
			case "fw":
				$this->RegisterVariableFloat('WRX_Firmware', 'Firmware', '', 220);
				SetValue($this->GetIDForIdent('WRX_Firmware'), $Payload);
				break;
			case "mac":
				$this->RegisterVariableString('WRX_mac', $this->Translate('MAC-Adress'), '', 230);
				SetValue($this->GetIDForIdent('WRX_mac'), $Payload);
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
            default:
                $this->SendDebug('Request Action', 'No Action defined: ' . $Ident, 0);
                break;
        }
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
