<?php
global $wpdb;

if(isset($_POST['pagamento_action'])){
	$evento_id = $_POST['evento_id'];
	if(get_post($evento_id)){
		switch ($_POST['pagamento_action']){
			case 'salvar_contrato_inscricao':
				update_contrato_inscricao($_POST['evento_id'], $_POST['valor'], $_POST['porcentagem'], $_POST['contrato']);
				//_pr('contrato salvo');
			break;
			
			case 'finalizar_contrato_inscricao':
				//_pr('contrato finalizado');
				update_contrato_inscricao($_POST['evento_id'], $_POST['valor'], $_POST['porcentagem'], $_POST['contrato']);
				
				// altera o post_status do evento para "pay_pending_ok"
				$wpdb->query("UPDATE $wpdb->posts SET post_status = 'pay_pending_ok' WHERE ID = '$evento_id'");
				
				// envia o email... (esta funcção está em "includes/email_messages.php")
				do_action('tnb_editor_revisou_evento_cobranca',$_POST['evento_id']);
			break;
			
			case 'marcar_pagamento_como_efetuado':
				add_post_meta($_POST['evento_id'], 'inscricao_pagamento_efetuado', true);
				do_action('tnb_editor_efetuou_pagamento_inscricoes',$_POST['evento_id']);
			break;
			
			case 'save-emails-pagamento': echo "AQUI";
				_pr($_POST);
				if(!get_option('emails_pagamento'))
					add_option('emails_pagamento', $_POST['pagamentos_emails']);
				else
					update_option('emails_pagamento', $_POST['pagamentos_emails']);
			break;
		}
	}elseif($_POST['pagamento_action'] == 'save-emails-pagamento'){
		if(!get_option('emails_pagamento'))
			add_option('emails_pagamento', $_POST['pagamentos_emails']);
		else
			update_option('emails_pagamento', $_POST['pagamentos_emails']);
	}
}