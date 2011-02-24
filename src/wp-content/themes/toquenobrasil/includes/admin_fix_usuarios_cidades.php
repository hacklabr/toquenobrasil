<?php
add_action('admin_menu', 'fix_usuarios_cidades_add_page');
wp_enqueue_script('ui-core');
wp_enqueue_script('ui-effects');
function fix_usuarios_cidades_add_page() {
    add_users_page(__("Cadastro incompleto"), __("Cadastro incompleto"), "manage_options", "fix_usuarios_cidades", "fix_usuarios_cidades_page");
}


function fix_usuarios_cidades_page() {
	global $wpdb;
    
    $users = $wpdb->get_col("SELECT $wpdb->users.ID FROM $wpdb->users, $wpdb->usermeta WHERE $wpdb->usermeta.user_id = $wpdb->users.ID AND $wpdb->usermeta.meta_key = 'wp_capabilities' AND ($wpdb->usermeta.meta_value LIKE '%artista%' OR $wpdb->usermeta.meta_value LIKE '%produtor%')");
    
    foreach ($users as $u){
      $user = get_userdata($u);
      if(!tnb_contatoUsuarioCorreto($user)){
        
        $usuariosInclompletos[] = $user;
      }
    }
    ?>
<script type="text/javascript">
<!--
function tnb_abreFormularioLocalidadeArtista(userId, origem_pais, origem_estado, origem_cidade, banda_pais, banda_estado, banda_cidade){
	var tr = jQuery('#tr-user-'+userId);
	var formHTML = jQuery('#template-form-artista').html();
	
	formHTML = formHTML.replace('{origem_pais}',origem_pais);
	formHTML = formHTML.replace('{origem_estado}',origem_estado);
	formHTML = formHTML.replace('{origem_cidade}',origem_cidade);
	
	formHTML = formHTML.replace('{banda_pais}',banda_pais);
	formHTML = formHTML.replace('{banda_estado}',banda_estado);
	formHTML = formHTML.replace('{banda_cidade}',banda_cidade);
	
	tr.html('<td colspan="5">'+formHTML+'</td>');
}

function tnb_abreFormularioLocalidadeProdutor(userId, origem_pais, origem_estado, origem_cidade){
	var tr = jQuery('#tr-user-'+userId);
	var formHTML = jQuery('#template-form-produtor').html();
	
	formHTML = formHTML.replace('{origem_pais}',origem_pais);
	formHTML = formHTML.replace('{origem_estado}',origem_estado);
	formHTML = formHTML.replace('{origem_cidade}',origem_cidade);
	
	
	tr.html('<td colspan="5">'+formHTML+'</td>');
}
//-->
</script>
<div id='template-form-artista' style='display:none'>
	<form>
		<div class='float-left' style='float:left; margin-right:20px; text-align:right;'>
			<label>país de origem: <input value='{origem_pais}'/></label><br />
			<label>estado de origem: <input value='{origem_estado}'/></label><br />
			<label>cidade de origem: <input  value='{origem_cidade}'/></label><br />
		</div>
		<div class='float-left' style='float:left; text-align:right;'>
			<label>país de residência: <input  value='{banda_pais}'/></label><br />
			<label>estado de residência: <input  value='{banda_estado}'/></label><br />
			<label>cidade de residência: <input  value='{banda_cidade}'/></label><br />
			<input type='submit' value='salvar'/>
		</div>
	</form>
</div>

<div id='template-form-produtor' style='display:none'>
	<form>
		<label>país de origem: <input value='{origem_pais}'/></label>
		<label>estado de origem: <input value='{origem_estado}'/></label>
		<label>cidade de origem: <input  value='{origem_cidade}'/></label>

		<input type='submit' value='salvar' />
	</form>
</div>
		<div class="wrap">
            <div class="icon32" id="icon-options-general"><br/></div>
            <h2>Usuários com cadastro de localidade incompleto (<?php echo sizeof($usuariosInclompletos)?>)</h2>

            <?php if (is_array($usuariosInclompletos) && sizeof($usuariosInclompletos) > 0): ?>
            	
                <table cellspacing="0" class="widefat fixed">
                
                    <tr class="thead">
                    
                        <th style="" class="manage-column" scope="col">Nome</th>
                        <th style="" class="manage-column" scope="col">E-mail</th>
                        <th style="" class="manage-column" scope="col">Origem</th>
                        <th style="" class="manage-column" scope="col">Residência</th>
                        <th>&nbsp;</th>
                    </tr>
                
                <?php foreach ($usuariosInclompletos as $user): ?>
                
                    
                    <tr id='tr-user-<?php echo $user->ID?>'>
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
                            País: <strong><?php echo $user->banda_pais; ?></strong><br />
                            Estado: <strong><?php echo $user->banda_estado; ?></strong><br />
                            Cidade: <strong><?php echo $user->banda_cidade; ?></strong><br />
                            
                            <?php else : ?>
                            ---
                            <?php endif; ?>
                        
                        </td>
                        <td>
                        	<?php var_dump($user->wp_capabilities); if(isset($user->wp_capabilities['artista'])):?>
                        		<a href='javascript:void(0)' onclick="tnb_abreFormularioLocalidadeArtista('<?php echo $user->ID?>','<?php echo $user->origem_pais?>','<?php echo $user->origem_estado?>','<?php echo $user->origem_cidade?>','<?php echo $user->banda_pais?>','<?php echo $user->banda_estado?>','<?php echo $user->banda_cidade?>');" >editar</a>
                        	<?php else:?>
                        		<a href='javascript:void(0)' onclick="tnb_abreFormularioLocalidadeProdutor('<?php echo $user->ID?>','<?php echo $user->origem_pais?>','<?php echo $user->origem_estado?>','<?php echo $user->origem_cidade?>');" >editar</a>
                        	<?php endif;?>
                    </tr>
                    
                
                <?php endforeach; ?>
                </table>
            
            <?php else : ?>
            
                Nenhum usuário com cadastro incompleto
            
            <?php endif; ?>
			
            
		</div>
	<?php
}
