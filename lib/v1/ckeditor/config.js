/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.extraPlugins = 'moh';
	/*
	config.toolbar_titulos = [
	    [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ],
	    [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],
	    [ 'Bold', 'Italic','Image', 'Moh']
	];
    */
	config.toolbar_samira = [
	    [ 'Source', '-', 'NewPage', 'Preview', 'Moh' ]
	];
//*
	config.toolbar_basico = [
	    [ 'Cut' , 'Copy' , 'Paste' , 'PasteFromWord' ],
	    [ 'Bold', 'Italic' , 'Underline' , 'SStrikeelect' , 'Subscript' , 'Superscript' ],
	    [ 'NumberedList' , 'BulletedList' , 'JustifyLeft' , 'JustifyCenter' , 'JustifyRight' , 'JustifyBlock'  ],
	    [ 'colors' , 'TextColor' , 'BGColor' ]
	];
//*/	
	config.toolbar_titulos = [
		[ 'Bold', 'Italic', 'Table', 'Copy', 'Paste' ],
		[ 'TextColor', '-', 'Image', 'Maximize' ],
		[ 'Source', 'NewPage' , 'Preview' , 'Print' ],
		[ 'Cut', 'Copy' , 'Paste' , 'PasteFromWord' , 'Undo' , 'Redo' ],
		[ 'Find' , 'Replace' , 'SelectAll' ],
		[ 'Bold', 'Italic' , 'Underline' , 'SStrikeelect' , 'Subscript' , 'Superscript' , 'RemoveFormat'  ],
		[ 'NumberedList' , 'BulletedList' , 'Outdent' , 'Indent' , 'JustifyLeft' , 'JustifyCenter' , 'JustifyRight' , 'JustifyBlock'  ],
		[ 'Table' ],
		[ 'Font' , 'FontSize' ]	,
		[ 'colors', 'TextColor', 'BGColor' ],	
		[ 'tools', 'Maximize', 'ShowBlocks', 'Moh'  ]	
	];
	
	

};


/*
		[ 'Bold', 'Italic', 'Link', 'Unlink', 'Table', 'Copy', 'Paste' ],
		[ 'FontSize', 'Font', 'TextColor', '-', 'Image', 'Maximize' , 'Anchor' ],
		[ 'document', 'mode', 'document', '-', 'Source', 'Save' , 'NewPage' , 'Preview' , 'Print' , 'Templates' ],
		[ 'clipboard', 'clipboard', 'undo', '-', 'Cut', 'Copy' , 'Paste' , 'PasteFromWord' , 'Undo' , 'Redo' ],
		[ 'editing', 'find', 'selection', '-', 'spellchecker', 'Find' , 'Replace' , 'SelectAll' , 'Scayt'  ],
		[ 'forms', 'Form', 'Checkbox', '-', 'Radio', 'TextField' , 'Textarea' , 'Select' , 'Button' , 'ImageButton' , 'HiddenField'  ],
		[ 'basicstyles', 'basicstyles', 'cleanup', '-', 'Bold', 'Italic' , 'Underline' , 'SStrikeelect' , 'Subscript' , 'Superscript' , 'RemoveFormat'  ],
		[ 'paragraph', 'list', 'indent', '-', 'blocks', 'align' , 'bidi' , 'NumberedList' , 'BulletedList' , 'Outdent' , 'Indent' , 'Blockquote' , 'CreateDiv' , 'JustifyLeft' , 'JustifyCenter' , 'JustifyRight' , 'JustifyBlock' , 'BidiLtr' , 'BidiRtl' , 'Language'  ],
		[ 'links', 'Link', 'Anchor' ],
		[ 'insert', 'Image', 'Flash', '-', 'Table', 'HorizontalRule' , 'Smiley' , 'SpecialChar' , 'PageBreak' , 'Iframe' ],
		[ 'styles', 'Format', 'Font' , 'FontSize' ]	,
		[ 'colors', 'TextColor', 'BGColor' ],	
		[ 'tools', 'Maximize', 'ShowBlocks' ],	
		[ 'document', 'mode', 'document' , 'doctools' ],
		[ 'others', '-' ],		
		[ 'about', 'About' ],		
		[ 'clipboard', 'clipboard', 'undo' ],	
		[ 'editing', 'find', 'selection', 'spellchecker' ]	,								
		[ 'forms']	,
		[ 'basicstyles', 'basicstyles', 'cleanup' ]	,
		[ 'list', 'indent', 'blocks' , 'align' , 'bidi' ],
		[ 'links', 'styles', 'colors' , 'tools' , 'others', 'Moh' ]
*/