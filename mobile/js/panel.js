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

function initQrcodemanagerQrcodemanager() {
	 getQrcodemanagerState()
}

function getQrcodemanagerState(){
	$.ajax({
        type: "POST",
        url: "plugins/qrcodemanager/core/ajax/qrcodemanager.ajax.php",
        data: {
            action: "getQrcodemanager",
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
		for (qrcodemanager in data.result.qrcode) {
			var qrcode = data.result.qrcode[qrcodemanager];
			table += '<option value="' + qrcode['id'] + '">' + qrcode['name'] + '</option>';
		}
		$("#select select").empty().append(table);
		$("#select select").trigger('create');
        }
});
}

$("#select").change(function(event) {
	var text = 'plugins/qrcodemanager/data/' + $("#select").val() + '.png';
	document.icon_visu.src=text;
});
