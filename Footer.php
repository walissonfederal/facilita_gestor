<?php include_once('_model/_modal.php'); ?>
<script>
    $("#valor").maskMoney({thousands: '', decimal: '.'});
</script>
<script>
    /*function carrega_pagina(pasta, arquivo) {
        $("#texto_modal_p").html('');
        var arquivo_pronto = arquivo.split('?');
        if (arquivo_pronto[1] === 'OP=CR') {
            var arquivo_pronto_funcao = arquivo;
        } else if (arquivo_pronto[1] === 'OP=CP') {
            var arquivo_pronto_funcao = arquivo;
        } else {
            var arquivo_pronto_funcao = arquivo_pronto[0];
        }
        var acao_carrega_pagina = "&acao=verifica_permissao&pasta=" + pasta + "&arquivo=" + arquivo_pronto_funcao;

        $.ajax({
            type: 'POST',
            url: "_controller/_nivel.php",
            data: acao_carrega_pagina,
            beforeSend: load_in(),
            success: function (data) {
                load_out();
                var data_return = jQuery.parseJSON(data);
                if (data_return.type === 'error') {
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                } else {
                    $.post('view/' + pasta + '/' + arquivo + '', function (html) {
                        $('.carregar_paginas').html(html);
                    });
                }
            }
        });
    }*/
    function carrega_pagina(pasta, arquivo) {
        $("#texto_modal_p").html('');
        if (pasta === 'financeiro') {
            var arquivo_pronto = arquivo.split('?');
            var desc_arquivo_pronto = arquivo_pronto[1];
            if (desc_arquivo_pronto !== undefined) {
                var arquivo_pronto_private = desc_arquivo_pronto.split('&');
            }
            if (arquivo_pronto_private[0] === 'OP=CR') {
                var arquivo_pronto_funcao = arquivo;
            } else if (arquivo_pronto_private[0] === 'OP=CP') {
                var arquivo_pronto_funcao = arquivo;
            } else {
                var arquivo_pronto_funcao = arquivo_pronto[0];
            }
        } else {
            var arquivo_pronto = arquivo.split('?');
            var arquivo_pronto_funcao = arquivo_pronto[0];
        }
        var acao_carrega_pagina = "&acao=verifica_permissao&pasta=" + pasta + "&arquivo=" + arquivo_pronto_funcao;

        $.ajax({
            type: 'POST',
            url: "_controller/_nivel.php",
            data: acao_carrega_pagina,
            beforeSend: load_in(),
            success: function (data) {
                var data_return = jQuery.parseJSON(data);
                if (data_return.type === 'error') {
                    $("#_modal").modal('show');
                    $("#title_modal").html(data_return.title);
                    $("#texto_modal").html(data_return.msg);
                    $("#buttons_modal").html(data_return.buttons);
                    load_out();
                } else {
                    $.post('view/' + pasta + '/' + arquivo + '', function (html) {
                        $('.carregar_paginas').html(html);
                        load_out();
                    });
                }
            }
        });
    }
	/*$(function(){
		loadMonitoring();
		setInterval(function(){ 
			loadMonitoring();	
		}, 120000);
	});
	function loadMonitoring() {
		$.ajax({
			type: 'POST',
			url: "_work.php",
			data: {
				action: 'load',
				id_user: <?php echo $_SESSION['id_usuario'];?>,
				tipo: <?php echo $_SESSION['id_verificar'];?>
			},
			success: function (data_return) {
				
			},
			error: function (error) {
			}
		});
	}*/

    $('.telefone').mask('(00)000000000');
    $('.referencia_faturamento_mask').mask('00/0000');
    $('.cep').mask('00000-000');
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112415887-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-112415887-1');
		</script>
<!--<div class="load" style="display: none; z-index: 9999999; position: absolute; left: 40%; top: 40%; margin-left: -40px; margin-top: -40px;">
    <img src="_img/pre-loader.gif" alt=""/>
</div>-->
</body>
</html>