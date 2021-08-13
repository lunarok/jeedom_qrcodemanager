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
	public static function dependancy_info() {
		$return = array();
		$return['progress_file'] = jeedom::getTmpFolder('qrcodemanager') . '/dependancy';
		$cmd = "pip3 list | grep pyzbar";
		exec($cmd, $output, $return_var);
		$cmd = "pip3 list | grep pillow";
		exec($cmd, $output2, $return_var);
		$return['state'] = 'nok';
		if (array_key_exists(0,$output) && array_key_exists(0,$output2)) {
		    if ($output[0] != "" && $output2[0] != "") {
			$return['state'] = 'ok';
		    }
		}
		return $return;
	}

	public static function dependancy_install() {
		$dep_info = self::dependancy_info();
		log::remove(__CLASS__ . '_dep');
		$resource_path = realpath(dirname(__FILE__) . '/../../resources');
		if ($dep_info['state'] != 'ok') {

			passthru('/bin/bash ' . $resource_path . '/install_apt.sh ' . jeedom::getTmpFolder('qrcodemanager') . '/dependancy > ' . log::getPathToLog(__CLASS__ . '_dep') . ' 2>&1 &');
		} else {
			passthru('/bin/bash ' . $resource_path . '/install_apt_force.sh ' . jeedom::getTmpFolder('qrcodemanager') . '/dependancy > ' . log::getPathToLog(__CLASS__ . '_dep') . ' 2>&1 &');
		}
	}

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
			$cmd = 'qr ' . $this->getConfiguration('registeredContent') . ' > ' . realpath(dirname(__FILE__) . '/../../data') . $image . '.png';
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

	public function scanImage() {
		$cmd = 'zbarimg -S*.enable /tmp/' . $this->getId() . '.png';
		log::add('qrcodemanager', 'debug', 'scanImage : ' . $cmd);
		$result = exec($cmd);
		log::add('qrcodemanager', 'debug', 'result : ' . $result);
	}

}

class qrcodemanagerCmd extends cmd {

}
?>
