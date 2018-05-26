<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Easy Viral PDF Brander | Rebrand PDFs On The Fly</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="jquery/jquery-1.3.2.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery/jquery.flot.selection.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery/jquery-ui-1.7.3.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery/mColorPicker.js"></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<!--[if IE]><script language="javascript" type="text/javascript" src="jquery/excanvas.min.js"></script><![endif]-->
<link type="text/css" href="jquery/ui.css" rel="stylesheet" />
<script type="text/javascript">
$(function() {
  $("#sortable").sortable({
		placeholder: 'ui-state-highlight',
		forcePlaceholderSize: true,
		handle: '.handle'
	});
	$("#fieldsettings").submit(function () {
    var ordering = $("#sortable").sortable( "toArray" );
    var commastring = "";  
    $.each(ordering, function(key, value) { 
      commastring = commastring + value + ",";
    });
    $("#order").val(commastring);    
  });
	$('.mce_load').click(function(){
      var txtarea = ($(this).attr("id")).substring(4);
			$('#dialog').remove();
			$('body').append('<div id="dialog" \/>');
			$('#dialog').dialog({	
				autoOpen: false,
				bgiframe: true,
				resizable: false,
				width: 645,
				position: ['center','top'],
				overlay: { backgroundColor: '#000', opacity: 0.5 },
				beforeclose: function(event, ui) {
					tinyMCE.get('editor').remove();
					$('#editor').remove();
				}
				
			});		
			$('#dialog').dialog('option', 'title', 'WYSIWYG Editor');
			$('#dialog').dialog('option', 'modal', true);
			$('#dialog').dialog('option', 'buttons', {
				'Cancel': function() {
					$(this).dialog('close');
				},
				'Update HTML': function() {
					var content = tinyMCE.get('editor').getContent();
					$('#'+txtarea).val(content);
					$(this).dialog('close');
				}
			});
		
			$('#dialog').html('<p style="font-size: 12px">Note: Don\'t forget to save your changes after clicking "Update HTML" by submitting the form from which you opened this editor </p><textarea name="editor" id="editor"><\/textarea>');
			$('#dialog').dialog('open');
			tinyMCE.init({
        // Location of TinyMCE script
        	mode : "exact",
        	elements : "editor",

    
    		// General options
    		theme : "advanced",
    		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
    
    		// Theme options
    		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
    		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,preview,|,forecolor,backcolor",
    		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,fullscreen",
    		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
    		theme_advanced_toolbar_location : "top",
    		theme_advanced_toolbar_align : "center",
    		theme_advanced_statusbar_location : "bottom",
    		theme_advanced_resizing : true,
				
				width: "600",
				height: "300",

        
        setup : function(ed) {
					ed.onInit.add(function(ed) {
						//alert('Editor is done: ' + ed.id);
						tinyMCE.get('editor').setContent($('#'+txtarea).val());
						tinyMCE.execCommand('mceRepaint');
					});		
				}
				 			
		 	});
			return false;     		
	});
		
});

</script>
</head>
<body>

		<div id="wrap">      
      <div id="header">      
      </div>      
     
    <div id="main">
    <noscript>
      <h1>You must enable javascript for the admin panel to function correctly.</h1>
    </noscript>