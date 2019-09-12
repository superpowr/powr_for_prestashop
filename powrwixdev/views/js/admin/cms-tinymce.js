/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$(document).ready(function() {
  	tinySetup({
		editor_selector :"autoload_rte",
		setup : function(ed) {			
	      	ed.on('loadContent', function(ed, e) {
	      		if (typeof tinymce.activeEditor != 'undefined') {
	      			handleCounterTiny(tinymce.activeEditor.id);	
	      		}	        	
	      	});

			ed.on('change', function(ed, e) {
				tinyMCE.triggerSave();
	        	if (typeof tinymce.activeEditor != 'undefined') {
	      			handleCounterTiny(tinymce.activeEditor.id);	
	      		}
			});

			ed.on('blur', function(ed) {
				tinyMCE.triggerSave();
			});
		}
	});
});

function handleCounterTiny(id) {
    let textarea = $('#'+id);
    let counter = textarea.attr('counter');
    let counter_type = textarea.attr('counter_type');
    let max = tinyMCE.activeEditor.getBody().textContent.length;

    textarea.removeClass('autoload_rte');

    setTimeout(5000, function() {
    	textarea.addClass('autoload_rte');
    });
    
    textarea.parent().find('span.currentLength').text(max);
    if ('recommended' !== counter_type && max > counter) {
      textarea.parent().find('span.maxLength').addClass('text-danger');
    } else {
      textarea.parent().find('span.maxLength').removeClass('text-danger');
    }
}
