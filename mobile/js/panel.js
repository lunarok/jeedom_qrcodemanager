/* This file is part of Plugin openzwave for jeedom.
 *
 * Plugin openzwave for jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Plugin openzwave for jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Plugin openzwave for jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

function initKlf200Klf200() {
	 getKlf200State()
}

function getKlf200State(){
	$.ajax({
        type: "POST",
        url: "plugins/klf200/core/ajax/klf200.ajax.php",
        data: {
            action: "getKlf200",
            type: "mobile",
        },
        dataType: 'json',
		global : false,
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
		},
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_inclusionAlert').showAlert({message: data.result, level: 'danger'});
            return;
		}
		var table = '';
		for (klf200 in data.result.shutters) {
			var shutter = data.result.shutters[klf200];
			table += '<tr><td>' +  shutter['name'] +' <br/> '+ shutter['position'] +' <br/></td>';
			table += '<td> <a class="bt_klf200Action ui-btn ui-mini ui-btn-inline ui-btn-raised clr-primary" data-cmd="'+shutter['open']+'"><i class="fas fa-arrow-up"></i></a>';
			table += ' <a class="bt_klf200Action ui-btn ui-mini ui-btn-inline ui-btn-raised clr-primary" data-cmd="'+shutter['close']+'"><i class="fas fa-arrow-down"></i></a>';
      table += ' <a class="bt_klf200Action ui-btn ui-mini ui-btn-inline ui-btn-raised clr-primary" data-cmd="'+shutter['stop']+'"><i class="fas fa-stop"></i></a></td>';
			table += '<td>' + shutter['cmdhtml'] + '</td>';
			table += '</tr>';
		}
		$("#table_klf200 tbody").empty().append(table);
		$("#table_klf200 tbody").trigger('create');
        }
});
}

 $('#table_klf200 tbody').on('click','.bt_klf200Action',function(){
       jeedom.cmd.execute({id: $(this).data('cmd')});
       getKlf200State();
   })

  $('#table_klf200 tbody').on('click','.bt_positionshutterAction',function(){
       jeedom.cmd.execute({id: $(this).data('cmd'), value: {slider: $(this).data('value')}});
       getKlf200State();
   })

setInterval(function() {

getKlf200State();

}, 5000);
