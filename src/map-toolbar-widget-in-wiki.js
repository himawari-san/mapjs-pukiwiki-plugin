/*
  This program is released under the MIT License.

  This program was derived by Masaya YAMAGUCHI from
  map-toolbar-widget.js included in map-js
  (https://github.com/mindmup/mapjs) that is distributed under the MIT
  License.

     Copyright (c) 2013 Damjan Vujnovic, David de Florinier, Gojko Adzic
     Permission is hereby granted, free of charge, to any person obtaining a copy of
     this software and associated documentation files (the "Software"), to deal in
     the Software without restriction, including without limitation the rights to
     use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
     of the Software, and to permit persons to whom the Software is furnished to do
     so, subject to the following conditions:

     The above copyright notice and this permission notice shall be included in all
     copies or substantial portions of the Software.
*/

/*global jQuery*/
jQuery.fn.mapToolbarWidget = function (mapModel) {
	'use strict';
	var clickMethodNames = ['insertIntermediate', 'scaleUp', 'scaleDown', 'addSubIdea', 'editNode', 'removeSubIdea', 'toggleCollapse', 'addSiblingIdea', 'undo', 'redo',
				'copy', 'cut', 'paste', 'saveAsWiki', 'resetView', 'openAttachment', 'toggleAddLinkMode', 'activateChildren', 'activateNodeAndChildren', 'activateSiblingNodes', 'editIcon'],
		changeMethodNames = ['updateStyle'];
	return this.each(function () {
		var element = jQuery(this), preventRoundtrip = false;
		mapModel.addEventListener('nodeSelectionChanged', function () {
			preventRoundtrip = true;
			element.find('.updateStyle[data-mm-target-property]').val(function () {
				return mapModel.getSelectedStyle(jQuery(this).data('mm-target-property'));
			}).change();
			preventRoundtrip = false;
		});
		mapModel.addEventListener('addLinkModeToggled', function () {
			element.find('.toggleAddLinkMode').toggleClass('active');
		});
		clickMethodNames.forEach(function (methodName) {
			element.find('.' + methodName).click(function () {
			    if (mapModel[methodName]) {
					mapModel[methodName]('toolbar');
			    } else if(methodName == "saveAsWiki"){
				window.alert("Save as Wiki");
				var current_path = location.href.replace(/^[^\?]+\?/,"").replace(/#[^#]+$/,"");

				$.ajax({
				    url : "./index.php?plugin=teo_update_mup",
				    contentType : "application/x-www-form-urlencoded; Charset=UTF-8",
				    type : "POST",
				    data : "pagecontent=" +
					escape(JSON.stringify(mapModel.getIdea())) +
					"&refer=" + current_path,
				    success : function( text, req, o ) {
				    },
				    error : function( type, req, o ) {
					window.alert("Some problem occured while saving this map!");
				    }
				});
			    }
			});
		});
		changeMethodNames.forEach(function (methodName) {
			element.find('.' + methodName).change(function () {
				if (preventRoundtrip) {
					return;
				}
				var tool = jQuery(this);
				if (tool.data('mm-target-property')) {
					mapModel[methodName]('toolbar', tool.data('mm-target-property'), tool.val());
				}
			});
		});
	});
};
