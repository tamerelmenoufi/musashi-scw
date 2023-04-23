GetForm = (obj) => {

	i=0;
	campo = new Array();
	valor = new Array();

	obj.find("input[form]").each(function(){
		campo[i] = $(this).attr('id');
		valor[i] = $(this).val();
		i++;
	});

	obj.find("select[form]").each(function(){
		campo[i] = $(this).attr('id');
		valor[i] = $(this).val();
		i++;
	});

	obj.find("textarea[form]").each(function(){
		campo[i] = $(this).attr('id');
		valor[i] = $(this).val();
		i++;
	});

	obj.find("button[form]").each(function(){
		campo[i] = $(this).attr('id');
		valor[i] = $(this).val();
		i++;
	});


}


JanelaAlerta = () => {
		$(".JanelaAlerta").css('display','block');
	setTimeout(()=>{
		$(".JanelaAlerta").css('display','none');
	}, 3000);
}

JanelaAlertaErro = () => {
		$(".JanelaAlertaErro").css('display','block');
	setTimeout(()=>{
		$(".JanelaAlertaErro").css('display','none');
	}, 3000);
}


GetImg = (obj) => {


    if (window.File && window.FileList && window.FileReader) {
        
        $('input[type="file"]').change(function () {
            
            if($(this).val()){
            
                var files = $(this).prop("files");
                for (var i = 0; i < files.length; i++) {
                    (function (file) {
            
                            var fileReader = new FileReader();
                            fileReader.onload = function (f) {
        						var Base64 = f.target.result;
        						var type = file.type;
        						var name = file.name;                                

        						obj.attr('Base64',Base64);
        						obj.attr('tipo',type);
        						obj.attr('nome',name);

                            };
                            fileReader.readAsDataURL(file);
                    })(files[i]);
                }
            }
        });
    }


}