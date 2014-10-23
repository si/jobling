<?php
// this file contains the contents of the popup window
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Shortcode Manager</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo includes_url('/js/tinymce/tiny_mce_popup.js'); ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo $fscb_base_url; ?>includes/js/popup-tabs.js"></script>
<style type="text/css" src="<?php echo includes_url( '/js/tinymce/themes/advanced/skins/wp_theme/dialog.css'); ?>"></style>
<link rel="stylesheet" href="<?php echo $fscb_base_url; ?>/includes/css/friendly_buttons_tinymce.css" />

<script type="text/javascript">
 
 
// ****** Build Column Shortcode ****** //

var LayoutDialog = {
	local_ed : 'ed',
	init : function(ed) {
		LayoutDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertLayout(ed) {
	 
		// Try and remove existing style / blockquote
		tinyMCEPopup.execCommand('mceRemoveNode', false, null);
		 
		// set up variables to contain our input values
		var colcontent = jQuery('#layout-dialog input#column-content').val();
		var width = jQuery('#layout-dialog select#column-width').val();
		
		// setup the output of our shortcode
		output = '[column ';
			output += 'width=' + width + ' ';
				
		// check to see if the content field is blank
		if(colcontent) {	
			output += ']'+ colcontent + '[/column]';
		}
		// if it is blank, use the selected text, if present
		else {
			output += ']'+LayoutDialog.local_ed.selection.getContent() + '[/column]';
		}
		tinyMCEPopup.execCommand('mceReplaceContent', false, output);
		 
		// Return
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(LayoutDialog.init, LayoutDialog);


// ****** Build Message Shortcode ****** //

var MessageDialog = {
	local_ed : 'ed',
	init : function(ed) {
		MessageDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertMessage(ed) {
	 
		// Try and remove existing style / blockquote
		tinyMCEPopup.execCommand('mceRemoveNode', false, null);
		 
		// set up variables to contain our input values
		var messcontent = jQuery('#message-dialog input#message-content').val();
		var messtype = jQuery('#message-dialog select#message-type').val();
		
		// setup the output of our shortcode
		output = '[message ';
			output += 'type=' + messtype + ' ';
				
		// check to see if the content field is blank
		if(messcontent) {	
			output += ']'+ messcontent + '[/message]';
		}
		// if it is blank, use the selected text, if present
		else {
			output += ']'+MessageDialog.local_ed.selection.getContent() + '[/message]';
		}
		tinyMCEPopup.execCommand('mceReplaceContent', false, output);
		 
		// Return
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(MessageDialog.init, MessageDialog);

// ****** Build Button Shortcode ****** //

var ButtonDialog = {
	local_ed : 'ed',
	init : function(ed) {
		ButtonDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertButton(ed) {
	 
		// Try and remove existing style / blockquote
		tinyMCEPopup.execCommand('mceRemoveNode', false, null);
		 
		// set up variables to contain our input values
		var buttoncontent = jQuery('#button-dialog input#button-content').val();
		var buttonlink = jQuery('#button-dialog input#buttonlink').val();
		var buttonalign = jQuery('#button-dialog select#buttonalign').val();
		
		// setup the output of our shortcode
		output = '[button ';
			output += 'alignment=' + buttonalign + ' link=' + buttonlink + ' ';
				
		// check to see if the content field is blank
		if(buttoncontent) {	
			output += ']'+ buttoncontent + '[/button]';
		}
		// if it is blank, use the selected text, if present
		else {
			output += ']'+ButtonDialog.local_ed.selection.getContent() + '[/button]';
		}
		tinyMCEPopup.execCommand('mceReplaceContent', false, output);
		 
		// Return
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);
 
</script>

</head>

<!-- html and forms for modal window --> 

<body>
	
	<div id="tabs" >
		<ul>
			<li><a href="#tab-1">Layout</a></li>
			<li><a href="#tab-2">Messages</a></li>
			<li><a href="#tab-3">Buttons</a></li>
		</ul>
		
		<div id="tab-1" class="tab">
			<div id="layout-dialog">
				<form action="/" method="get" accept-charset="utf-8">
					<div>
						<label for="column-content">Column Text</label>
						<input type="text" name="column-content" value="" id="column-content" />
					</div>
					<div>
						<label for="column-width">Column Width</label>
						<select name="column-width" id="column-width" size="1">
							<option value="third" selected="selected">1/3 Column</option>
							<option value="twothird"=>2/3 Column</option>
							<option value="quarter">1/4 Column</option>
							<option value="threequarter">3/4 Column</option>
							<option value="half">1/2 Column</option>
						</select>
					</div>
					
					<div>	
						<a href="javascript:LayoutDialog.insert(LayoutDialog.local_ed)" id="insert" style="display: block; line-height: 24px;">Insert</a>
					</div>
				</form>
			</div>
		</div>
		
		<div id="tab-2" class="tab">
		 	<div id="message-dialog">
				<form action="/" method="get" accept-charset="utf-8">
					<div>
						<label for="messcontent">Text</label>
						<input type="text" name="message-content" value="" id="message-content" />
					</div>
					
					<div>
						<label for="message-type">Message Type</label>
						<select name="message-type" id="message-type" size="1">
							<option value="alert" selected="selected">Alert Message</option>
							<option value="warning">Warning Message</option>
						</select>
					</div>
					
					<div>	
						<a href="javascript:MessageDialog.insert(MessageDialog.local_ed)" id="insert" style="display: block; line-height: 24px;">Insert</a>
					</div>
				</form>
			</div>
		</div>
		
		<div id="tab-3" class="tab">
		 	<div id="button-dialog">
				<form action="/" method="get" accept-charset="utf-8">
					<div>
						<label for="buttoncontent">Text</label>
						<input type="text" name="button-content" value="" id="button-content" />
					</div>
					
					<div>
						<label for="buttonlink">Link (full URL)</label>
						<input type="text" name="buttonlink" value="" id="buttonlink" />
					</div>
					
					<div>
						<label for="buttonalign">Alignment</label>
						<select name="buttonalign" id="buttonalign" size="1">
							<option value="left" selected="selected">Left</option>
							<option value="right">Right</option>
						</select>
					</div>
					
					<div>	
						<a href="javascript:ButtonDialog.insert(ButtonDialog.local_ed)" id="insert" style="display: block; line-height: 24px;">Insert</a>
					</div>
				</form>
			</div>
		</div>
	</div>
					 
	
</body>
</html>