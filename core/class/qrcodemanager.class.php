<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class qrcodemanager extends eqLogic {
	public function preSave() {
		log::add('qrcodemanager', 'debug', 'preSave');
		$this->checkImage();
	}

	public function checkImage() {
		log::add('qrcodemanager', 'debug', 'checkImage');
		$generate = 0;
		//check if type and content have change
		if ($this->getConfiguration('type') != $this->getConfiguration('registeredType')) {
			$this->setConfiguration('registeredType',$this->getConfiguration('type'));
			$generate = 1;
		}
		if ($this->getConfiguration('content') != $this->getConfiguration('registeredContent')) {
			$this->setConfiguration('registeredContent',$this->getConfiguration('content'));
			$generate = 1;
		}
		//if change, generate image
		if ($generate == 1) {
			$this->generateImage();
			$this->setConfiguration('imageExist',"1");
		}
	}

	public function generateImage() {
		log::add('qrcodemanager', 'debug', 'generateImage');
		$image = '/' . $this->getId();
		if ($this->getConfiguration('registeredType') == 'qrcode') {
			$cmd = "qrencode '" . $this->getConfiguration('registeredContent') . "' -l H -o " . realpath(dirname(__FILE__) . '/../../data') . $image . ".png";
			//$cmd = 'segno -o=' . realpath(dirname(__FILE__) . '/../../data') . $image . '.png --title="' . $this->getConfiguration('registeredContent') . '" "' . $this->getConfiguration('registeredContent') . '"';
		} else {
			$cmd = 'python-barcode create -t png "' . $this->getConfiguration('registeredContent') . '" ' . realpath(dirname(__FILE__) . '/../../data') . $image . ' -b ' . $this->getConfiguration('registeredType');
			//isbn13 : 12 digits start by 978 or 979
			//isbn10 : 9 digits
			//ean13 : 12 digits
			//ean8 : 7 digits
			//pzn 6 digits
			//upca 11 digits
		}
		log::add('qrcodemanager', 'debug', 'generateImage : ' . $cmd);
		$result = exec($cmd);
		//log::add('qrcodemanager', 'debug', 'result : ' . $result);
	}

	public function scanImage($_type) {
		$cmd = 'zbarimg -S*.enable ' . $_type;
		log::add('qrcodemanager', 'debug', 'scanImage : ' . $cmd);
		$result = shell_exec($cmd);
		log::add('qrcodemanager', 'debug', 'result : ' . $result);
		$array = explode(':',$result);
		$type = array_shift($array);
		$this->setConfiguration('type',strtolower(str_replace('-','',$type)));
		$this->setConfiguration('content',implode(":", $array));
		log::add('qrcodemanager', 'debug', 'type : ' . $array[0]);
		if ($array[0] == 'HC1') {
			//greenpass
			$cmd = "python3 " . realpath(dirname(__FILE__) . '/../../resources') . "/verify_ehc.py --no-verify '" . implode(":", $array) . "'";
			$result = shell_exec($cmd);
			log::add('qrcodemanager', 'debug', 'execute : ' . $cmd);
			log::add('qrcodemanager', 'debug', 'HC1 : ' . $result);
			$this->setConfiguration('hc1',1);
			$this->setConfiguration('hc1Content',$result);
		} else {
			$this->setConfiguration('hc1',0);
		}
		$this->save();
		event::add('qrcodemanager::includeDevice',
            array(
                'state' => 1
            )
        );
	}

}

class qrcodemanagerCmd extends cmd {

}
?>
