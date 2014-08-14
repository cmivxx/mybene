
<script type="text/javascript">
//<![CDATA[

function addHtmlLink(l, f) {
	
		var name = prompt("Enter link name for file: "+f+" ", "");
		
		if( name != '' && name != null ) {
			html = '<a href="'+l+'" target="_blank">'+name+'</a>';
			send_to_editor(html);
			return false;
		} else {
			return false;
		}
		
}

function send_to_editor(h) {
	var ed;
			alert('editor');

	if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
		ed.focus();
		if ( tinymce.isIE )
			ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);

		if ( h.indexOf('[caption') === 0 ) {
			if ( ed.plugins.wpeditimage )
				h = ed.plugins.wpeditimage._do_shcode(h);
		} else if ( h.indexOf('[gallery') === 0 ) {
			if ( ed.plugins.wpgallery )
				h = ed.plugins.wpgallery._do_gallery(h);
		} else if ( h.indexOf('[embed') === 0 ) {
			if ( ed.plugins.wordpress )
				h = ed.plugins.wordpress._setEmbed(h);
		}

		ed.execCommand('mceInsertContent', false, h);

	} else if ( typeof edInsertContent == 'function' ) {
		edInsertContent(edCanvas, h);
	} else {
		jQuery( edCanvas ).val( jQuery( edCanvas ).val() + h );
	}

	tb_remove();
}
</script>

(function() {
	tinymce.create('tinymce.plugins.ExamplePlugin', {
		init : function(ed, url) {
			ed.addButton('example', {
				title : 'youryoutube.youtube',
				image : url+'/youtube.png',
				onclick : function() {
					idPattern = /(?:(?:[^v]+)+v.)?([^&=]{11})(?=&|$)/;
					var vidId = prompt("YouTube Video", "Enter the id or url for your video");
					var m = idPattern.exec(vidId);
					if (m != null && m != 'undefined')
						ed.execCommand('mceInsertContent', false, '[youtube id="'+m[1]+'"]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "YouTube Shortcode",
				author : 'Brett Terpstra',
				authorurl : 'http://brettterpstra.com/',
				infourl : 'http://brettterpstra.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('example', tinymce.plugins.ExamplePlugin);
})();

