<?php

declare(strict_types=1);

class MQTTworxGateway extends IPSModule
{

#================================================================================================
    public function Create()
#================================================================================================
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Topic', "");

		$this->ConnectParent('{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}');


		$this->RegisterTimer('ServerStatus', 90000, 'IPS_RequestAction($_IPS["TARGET"], "ServerStatus",0);');

		if (!IPS_VariableProfileExists('Online.WRX')) {
			IPS_CreateVariableProfile('Online.WRX', 0);
			IPS_SetVariableProfileAssociation("Online.WRX", true, 'online', "", -1);
			IPS_SetVariableProfileAssociation("Online.WRX", false, 'offline', "", -1);
		};
		$this->RegisterVariableBoolean('WRX_bridge', 'MQTT-Bridge', 'Online.WRX', 0);
		$this->RegisterVariableBoolean('WRX_mower', $this->Translate('Mower'), 'Online.WRX', 0);

    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
			
#		Filter setzen
		$this->SetReceiveDataFilter(".*\"Topic\":\"".$this->ReadPropertyString("Topic")."/.*");

    }

#================================================================================================
	public function ReceiveData($JSONString)
#================================================================================================
    {
		$this->SendDebug("Received", $JSONString, 0);
		$data = json_decode($JSONString);

#----------------------------------------------------------------
#		Daten weiterleiten
#----------------------------------------------------------------

	    $Payload = json_decode($data->Payload);
		$this->SetTimerInterval("ServerStatus", 90000);
		$this->SetValue('WRX_bridge', true);

		if (property_exists($Payload, 'online')) {
			$this->SetStatus(($Payload->online)?102:104);
			$this->SetValue('WRX_mower', $Payload->online);
		}

		if (property_exists($Payload, 'dat')) {
			if (property_exists($Payload->dat, 'fw')){
				$this->sendDataToDevices('Infos', 'fw', $Payload->dat->fw);
			}
			if (property_exists($Payload->dat, 'mac')){
				$this->sendDataToDevices('Infos', 'mac', $Payload->dat->mac);
			}
			if (property_exists($Payload->dat, 'bt')){
				if (property_exists($Payload->dat->bt, 't')){
					$this->sendDataToDevices('Infos', 'bt.t', $Payload->dat->bt->t);
				}
				if (property_exists($Payload->dat->bt, 'v')){
					$this->sendDataToDevices('Infos', 'bt.v', $Payload->dat->bt->v);
				}
				if (property_exists($Payload->dat->bt, 'p')){
					$this->sendDataToDevices('Infos', 'bt.p', $Payload->dat->bt->p);
				}
				if (property_exists($Payload->dat->bt, 'nr')){
					$this->sendDataToDevices('Infos', 'bt.nr', $Payload->dat->bt->nr);
				}
				if (property_exists($Payload->dat->bt, 'c')){
					$this->sendDataToDevices('Infos', 'bt.c', $Payload->dat->bt->c);
				}
			}
			if (property_exists($Payload->dat, 'dmp')){
				$this->sendDataToDevices('Infos', 'dmp', $Payload->dat->dmp);
			}
			if (property_exists($Payload->dat, 'st')){
				if (property_exists($Payload->dat->st, 'b')){
					$this->sendDataToDevices('Infos', 'st.b', $Payload->dat->st->b);
				}
				if (property_exists($Payload->dat->st, 'wt')){
					$this->sendDataToDevices('Infos', 'st.wt', $Payload->dat->st->wt);
				}
				if (property_exists($Payload->dat->st, 'd')){
					$this->sendDataToDevices('Infos', 'st.d', $Payload->dat->st->d);
				}
			}
			if (property_exists($Payload->dat, 'le')){
				$this->sendDataToDevices('Infos', 'le', $Payload->dat->le);
			}
			if (property_exists($Payload->dat, 'ls')){
				$this->sendDataToDevices('Infos', 'ls', $Payload->dat->ls);
			}
			if (property_exists($Payload->dat, 'rsi')){
				$this->sendDataToDevices('Infos', 'rsi', $Payload->dat->rsi);
			}
			if (property_exists($Payload->dat, 'lk')){
				$this->sendDataToDevices('Infos', 'lk', $Payload->dat->lk);
			}
			if (property_exists($Payload->dat, 'lz')){
				$this->sendDataToDevices('Zones', 'lz', $Payload->dat->lz);
			}
			if (property_exists($Payload->dat, 'rain')){
				$this->sendDataToDevices('Infos', 'rain', $Payload->dat->rain);
			}
		}

		if (property_exists($Payload, 'cfg')) {
			if (property_exists($Payload->cfg, 'cmd')){
				$this->sendDataToDevices('Scheduler', 'cmd', $Payload->cfg->cmd);
			}
			if (property_exists($Payload->cfg, 'sc')){
				if (property_exists($Payload->cfg->sc, 'd') && property_exists($Payload->cfg->sc, 'dd')){
	 				$this->sendDataToDevices('Scheduler', 'dd', array($Payload->cfg->sc->d,  $Payload->cfg->sc->dd));
				}elseif (property_exists($Payload->cfg->sc, 'd')){
					$this->sendDataToDevices('Scheduler', 'd', array($Payload->cfg->sc->d));
				}
				if (property_exists($Payload->cfg->sc, 'p')){
					$this->sendDataToDevices('Scheduler', 'p', $Payload->cfg->sc->p);
				}
				if (property_exists($Payload->cfg->sc, 'm')){
					$this->sendDataToDevices('Scheduler', 'm', $Payload->cfg->sc->m);
				}
				if (property_exists($Payload->cfg->sc, 'distm')){
					$this->sendDataToDevices('Scheduler', 'distm', $Payload->cfg->sc->distm);
				}
			}
			if (property_exists($Payload->cfg, 'rd')){
				$this->sendDataToDevices('Scheduler', 'rd', $Payload->cfg->rd);
			}
			if (property_exists($Payload->cfg, 'mz')){
				$this->sendDataToDevices('Zones', 'mz', $Payload->cfg->mz);
			}
			if (property_exists($Payload->cfg, 'mzv')){
				$this->sendDataToDevices('Zones', 'mzv', $Payload->cfg->mzv);
			}
			if (property_exists($Payload->cfg, 'tq')){
				$this->sendDataToDevices('Settings', 'tq', $Payload->cfg->tq);
			}
			if (property_exists($Payload->cfg, 'al')){
				if (property_exists($Payload->cfg->al, 'lvl') && property_exists($Payload->cfg->al, 't')){
					$this->sendDataToDevices('Settings', 'lvl', $Payload->cfg->al->lvl);
					$this->sendDataToDevices('Settings', 't', $Payload->cfg->al->t);
				}
			}
		}

		if (property_exists($Payload, 'al')) {
			if (property_exists($Payload->cfg, 'cmd')){
				$this->sendDataToDevices('Scheduler', 'cmd', $Payload->cfg->cmd);
			}
			if (property_exists($Payload->cfg, 'sc')){
				if (property_exists($Payload->cfg->sc, 'd') && property_exists($Payload->cfg->sc, 'dd')){
	 				$this->sendDataToDevices('Scheduler', 'dd', array($Payload->cfg->sc->d,  $Payload->cfg->sc->dd));
				}elseif (property_exists($Payload->cfg->sc, 'd')){
					$this->sendDataToDevices('Scheduler', 'd', array($Payload->cfg->sc->d));
				}
				if (property_exists($Payload->cfg->sc, 'p')){
					$this->sendDataToDevices('Scheduler', 'p', $Payload->cfg->sc->p);
				}
				if (property_exists($Payload->cfg->sc, 'm')){
					$this->sendDataToDevices('Scheduler', 'm', $Payload->cfg->sc->m);
				}
				if (property_exists($Payload->cfg->sc, 'distm')){
					$this->sendDataToDevices('Scheduler', 'distm', $Payload->cfg->sc->distm);
				}
			}
			if (property_exists($Payload->cfg, 'rd')){
				$this->sendDataToDevices('Scheduler', 'rd', $Payload->cfg->rd);
			}
			if (property_exists($Payload->cfg, 'mz')){
				$this->sendDataToDevices('Zones', 'mz', $Payload->cfg->mz);
			}
			if (property_exists($Payload->cfg, 'mzv')){
				$this->sendDataToDevices('Zones', 'mzv', $Payload->cfg->mzv);
			}
			if (property_exists($Payload->cfg, 'tq')){
				$this->sendDataToDevices('Settings', 'tq', $Payload->cfg->tq);
			}
		}
	}

