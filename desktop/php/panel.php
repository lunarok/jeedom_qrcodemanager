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
$plugin = plugin::byId('klf200');
$eqLogics = klf200::byType('klf200');
?>

<table class="table table-condensed tablesorter" align="center">
  <thead>
    <tr>
      <th>{{Equipement}}</th>
      <th>{{ID}}</th>
      <th>{{Type}}</th>
      <th>{{Ouvrir}}</th>
      <th>{{Fermer}}</th>
      <th>{{Arrêter}}</th>
      <th>{{Pourcentage}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($eqLogics as $eqLogic) {
      $cmd_open = $eqLogic->getCmd(null, 'open');
      $cmd_close = $eqLogic->getCmd(null, 'close');
      $cmd_stop = $eqLogic->getCmd(null, 'stop');
      if ($eqLogic->getConfiguration('id') != '99') {
        $cmd_position = $eqLogic->getCmd(null, 'position');
        $position_value = $cmd_position->execCmd(null, 2);
        $position_id = $cmd_position->getId();
      } else {
        $position_value = 0;
        $position_id = 0;
      }
      $cmd_position_slider = $eqLogic->getCmd(null, 'position_slider');
      echo '<tr>';
      echo '<td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
      echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getId() . '</span></td>';
      echo '<td><span class="label label-info" style="font-size : 1em; cursor : default;">' . $eqLogic->getConfiguration('type') . '</span></td>';
      echo '<td><center><span class="cmd cmd-widget noRefresh" data-type="action" data-subtype="other" data-cmd_id="' . $cmd_open->getId() . '"><a class="btn btn-sm btn-default action"><i class="fas fa-arrow-up"></i></a></span></center></td>';
      echo '<td><center><span class="cmd cmd-widget noRefresh" data-type="action" data-subtype="other" data-cmd_id="' . $cmd_close->getId() . '"><a class="btn btn-sm btn-default action"><i class="fas fa-arrow-down"></i></a></span></center></td>';
      echo '<td><center><span class="cmd cmd-widget noRefresh" data-type="action" data-subtype="other" data-cmd_id="' . $cmd_stop->getId() . '"><a class="btn btn-sm btn-default action"><i class="fas fa-stop"></i></a></span></center></td>';
      echo '<td><center><span class="label label-info positiontext'.$id.'" style="font-size : 0.8em;cursor:default">' . $position_value . '</span></br></br>
      <div class="position'.$cmd_position_slider->getId().'" style="width: 80px;" value="'.$position_value.'" ></div></center></br>';
      echo '</tr>';
      echo '<script>
      $(".position'.$cmd_position_slider->getId().'").slider({
        min: 0,
        max: 100,
        range: "min",
        value: ("' . $position_value . '" == "") ? 0 : parseInt("' . $position_value . '")
      });
      $(".position'.$cmd_position_slider->getId().'").on("slidestop", function (event,ui) {
        jeedom.cmd.execute({id: "'.$cmd_position_slider->getId().'", value: {slider: ui.value}});
      });
      $(".cmd[data-cmd_id=' . $cmd_open->getId() . '] .action").off().on("click", function () {
          jeedom.cmd.execute({id: ' . $cmd_open->getId() . ', notify: false});
      });
      $(".cmd[data-cmd_id=' . $cmd_close->getId() . '] .action").off().on("click", function () {
          jeedom.cmd.execute({id: ' . $cmd_close->getId() . ', notify: false});
      });
      $(".cmd[data-cmd_id=' . $cmd_stop->getId() . '] .action").off().on("click", function () {
          jeedom.cmd.execute({id: ' . $cmd_stop->getId() . ', notify: false});
      });
      </script>';
    }
    ?>
  </tbody>
</table>


<?php include_file('desktop', 'panel', 'js', 'klf200');?>
