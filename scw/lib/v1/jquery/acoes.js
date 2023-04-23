$(function(){
    
    $("#orcamento_produtos_servicos").click(function(){
        //alert('Acessando acoes.js');
		compras = $.dialog({
			title: $(this).attr("title"),//'FORMULÁRIO',
			content: 'url:navegacao/popup/formulario_orcamento.php?produto='+$(this).attr("title"),
			closeIcon: true,
			columnClass: 'col-md-6 col-md-offset-3',
			animation: 'rotate',
    		closeAnimation: 'rotate',
			animationBounce: 3.5,
			//theme: 'supervan'//'bootstrap'
    	});
    });
    
    
    $("button[mohatron_compra]").click(function(){
        //alert('Acessando acoes.js');
        
        valor = $("#valorInteiro").attr("v");
        produto = $(this).attr("produto");
        cod_produto = $(this).attr("cod_produto");
        detalhes = $("p[resumo]").html()+' ('+$("span[fator]").html()+')';
        
       // alert('Alterado: '+produto+' : '+valor);        
        $.ajax({
            url:'navegacao/popup/formulario_produto.php?produto='+produto+'&cod_produto='+cod_produto+'&valor='+valor+'&detalhes='+detalhes,
            success:function(dados){
        
                produtos = $.dialog({
        			title: produto,//'FORMULÁRIO',
        			content: dados,
        			closeIcon: true,
        			columnClass: 'col-md-6 col-md-offset-3',
        			animation: 'rotate',
            		closeAnimation: 'rotate',
        			animationBounce: 3.5,
        			//theme: 'supervan'//'bootstrap'
            	});
                
            }
        });

    });
    
    
    
    
    
});