CKEDITOR.plugins.add('moh',
{
    init: function (editor) {
        var pluginName = 'moh';
        editor.ui.addButton('Moh',
            {
                label: 'My New Plugin',
                command: 'OpenWindow',
                icon: CKEDITOR.plugins.getPath('moh') + 'icons/icone.png'
            });
        var cmd = editor.addCommand('OpenWindow', { exec: showMyDialog });
    }
});
function showMyDialog(e) {
    //window.open('/Default.aspx', 'MyWindow', 'width=800,height=700,scrollbars=no,scrolling=no,location=no,toolbar=no');
    opc = $("editor").attr('opc');
    moh_janela = $.dialog({
        content:"url:titulacao/modelos.php?opc="+opc 
    });
}