#================================================================================================
	public function ForwardData($JSONString) {
#================================================================================================
	 
		// Empfangene Daten von der Device Instanz
		$this->SendDebug("ForwardData", $JSONString, 0);
		$data = json_decode($JSONString);
		if($this->GetStatus() == 201){
			$this->SendDebug("ForwardData", $this->Translate("Forwarding rejected. Mower is offline"), 0);
			$this->LogMessage('[ID: '.$this->InstanceID.'] '.$this->Translate("Forwarding rejected. Mower is offline")." - ".$data->Payload, KL_ERROR);
		}elseif($this->GetStatus() == 202){
			$this->SendDebug("ForwardData", $this->Translate("Forwarding rejected. MQTT-Landroid-Bridge is offline"), 0);
			$this->LogMessage('[ID: '.$this->InstanceID.'] '.$this->Translate("Forwarding rejected. MQTT-Landroid-Bridge is offline")." - ".$data->Payload, KL_ERROR);
		}else{
			$this->sendMQTT($data->Topic, $data->Payload, 0);
		}
	}

#================================================================================================
	protected function sendDataToDevices($Device, $Topic, $Payload)
#================================================================================================
	{
	    $Data['DataID'] = '{FFC643B7-C985-1896-D537-7436CA1F11B2}';
	    $Data['Device'] = $Device;
	    $Data['Topic'] = $Topic;
	    $Data['Payload'] = json_encode($Payload);

	    $DataJSON = json_encode($Data, JSON_UNESCAPED_SLASHES);
		$this->SendDebug("Sended", $DataJSON, 0);
	    $this->SendDataToChildren($DataJSON);
    }

#================================================================================================
	protected function sendMQTT($Topic, $Payload)
#================================================================================================
	{
	    $Data['DataID'] = '{043EA491-0325-4ADD-8FC2-A30C8EEB4D3F}';
	    $Data['PacketType'] = 3;
	    $Data['QualityOfService'] = 0;
	    $Data['Retain'] = false;
	    $Data['Topic'] = $this->ReadPropertyString("Topic").$Topic;
	    $Data['Payload'] = $Payload;

	    $DataJSON = json_encode($Data, JSON_UNESCAPED_SLASHES);
		$this->SendDebug("Sended", $DataJSON, 0);
	    $this->SendDataToParent($DataJSON);
    }

#================================================================================================
    public function RequestAction($Ident, $Value)
#================================================================================================
	{
		switch ($Ident) {
	        case 'ServerStatus':
				$this->SetStatus(202);
				$this->SetValue('WRX_bridge', false);
				$this->SetValue('WRX_mower', false);
				break;
	        default:
				$this->SendDebug('Request Action', 'Action "'.$Ident.'" not defined: ', 0);
				break;
		}
	}
}
