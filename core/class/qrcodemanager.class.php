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
		$this->checkImage();
	}

	public function checkImage() {
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
		if ($this->getConfiguration('registeredType') == 'QRCODE') {
			$script = '/qrcodde.py';
		} else {
			$script = '/barcode-' . $this->getConfiguration('registeredType') . '.py';
		}
		$image = '/' . $this->getId() . '.png';
		$cmd = realpath(dirname(__FILE__) . '/../../resources') . $script . ' ' . $this->getConfiguration('registeredContent') . ' ' . realpath(dirname(__FILE__) . '/../../data') . $image;
		log::add('qrcodemanager', 'debug', 'generateImage : ' . $cmd);
		$result = exec($cmd);
	}

	public function scanImage() {
		if ($this->getId() == 'QRCODE') {
			$script = '/qrcodde.py';
		} else {
			$script = '/barcode-' . $this->getConfiguration('registeredType') . '.py';
		}
		$image = '/' . $this->getId() . '.png';
		$cmd = realpath(dirname(__FILE__) . '/../../resources') . '/pyzbar.py /tmp/' . $this->getId() . '.png';
		log::add('qrcodemanager', 'debug', 'scanImage : ' . $cmd);
		$result = exec($cmd);
		log::add('qrcodemanager', 'debug', 'result : ' . $result);
	}

}

class qrcodemanagerCmd extends cmd {

}
?>
