<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<script src="http://www.mohatron.com/2016/js/jquery.min.js"></script>
	<script src="ckeditor.js?<?=date('YmdHis')?>"></script>
</head>
<body>
	
<div >
	<div >
		<div id="modelo1" contenteditable="true">
			<h2>
				CKEditor<br> Goes Inline!
			</h2>
			<h3>
				Lorem ipsum dolor sit amet dolor duis blandit vestibulum faucibus a, tortor.
			</h3>
			
		</div>
		<div id="modelo2" contenteditable="true">
			<div>
				<div contenteditable="true">
				<img src="https://plus.google.com/u/1/_/focus/photos/public/AIbEiAIAAABDCP6Ij9zO-JmKBSILdmNhcmRfcGhvdG8qKDM2Y2M3MzFhMTU4NmEwODJiYjM4YmFmNDRmM2MwOWE1OGEwZTFmMjMwAV1MPlzdsYesI88jxWQzsXsZFir_?sz=64" />
				</div>
				<p>
					Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies.
				</p>
				<p>
					Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac.
				</p>
			</div>
		</div>
	</div>
</div>

<div>
    <h1>Inline Editing in Action!</h1>
    <p>The "div" element that contains this text is now editable.
</div>

<script>


		CKEDITOR.inline( 'modelo1',
                        {
                            toolbar : 'samira', /* this does the magic */
                            uiColor : '#9AB8F3'
                        });

		CKEDITOR.inline( 'modelo2',
                        {
                            toolbar : 'imagem', /* this does the magic */
                            uiColor : '#9AB8F3'
                        });

	

</script>

</body>
</html>