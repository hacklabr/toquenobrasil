<?php
add_action('admin_menu', 'fix_usuarios_cidades_add_page');

function fix_usuarios_cidades_add_page() {
    add_users_page(__("Cadastro incompleto"), __("Cadastro incompleto"), "manage_options", "fix_usuarios_cidades", "fix_usuarios_cidades_page");
}


function fix_usuarios_cidades_page() {
	
    
    global $wpdb;
    
    $users = $wpdb->get_col("SELECT ID FROM $wpdb->users");
    
    
    ?>

		<div class="wrap">
            <div class="icon32" id="icon-options-general"><br/></div>
            <h2>Usuários com cadastro de localidade incompleto</h2>

            <?php if (is_array($users) && sizeof($users) > 0): ?>
            
                <table cellspacing="0" class="widefat fixed">
                
                    <tr class="thead">
                    
                        <th style="" class="manage-column" scope="col">Nome</th>
                        <th style="" class="manage-column" scope="col">E-mail</th>
                        <th style="" class="manage-column" scope="col">Origem</th>
                        <th style="" class="manage-column" scope="col">Residência</th>
                        
                    </tr>
                
                <?php foreach ($users as $u): ?>
                
                    <?php $user = get_userdata($u); ?>
                    <tr>
                        <td>
                            <a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo $user->display_name; ?></a>
                        </td>
                        <td>
                            <?php echo $user->user_email; ?>
                        </td>
                        <td>
                            Valores informados:<br/>
                            País: <?php echo $user->origem_pais; ?><br />
                            Estado: <?php echo $user->origem_estado; ?><br />
                            Cidade: <?php echo $user->origem_cidade; ?><br />
                        </td>
                        
                        <td>
                        
                            <?php $cap_name = "{$wpdb->prefix}capabilities"; if (array_key_exists('artista', $user->$cap_name)) : ?>
                            
                            Valores informados:<br/>
                            País: <?php echo $user->banda_pais; ?><br />
                            Estado: <?php echo $user->banda_estado; ?><br />
                            Cidade: <?php echo $user->banda_cidade; ?><br />
                            
                            <?php else : ?>
                            ---
                            <?php endif; ?>
                        
                        </td>
                    </tr>
                    
                
                <?php endforeach; ?>
                </table>
            
            <?php else : ?>
            
                Nenhum usuário com cadastro incompleto
            
            <?php endif; ?>
			
            
		</div>
	<?php
}
