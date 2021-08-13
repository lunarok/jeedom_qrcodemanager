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

if (!isConnect('admin')) {
  throw new Exception('401 - Accès non autorisé');
}
$plugin = plugin::byId('qrcodemanager');
$eqLogics = qrcodemanager::byType('qrcodemanager');
?>

<center>
  <select id="select">
    <?php
    foreach ($eqLogics as $eqLogic) {
      echo '<option value="' . $eqLogic->getId() . '">' . $eqLogic->getHumanName() . '</option>';
    }
    ?>
  </select>
  
  <br>

<img name="icon_visu" src="" width="400" height="400"/>

  <br>

<span id="content"></span>

</center>

<?php include_file('desktop', 'panel', 'js', 'qrcodemanager');?>
