<section id="login" class="content">
    <form method="post" enctype="multipart/form-data">
        <input type='hidden' name='tnb_user_action' value='edit-login' />

        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Informações de Login", "tnb"); ?></span>
        </h2>
        
        <div class="clearfix">
            <label><?php _e("Nome de Usuário", "tnb"); ?></label>
            <input type="text" value="<?php echo $profileuser->user_login; ?>" disabled />
            <br/>
            <span class="info"><?php _e("O nome de usuário não pode ser alterado", "tnb"); ?></span>
        </div>
        <!-- .clearfix -->
        
        <div class="clearfix">
            <label><?php _e("E-mail de cadastro", "tnb"); ?></label>
            <input type="text" name='user_email' value="<?php echo $profileuser->user_email; ?>" />
            <br/>
            <span class="info"><?php _e("Esse é o seu e-mail de cadastro. Apenas o TNB tem acesso a ele, para comunicar-se com seus usuários. ", "tnb"); ?></span>
        </div>
        <!-- .clearfix -->
        
        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Foto de perfil", "tnb"); ?></span>
        </h2>
        
        <?php do_action('custom_edit_user_profile'); ?>
        
        
        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Alterar Senha", "tnb"); ?></span>
        </h2>
        
        <div class="clearfix">
            <label><?php _e("Nova Senha", "tnb"); ?></label>
            <input type="password" name='user_pass' />
        </div>
        <!-- .clearfix -->

        <div class="clearfix">
            <label><?php _e("Confirmação da Senha", "tnb"); ?></label>
            <input type="password" name='user_pass_confirm' />
        </div>
        <!-- .clearfix -->
        
        

        

        <div class="clearfix text-right">
            <input type="submit" value="Salvar" class="submit" />
        </div>
        <!-- .clearfix -->
        
        <h2 class="section-title">
            <span class="bg-blue"><?php _e("Encerrar conta", "tnb"); ?></span>
        </h2>
        
        <div class="clearfix">
            <a id="delete_profile_link" href="<?php echo add_query_arg('delete_profile', wp_create_nonce('delete_profile_' . $profileuser->ID) ); ?>" >
                <span><?php _e("Encerrar minha conta no TNB", "tnb"); ?></span>
            </a>
             - <?php _e("Todos as minhas publicações serão permanentemente removidas", "tnb"); ?>
        </div>
        
        <script>
        
        jQuery('#delete_profile_link').click(function() {
            if (confirm('<?php _e('Tem certeza que deseja encerrar sua conta no TNB? Não há como desfazer essa ação!', 'tnb'); ?>')) {
                return true;
            } else {
                return false;
            }
        });
        
        </script>
        
        
    </form>
</section>
<!-- #login -->
