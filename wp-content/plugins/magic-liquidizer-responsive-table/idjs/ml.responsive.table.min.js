
/*
 * Plugin Name: Magic Liquidizer Responsive Table
 * Plugin URI: http://www.innovedesigns.com/wordpress/magic-liquidizer-responsive-table-rwd-you-must-have-wp-plugin/
 * Author: Elvin Deza
 * Description: A simple and lightweight plugin that converts HTML table into responsive. After activation, go to Dashboard > Magic Liquidizer Lite > Table.
 * Version: 2.0.0
 * Author URI: http://innovedesigns.com/author/esstat17
 */
 
(function($){$.fn.responsiveTable=function(options){var settings=$.extend({headerSelector:'thead td, thead th, tr th',bodyRowSelector:'tbody tr, tr',rowElement:'<dl></dl>',columnTitleElement:'<dt></dt>',columnValueElement:'<dd></dd>',enable:!0},options);var tableHTML='',display='',titles=new Array(),index=0,jSum=0,row='',dt='',dd='';return this.each(function(o,e){tableHTML=$(this);display=$('<div class="ml-responsive-table ml-responsive-table-'+o+'" />');tableHTML.find(settings.headerSelector).each(function(i,e){titles[i]=$(e).html()});tableHTML.find(settings.bodyRowSelector).each(function(){$(this).children('td').each(function(j){jSum=jSum+j})});tableHTML.find(settings.bodyRowSelector).each(function(i,e){row=$(settings.rowElement);row.addClass('ml-grid ml-clearfix ml-row-'+i);$(this).children('td').each(function(j,d){index=jSum==0?i:j,dt=$(settings.columnTitleElement),dd=$(settings.columnValueElement);dt.addClass('ml-title ml-table');dt.html(titles[index]);dd.addClass('ml-value ml-table');dd.html($(this).html());if($.trim($(this).html())==''){dd.addClass('ml-empty');dt.addClass('ml-empty')}
row.append(dt).append(dd)});display.append(row)});if(settings.enable){tableHTML.hide();tableHTML.after(display)}else{$(".ml-responsive-table").remove();tableHTML.show()}})}})(jQuery);(function($){$.fn.MagicLiquidizerTable=function(options){var settings=$.extend({table:'1',breakpoint:'720',whichelement:'table',},options);return this.each(function(){function responsiveTableFn(){var viewwidth=$(window).width();if(viewwidth<settings.breakpoint){if(!$('html.rwd-table').length>0){$('html').addClass('rwd-table')}
if(settings.table=='1'&&!$('.ml-responsive-table').length>0){$(settings.whichelement).responsiveTable({enable:!0})}}else{if($('html.rwd-table').length>0){$('html').removeClass('rwd-table')}
if(settings.table=='1'){$(settings.whichelement).responsiveTable({enable:!1})}}}
$(window).resize(responsiveTableFn).ready(responsiveTableFn)})}}(jQuery))