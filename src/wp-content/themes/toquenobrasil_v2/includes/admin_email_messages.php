<?php
add_action('admin_menu', 'custom_email_notices_add_page');
add_action('admin_init', 'custom_email_notices_init' );

function custom_email_notices_add_page() {
    add_options_page(__("Mensagens de E-mail"), __("Mensagens de E-mail"), "manage_options", "email-messages", "custom_email_notices");
}

function custom_email_notices_init(){
    register_setting( 'custom_email_notices_options', 'custom_email_notices', 'custom_email_notices_validate' );
}

function custom_email_notices() {
	?>
        <style type="text/css">
            /* TODO: Refazer estilo para esta página */
            form.tnb-mail-messages div {
                margin-bottom: 0.5em;
                padding: 0.5em;
                background-color: #f0f0f0;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }
        </style>

		<div class="wrap">
            <div class="icon32" id="icon-options-general"><br/></div>
            <h2>Mensagens de E-mail</h2>

			<form method="post" action="options.php" class="tnb-mail-messages">
				<?php settings_fields('custom_email_notices_options'); ?>
				<?php $options = get_option('custom_email_notices');?>

                <div>
                    <label for="msg_novo_artista"><?php _e("Mensagem para o artista recém registrado");?></label><br/>
                    <textarea id="msg_novo_artista" class="large-text code" rows="10" name="custom_email_notices[msg_novo_artista]"><?php echo $options['msg_novo_artista'];?></textarea><br/>
                    <small>
                    E-mail enviado ao artista quando ele se registra no site. <br />
                    {{INFORMACOES}} será substituído por Nome de usuário, senha e link de ativação do cadastro.
                    </small>
                </div>

                <div>
                    <label for="msg_novo_produtor"><?php _e("Mensagem para o produtor recém registrado");?></label><br/>
                    <textarea id="msg_novo_produtor" class="large-text code" rows="10" name="custom_email_notices[msg_novo_produtor]"><?php echo $options['msg_novo_produtor'];?></textarea><br/>
                    <small>
                    E-mail enviado ao produtor quando ele se registra no site. <br />
                    {{INFORMACOES}} será substituído por Nome de usuário e senha.
                    </small>
                </div>

                <div>
                    <label for="msg_inscr_produtor"><?php _e("Mensagem para o produtor cujo evento recebeu uma inscrição de um artista");?></label><br/>
                    <textarea id="msg_insc_produtor" class="large-text code" rows="10" name="custom_email_notices[msg_insc_produtor]"><?php echo $options['msg_insc_produtor'];?></textarea><br/>
                    <small>
                    E-mail enviado ao produtor quando um artista se inscreve para um evento seu. <br />
                    {{INFORMACOES}} será substituído pelos dados do artista.
                    </small>
                </div>

                <div>
                    <label for="msg_inscr_artista"><?php _e("Mensagem para o artista que se inscreveu em um evento");?></label><br/>
                    <textarea id="msg_insc_artista" class="large-text code" rows="10" name="custom_email_notices[msg_insc_artista]"><?php echo $options['msg_insc_artista'];?></textarea><br/>
                    <small>
                    E-mail enviado ao artista quando ele se inscreve em um evento. <br />
                    {{INFORMACOES}} será substituído pelo nome do evento no qual ele se inscreveu.
                    </small>
                </div>

                <div>
                    <label for="msg_new_subevent"><?php _e("Mensagem para o produtor cujo superevento foi atrelado a um subevento de outro produtor");?></label><br/>
                    <textarea id="msg_new_subevent" class="large-text code" rows="10" name="custom_email_notices[msg_new_subevent]"><?php echo $options['msg_new_subevent'];?></textarea><br/>
                    <small>
                    E-mail enviado ao produtor de um super evento quando um outro produtor cria um evento filho do seu evento. <br />
                    {{INFORMACOES}} será substituído pelos dados do produtor do evento filho assim como as informações links para o evento que ele tem que moderar.
                    </small>
                </div>

                <div>
                    <label for="msg_subevent_approved"><?php _e("Mensagem para o produtor cujo subevento foi aprovado pelo produtor do superevento");?></label><br/>
                    <textarea id="msg_subevent_approved" class="large-text code" rows="10" name="custom_email_notices[msg_subevent_approved]"><?php echo $options['msg_subevent_approved'];?></textarea><br/>
                    <small>
                    E-mail enviado ao produtor de um evento "filho" quando o dono do Super evento para o qual ele inscreveu um evento aprova o seu evento. <br />
                    {{INFORMACOES}} será substituído pelo nome do Super evento.
                    </small>
                </div>
                
                <div>
                    <label for="msg_evento_desativado_por_superevento"><?php _e("Mensagem para o produtor cujo subevento foi desativado por Superevento");?></label><br/>
                    <textarea id="msg_evento_desativado_por_superevento" class="large-text code" rows="10" name="custom_email_notices[msg_evento_desativado_por_superevento]"><?php echo $options['msg_evento_desativado_por_superevento'];?></textarea><br/>
                    <small>
                    E-mail enviado ao produtor de um evento "filho" quando o dono do Super evento desativa o Super Evento e, por consequencia, todos os eventos filhos <br />
                    {{INFORMACOES}} será substituído pelos dados do Super Evento e do sub evento desativado.
                    </small>
                </div>
                
                <div>
                    <label for="msg_artista_desinscrito_pelo_filtro"><?php _e("Mensagem para o artista que foi desinscrito de uma oportunidade por causa de mudança nas restrições de genero ou local");?></label><br/>
                    <textarea id="msg_artista_desinscrito_pelo_filtro" class="large-text code" rows="10" name="custom_email_notices[msg_artista_desinscrito_pelo_filtro]"><?php echo $options['msg_artista_desinscrito_pelo_filtro'];?></textarea><br/>
                    <small>
                    <br />
                    {{INFORMACOES}} será substituído pelos dados da oportunidade da qual o artista foi desinscrito.
                    </small>
                </div>
                

                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
				</p>
			</form>
		</div>
	<?php
}

function custom_email_notices_validate($msgs) {
    $should_have_info = array('msg_novo_artista', 'msg_novo_produtor', 'msg_insc_produtor');

    foreach($should_have_info  as $key) {
        if(!preg_match('/{{INFORMACOES}}/', $msgs[$key])) {
            $msgs[$key] = $msgs[$key] . "\r\n" . "{{INFORMACOES}}";
        }
    }
	return $msgs;
}
