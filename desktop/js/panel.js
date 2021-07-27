
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

 setTimeout(function () {
  positionEqLogic();
  $('.div_displayEquipement').disableSelection();
  $( "input").click(function() { $(this).focus(); });
  $( "textarea").click(function() { $(this).focus(); });
  $('.div_displayEquipement').each(function(){
    var container = $(this).packery({
      itemSelector: ".eqLogic-widget",
      gutter : 2,
  });
    var itemElems =  container.find('.eqLogic-widget');
    itemElems.draggable();
    container.packery( 'bindUIDraggableEvents', itemElems );
});
  $('.div_displayEquipement .eqLogic-widget').draggable('disable');
  $('#bt_editDashboardWidgetOrder').on('click',function(){
    if($(this).attr('data-mode') == 1){
      $.hideAlert();
      $(this).attr('data-mode',0);
      editWidgetMode(0);
      $(this).css('color','black');
  }else{
      $('#div_alert').showAlert({message: "{{Vous êtes en mode édition vous pouvez redimensionner les widgets}}", level: 'info'});
      $(this).attr('data-mode',1);
      editWidgetMode(1);
      $(this).css('color','rgb(46, 176, 75)');
  }
});
}, 1);
