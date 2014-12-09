<?php
/*
    Copyright (C) 2014 Masaya YAMAGUCHI

    The part of this program is based on the sample HTML file of mapjs:
    https://github.com/mindmup/mapjs/blob/master/test/index.html
    Copyright (c) 2013 Damjan Vujnovic, David de Florinier, Gojko Adzic

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
  Usage: 
    #teo_mapjs(height,save_flag){{
           (more than one empty line)
    }}

    height: the size of the map
    save_flag: yes  ... show save-button
               no   ... not show save-button
 */


define(START_MUP, '{"title": "Press Space or double-click to edit", "id": 1, "formatVersion": 2}');
define(DEFAULT_MAP_HEIGHT, '400pt');

function plugin_teo_mapjs_convert(){
  global $vars;
  static $number = array();

  $page = isset($vars['page']) ? $vars['page'] : '';
  if (!isset($number[$page])) $number[$page] = 0;
  if($number[$page] > 0){
    die_message("Error: Only one teo_mapjs plugin can be placed in one page.");
  }
  $number[$page]++;


  $options = func_num_args() ? func_get_args() : array();
  $n_args = func_num_args();
  
  if($n_args != 3){
    return 'Error(teo_mapjs): invalid options. #teo_mapjs(height,save_flag)';
  } else {
    // read from the tail of $options
    $contents = array_pop($options);
    if(!preg_match("/title/", $contents)){
      $contents = START_MUP;
    }
    $contents = preg_replace("/[\r\n]/", "", $contents);
    $contents = preg_replace('/\\\\/', "\\\\\\\\", $contents);
    //    die_message($contents);
    $save_flag = array_pop($options);
    if($save_flag == 'yes'){
      $html_save_button = '<input type="button" class="saveAsWiki" value="save"></input>';
    } else {
      $html_save_button = "";
    }

    $map_height = array_pop($options);
    if(!$map_height){
      $map_height = DEFAULT_MAP_HEIGHT;
    }
  }


  $body = <<<EOD

   <style>
	#container {
		background-color: #FFFFFF;
		height: $map_height;
		width: 100%;
		border: 1px dashed black;
	}
   </style>
   <input type="button" class="resetView" value="0"></input>
   <input type="button" class="scaleUp" value="+"></input>
   <input type="button" class="scaleDown" value="-"></input>
   <input type="button" class="addSubIdea" value="add"></input>
   <input type="button" class="editNode" value="edit"></input>
   <input type="button" class="removeSubIdea" value="delete"></input>
   <input type="button" class="insertIntermediate" value="insert parent"></input>
   <input type="button" class="toggleCollapse" value="exp/col"></input>
   <input type="button" onclick="mapModel.setInputEnabled(false)" value="disable"></input>
   <input type="button" onclick="mapModel.setInputEnabled(true)" value="enable"></input>
   <input type="button" class="undo" value="undo"></input>
   <input type="button" class="redo" value="redo"></input>
   <input type="button" class="cut" value="cut"></input>
   <input type="button" class="copy" value="copy"></input>
   <input type="button" class="paste" value="paste"></input>
   $html_save_button
   <input type="button" class="openAttachment" value="open attachment"></input>
   <input type="button" class="toggleAddLinkMode" value="add link"></input>
    <div id="linkEditWidget">
      <input class="delete" type="button" value="Delete"></input>
      <select class="color">
        <option value="red">Red</option>
        <option value="blue">Blue</option>
      </select>
      <select class="lineStyle">
        <option value="solid">Solid</option>
        <option value="dashed">Dashed</option>
      </select>
      <button class="arrow">Arrow</button>
    </div>
    <div id="container"></div>
    <script type="text/javascript" src="plugin/mapjs/lib/jquery-2.0.2.min.js"></script>
    <script type="text/javascript">
      var link = document.createElement('link');
      link.setAttribute('href', 'plugin/mapjs/src/mapjs-default-styles.css');
      link.setAttribute('type', 'text/css');
      link.setAttribute('rel', 'stylesheet');
      link.setAttribute('id', 'mapjs_css');
      var meta = document.createElement('meta');  
      meta.setAttribute('name', 'viewport');
      meta.setAttribute('content', 'user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1');
      document.head.appendChild(meta);
      document.head.appendChild(link);
    </script>

    <script type="text/javascript" src="plugin/mapjs/lib/jquery.hotkeys.js"></script>
    <script type="text/javascript" src="plugin/mapjs/lib/hammer.min.js"></script>
    <script type="text/javascript" src="plugin/mapjs/lib/jquery.hammer.min.js"></script>
    <script type="text/javascript" src="plugin/mapjs/lib/underscore-1.4.4.js"></script>
    <script type="text/javascript" src="plugin/mapjs/lib/color-0.7.1.min.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/observable.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/mapjs.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/url-helper.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/content.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/layout.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/clipboard.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/map-model.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/map-toolbar-widget-in-wiki.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/link-edit-widget.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/image-drop-widget.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/hammer-draggable.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/dom-map-view.js"></script>
    <script type="text/javascript" src="plugin/mapjs/src/dom-map-widget.js"></script>
    <script type="text/javascript">
      jQuery.fn.attachmentEditorWidget = function (mapModel) {
        'use strict';
	return this.each(function () {
	var element = jQuery(this);
	mapModel.addEventListener('attachmentOpened', function (nodeId, attachment) {
	    mapModel.setAttachment(
				   'attachmentEditorWidget',
				   nodeId, {
				   contentType: 'text/html',
				       content: prompt('attachment', attachment && attachment.content)
				       }
				   );
	  });
      });
  };
  (function () {
    var flag_css_loaded = 0;
    window.onerror = alert;
    $("#mapjs_css").load(function(){
	flag_css_loaded = 1
	  });
    var timer = setInterval(function() {
	if (flag_css_loaded == 1) {
	  clearInterval(timer);
	  main();
	}
      }, 500);
    function main(){
      var container = jQuery('#container'),
      idea = MAPJS.content(JSON.parse('$contents'));
      imageInsertController = new MAPJS.ImageInsertController("http://localhost:4999?u="),
      mapModel = new MAPJS.MapModel(MAPJS.DOMRender.layoutCalculator, []);
    container.domMapWidget(console, mapModel, false, imageInsertController);
    jQuery('body').mapToolbarWidget(mapModel);
    jQuery('body').attachmentEditorWidget(mapModel);
    $("[data-mm-action='export-image']").click(function () {
	MAPJS.pngExport(idea).then(function (url) {
	    window.open(url, '_blank');
	  });
      });
    mapModel.setIdea(idea);
    jQuery('#linkEditWidget').linkEditWidget(mapModel);
    window.mapModel = mapModel;
    jQuery('.arrow').click(function () {
	jQuery(this).toggleClass('active');
      });
    imageInsertController.addEventListener('imageInsertError', function (reason) {
	console.log('image insert error', reason);
      });
    container.on('drop', function (e) {
	var dataTransfer = e.originalEvent.dataTransfer;
	e.stopPropagation();
	e.preventDefault();
	if (dataTransfer && dataTransfer.files && dataTransfer.files.length > 0) {
	  var fileInfo = dataTransfer.files[0];
	  if (/\.mup$/.test(fileInfo.name)) {
	    var oFReader = new FileReader();
	    oFReader.onload = function (oFREvent) {
	      mapModel.setIdea(MAPJS.content(JSON.parse(oFREvent.target.result)));
	    };
	    oFReader.readAsText(fileInfo, 'UTF-8');
	  }
	}
      });
    }
  }());
  </script>
EOD;
  return $body;
}
?>
