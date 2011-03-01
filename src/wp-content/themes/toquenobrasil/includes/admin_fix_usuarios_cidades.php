<?php
add_action('admin_menu', 'fix_usuarios_cidades_add_page');
wp_enqueue_script('ui-core');
wp_enqueue_script('ui-effects');
function fix_usuarios_cidades_add_page() {
    add_users_page(__("Cadastro incompleto"), __("Cadastro incompleto"), "manage_options", "fix_usuarios_cidades", "fix_usuarios_cidades_page");
}


function fix_usuarios_cidades_page() {
	global $wpdb;
    
    $estados = get_estados();
	$paises = get_paises();
	
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

var trs_originais = [];

function tnb_abreFormularioLocalidadeArtista(userId, origem_pais, origem_estado, origem_cidade, banda_pais, banda_estado, banda_cidade, display_name){
	var tr = jQuery('#tr-user-'+userId);
	
	trs_originais[userId] = tr.html();
	
	var formHTML = jQuery('#template-form-artista').html();
	
	
	while(formHTML.indexOf('{usuario_id}') != -1)
		formHTML = formHTML.replace('{usuario_id}',userId);
	
	while(formHTML.indexOf('{origem_pais}') != -1)
		formHTML = formHTML.replace('{origem_pais}',origem_estado);
		
	while(formHTML.indexOf('{origem_estado}') != -1)
		formHTML = formHTML.replace('{origem_estado}',origem_estado);
	
	while(formHTML.indexOf('{origem_cidade}') != -1)
		formHTML = formHTML.replace('{origem_cidade}',origem_cidade);
	
	while(formHTML.indexOf('{banda_pais}') != -1)
		formHTML = formHTML.replace('{banda_pais}',origem_estado);
		
	while(formHTML.indexOf('{banda_estado}') != -1)
		formHTML = formHTML.replace('{banda_estado}',banda_estado);
		
	while(formHTML.indexOf('{banda_cidade}') != -1)
		formHTML = formHTML.replace('{banda_cidade}',banda_cidade);
	
	while(formHTML.indexOf('{display_name}') != -1)
		formHTML = formHTML.replace('{display_name}',display_name);
	
	tr.html('<td colspan="5">'+formHTML+'</td>');
	
	jQuery('#origem_pais_'+userId).val(origem_pais);
	jQuery('#banda_pais_'+userId).val(origem_pais);
	
	if(origem_pais == 'BR'){
		jQuery('#origem_estado_select_'+userId).show();
		jQuery('#origem_estado_input_'+userId).hide();
		jQuery('#origem_estado_select_'+userId).val(origem_estado);
		
		jQuery('#origem_cidade_select_'+userId).show();
		jQuery('#origem_cidade_input_'+userId).hide();
		jQuery('#origem_cidade_select_'+userId).val(origem_cidade);
	}else{
		jQuery('#origem_estado_select_'+userId).hide();
		jQuery('#origem_estado_input_'+userId).show();
		jQuery('#origem_estado_input_'+userId).val(origem_estado);
		
		jQuery('#origem_cidade_select_'+userId).hide();
		jQuery('#origem_cidade_input_'+userId).show();
		jQuery('#origem_cidade_input_'+userId).val(origem_cidade);
	}
	if(banda_pais == 'BR'){
		jQuery('#banda_estado_select_'+userId).show();
		jQuery('#banda_estado_input_'+userId).hide();
		jQuery('#banda_estado_select_'+userId).val(banda_estado);
		
		jQuery('#banda_cidade_select_'+userId).show();
		jQuery('#banda_cidade_input_'+userId).hide();
		jQuery('#banda_cidade_select_'+userId).val(banda_cidade);
	}else{
		jQuery('#banda_estado_select_'+userId).hide();
		jQuery('#banda_estado_input_'+userId).show();
		jQuery('#banda_estado_input_'+userId).val(banda_estado);
		
		jQuery('#banda_cidade_select_'+userId).hide();
		jQuery('#banda_cidade_input_'+userId).show();
		jQuery('#banda_cidade_input_'+userId).val(banda_cidade);
		
	}
	


	jQuery('#origem_pais_'+userId).change(function(){
        if(jQuery('#origem_pais_'+userId).val() == 'BR') {
            jQuery('#origem_estado_select_'+userId).show();
            jQuery('#origem_estado_input_'+userId).hide();

            jQuery('#origem_cidade_select_'+userId).show();
            jQuery('#origem_cidade_input_'+userId).hide();

            jQuery('#origem_estado').val(jQuery('#origem_estado_select_'+userId).val());
            jQuery('#origem_estado').val(jQuery('#origem_estado_select_'+userId).val());
        }else{
            jQuery('#origem_estado_select_'+userId).hide();
            jQuery('#origem_estado_input_'+userId).show();

            jQuery('#origem_cidade_select_'+userId).hide();
            jQuery('#origem_cidade_input_'+userId).show();

            jQuery('#origem_estado').val(jQuery('#origem_estado_input_'+userId).val());
            jQuery('#origem_estado').val(jQuery('#origem_estado_input_'+userId).val());
            jQuery('#origem_cidade_'+userId).val(jQuery('#origem_cidade_input_'+userId).val());
        }
     });

     jQuery('#origem_estado_select_'+userId).change(function(){
         jQuery('#origem_cidade_select_'+userId).html('<option>carregando...</option>');
         jQuery('#origem_estado').val(jQuery('#origem_estado_select_'+userId).val());
         tnbCarregaCidadesOptions('origem_cidade',userId,jQuery('#origem_estado_select_'+userId).val());
     });

     jQuery('#origem_estado_input_'+userId).change(function(){
         jQuery('#origem_estado').val(jQuery('#origem_estado_input_'+userId).val());
     });

     jQuery('#origem_cidade_select_'+userId).change(function(){
         jQuery('#origem_cidade_'+userId).val(jQuery('#origem_cidade_select_'+userId).val());
     });

     jQuery('#origem_cidade_input_'+userId).change(function(){
         jQuery('#origem_cidade_'+userId).val(jQuery('#origem_cidade_input_'+userId).val());
     });
     
     
     if(jQuery('#origem_pais_'+userId).val() == 'BR'){
       tnbCarregaCidadesOptions('origem_cidade',userId,jQuery('#origem_estado_select_'+userId).val());
     }


	jQuery('#banda_pais_'+userId).change(function(){
        if(jQuery('#banda_pais_'+userId).val() == 'BR') {
            jQuery('#banda_estado_select_'+userId).show();
            jQuery('#banda_estado_input_'+userId).hide();

            jQuery('#banda_cidade_select_'+userId).show();
            jQuery('#banda_cidade_input_'+userId).hide();

            jQuery('#banda_estado').val(jQuery('#banda_estado_select_'+userId).val());
            jQuery('#banda_estado').val(jQuery('#banda_estado_select_'+userId).val());
        }else{
            jQuery('#banda_estado_select_'+userId).hide();
            jQuery('#banda_estado_input_'+userId).show();

            jQuery('#banda_cidade_select_'+userId).hide();
            jQuery('#banda_cidade_input_'+userId).show();

            jQuery('#banda_estado').val(jQuery('#banda_estado_input_'+userId).val());
            jQuery('#banda_estado').val(jQuery('#banda_estado_input_'+userId).val());
            jQuery('#banda_cidade_'+userId).val(jQuery('#banda_cidade_input_'+userId).val());
        }
     });

     jQuery('#banda_estado_select_'+userId).change(function(){
         jQuery('#banda_cidade_select_'+userId).html('<option>carregando...</option>');
         jQuery('#banda_estado').val(jQuery('#banda_estado_select_'+userId).val());
         tnbCarregaCidadesOptions('banda_cidade',userId,jQuery('#banda_estado_select_'+userId).val());
     });

     jQuery('#banda_estado_input_'+userId).change(function(){
         jQuery('#banda_estado').val(jQuery('#banda_estado_input_'+userId).val());
     });

     jQuery('#banda_cidade_select_'+userId).change(function(){
         jQuery('#banda_cidade_'+userId).val(jQuery('#banda_cidade_select_'+userId).val());
     });

     jQuery('#banda_cidade_input_'+userId).change(function(){
         jQuery('#banda_cidade_'+userId).val(jQuery('#banda_cidade_input_'+userId).val());
     });
     
     
     if(jQuery('#banda_pais_'+userId).val() == 'BR'){
       tnbCarregaCidadesOptions('banda_cidade',userId,jQuery('#banda_estado_select_'+userId).val());
     }
     
     jQuery('#form-local-'+userId).submit(function(){
		
		if(jQuery("#origem_pais_"+userId).val() == "BR"){
			 jQuery("#origem_estado_"+userId).val(jQuery("#origem_estado_select_"+userId).val());
			 jQuery("#origem_cidade_"+userId).val(jQuery("#origem_cidade_select_"+userId).val());
		}else{
			 jQuery("#origem_estado_"+userId).val(jQuery("#origem_estado_input_"+userId).val());
			 jQuery("#origem_cidade_"+userId).val(jQuery("#origem_cidade_input_"+userId).val());
		}
		
		if(jQuery("#banda_pais_"+userId).val() == "BR"){
			 jQuery("#banda_estado_"+userId).val(jQuery("#banda_estado_select_"+userId).val());
			 jQuery("#banda_cidade_"+userId).val(jQuery("#banda_cidade_select_"+userId).val());
		}else{
			 jQuery("#banda_estado_"+userId).val(jQuery("#banda_estado_input_"+userId).val());
			 jQuery("#banda_cidade_"+userId).val(jQuery("#banda_cidade_input_"+userId).val());
		}
		 jQuery.post("<?php echo get_bloginfo('stylesheet_directory');?>/admin_fix_usuarios_cidades_post.php", jQuery(this).serialize(), function(value){
			if(value == 'OK')
				tr.hide();
			else
				alert(value);
	      });
		 return false;
	 });
}



function tnb_abreFormularioLocalidadeProdutor(userId, origem_pais, origem_estado, origem_cidade, display_name){
	var tr = jQuery('#tr-user-'+userId);
	trs_originais[userId] = tr.html();
	
	var formHTML = jQuery('#template-form-produtor').html();
	
	while(formHTML.indexOf('{usuario_id}') != -1)
		formHTML = formHTML.replace('{usuario_id}',userId);
	
	while(formHTML.indexOf('{origem_pais}') != -1)
		formHTML = formHTML.replace('{origem_pais}',origem_estado);
		
	while(formHTML.indexOf('{origem_estado}') != -1)
		formHTML = formHTML.replace('{origem_estado}',origem_estado);
	
	while(formHTML.indexOf('{origem_cidade}') != -1)
		formHTML = formHTML.replace('{origem_cidade}',origem_cidade);
	
	while(formHTML.indexOf('{display_name}') != -1)
		formHTML = formHTML.replace('{display_name}',display_name);
	
	tr.html('<td colspan="5">'+formHTML+'</td>');
	
	jQuery('#origem_pais_'+userId).val(origem_pais);
	
	
	if(origem_pais == 'BR'){
		jQuery('#origem_estado_select_'+userId).show();
		jQuery('#origem_estado_input_'+userId).hide();
		jQuery('#origem_estado_select_'+userId).val(origem_estado);
		
		jQuery('#origem_cidade_select_'+userId).show();
		jQuery('#origem_cidade_input_'+userId).hide();
		jQuery('#origem_cidade_select_'+userId).val(origem_cidade);
	}else{
		jQuery('#origem_estado_select_'+userId).hide();
		jQuery('#origem_estado_input_'+userId).show();
		jQuery('#origem_estado_input_'+userId).val(origem_estado);
		
		jQuery('#origem_cidade_select_'+userId).hide();
		jQuery('#origem_cidade_input_'+userId).show();
		jQuery('#origem_cidade_input_'+userId).val(origem_cidade);
	}
	
	jQuery('#origem_pais_'+userId).change(function(){
        if(jQuery('#origem_pais_'+userId).val() == 'BR') {
            jQuery('#origem_estado_select_'+userId).show();
            jQuery('#origem_estado_input_'+userId).hide();

            jQuery('#origem_cidade_select_'+userId).show();
            jQuery('#origem_cidade_input_'+userId).hide();

            jQuery('#origem_estado').val(jQuery('#origem_estado_select_'+userId).val());
            jQuery('#origem_estado').val(jQuery('#origem_estado_select_'+userId).val());
        }else{
            jQuery('#origem_estado_select_'+userId).hide();
            jQuery('#origem_estado_input_'+userId).show();

            jQuery('#origem_cidade_select_'+userId).hide();
            jQuery('#origem_cidade_input_'+userId).show();

            jQuery('#origem_estado').val(jQuery('#origem_estado_input_'+userId).val());
            jQuery('#origem_estado').val(jQuery('#origem_estado_input_'+userId).val());
            jQuery('#origem_cidade_'+userId).val(jQuery('#origem_cidade_input_'+userId).val());
        }
     });

     jQuery('#origem_estado_select_'+userId).change(function(){
         jQuery('#origem_cidade_select_'+userId).html('<option>carregando...</option>');
         jQuery('#origem_estado_'+userId).val(jQuery('#origem_estado_select_'+userId).val());
         tnbCarregaCidadesOptions('origem_cidade',userId,jQuery('#origem_estado_select_'+userId).val());
     });

     jQuery('#origem_estado_input_'+userId).change(function(){
         jQuery('#origem_estado_'+userId).val(jQuery('#origem_estado_input_'+userId).val());
     });

     jQuery('#origem_cidade_select_'+userId).change(function(){
         jQuery('#origem_cidade_'+userId).val(jQuery('#origem_cidade_select_'+userId).val());
     });

     jQuery('#origem_cidade_input_'+userId).change(function(){
         jQuery('#origem_cidade_'+userId).val(jQuery('#origem_cidade_input_'+userId).val());
     });
     
     
     if(jQuery('#origem_pais_'+userId).val() == 'BR'){
       tnbCarregaCidadesOptions('origem_cidade',userId,jQuery('#origem_estado_select_'+userId).val());
     }
    
     jQuery('#form-local-'+userId).submit(function(){
		if(jQuery("#origem_pais_"+userId).val() == "BR"){
			 jQuery("#origem_estado_"+userId).val(jQuery("#origem_estado_select_"+userId).val());
			 jQuery("#origem_cidade_"+userId).val(jQuery("#origem_cidade_select_"+userId).val());
		}else{
			 jQuery("#origem_estado_"+userId).val(jQuery("#origem_estado_input_"+userId).val());
			 jQuery("#origem_cidade_"+userId).val(jQuery("#origem_cidade_input_"+userId).val());
		}
		
			
		 jQuery.post("<?php echo get_bloginfo('stylesheet_directory');?>/admin_fix_usuarios_cidades_post.php", jQuery(this).serialize(), function(value){
			if(value == 'OK')
				tr.hide();
			else
				alert(value);
	      });
		 return false;
	 });
}


function tnb_fechaFormulario(userId){
	jQuery('#tr-user-'+userId).html(trs_originais[userId]);
}
//-->
</script>
<div id='template-form-artista' style='display:none'>
	<h3>{display_name}</h3>
	<form id='form-local-{usuario_id}'>
		<input type='hidden' name='user_id' value='{usuario_id}' />
		<input type='hidden' name='action' value='user.update.local' />
		<input type='hidden' name='capability' value='artista' />
		<div class='float-left' style='float:left; margin-right:20px;'>
			<div>
			<label>
				<strong><?php _e('País de origem', 'tnb');?></strong><br />
				<select id='origem_pais_{usuario_id}' name="origem_pais">
				 <?php
                    foreach($paises as $sigla=>$name){
                        echo "<option value='$sigla'>$name</option>";
                    }
                ?>
				</select>
			</label>
			</div>
			<div>
			<label><strong><?php _e('Estado de origem', 'tnb');?></strong><br />
				<select class="span-6 text" id="origem_estado_select_{usuario_id}" name="origem_estado_select" >
					 <?php
						foreach($estados as $uf=>$name){
							echo "<option value='$uf'>$name</option>";
						}
					?>
				</select>
				<input class="span-6 text" type="text" id="origem_estado_input_{usuario_id}" name="origem_cidade" value="" />
				<input type="hidden" id="origem_estado_{usuario_id}" name="origem_estado" value="" />
			</label>
			</div>
			<div>
			<label><strong><?php _e('Cidade de origem', 'tnb');?></strong><br />
				<select class="span-6 text" id="origem_cidade_select_{usuario_id}" name="origem_cidade_select" ></select>
				<input class="span-6 text" type="text" id="origem_cidade_input_{usuario_id}" name="origem_cidade_input" value="" />
				<input type="hidden" name="origem_cidade" id="origem_cidade_{usuario_id}" value=""/>
			</label>
			</div>
		</div>
		<div class='float-left' style='float:left; margin-right:20px;'>
			<div>
			<label>
				<strong><?php _e('País de residência', 'tnb');?></strong><br />
				<select id='banda_pais_{usuario_id}' name="banda_pais">
				 <?php
                    foreach($paises as $sigla=>$name){
                        echo "<option value='$sigla'>$name</option>";
                    }
                ?>
				</select>
			</label>
			</div>
			<div>
			<label><strong><?php _e('Estado de residência', 'tnb');?></strong><br />
				<select class="span-6 text" id="banda_estado_select_{usuario_id}" name="banda_estado_select" >
					 <?php
						foreach($estados as $uf=>$name){
							echo "<option value='$uf'>$name</option>";
						}
					?>
				</select>
				<input class="span-6 text" type="text" id="banda_estado_input_{usuario_id}" name="banda_cidade" value="{banda_estado}" />
				<input type="hidden" id="banda_estado_{usuario_id}" name="banda_estado" value="" />
			</label>
			</div>
			<div>
			<label><strong><?php _e('Cidade de residência', 'tnb');?></strong><br />
				<select class="span-6 text" id="banda_cidade_select_{usuario_id}" name="banda_cidade_select" ></select>
				<input class="span-6 text" type="text" id="banda_cidade_input_{usuario_id}" name="banda_cidade_input" value="{banda_cidade}" />
				<input type="hidden" name="banda_cidade" id="banda_cidade_{usuario_id}" value=""/>
			</label>
			</div>
		</div>
		<input type='submit' value='salvar'> <input type='button' value='cancelar' onclick="tnb_fechaFormulario('{usuario_id}');" />
	</form>
	
</div>

<div id='template-form-produtor' style='display:none'>
	<h3>{display_name}</h3>
	<form id='form-local-{usuario_id}'>
		<input type='hidden' name='user_id' value='{usuario_id}' />
		<input type='hidden' name='action' value='user.update.local' />
		<input type='hidden' name='capability' value='produtor' />
		<div class='float-left' style='float:left; margin-right:20px;'>
			<div>
			<label>
				<strong><?php _e('País de origem', 'tnb');?></strong><br />
				<select id='origem_pais_{usuario_id}' name="origem_pais">
				 <?php
                    foreach($paises as $sigla=>$name){
                        echo "<option value='$sigla'>$name</option>";
                    }
                ?>
				</select>
			</label>
			</div>
			<div>
			<label><strong><?php _e('Estado de origem', 'tnb');?></strong><br />
				<select class="span-6 text" id="origem_estado_select_{usuario_id}" name="origem_estado_select" >
					 <?php
						foreach($estados as $uf=>$name){
							echo "<option value='$uf'>$name</option>";
						}
					?>
				</select>
				<input class="span-6 text" type="text" id="origem_estado_input_{usuario_id}" name="origem_cidade" value="" />
				<input type="hidden" id="origem_estado_{usuario_id}" name="origem_estado" value="{origem_estado}" />
			</label>
			</div>
			<div>
			<label><strong><?php _e('Cidade de origem', 'tnb');?></strong><br />
				<select class="span-6 text" id="origem_cidade_select_{usuario_id}" name="origem_cidade_select" ></select>
				<input class="span-6 text" type="text" id="origem_cidade_input_{usuario_id}" name="origem_cidade_input" value="" />
				<input type="hidden" name="origem_cidade" id="origem_cidade_{usuario_id}" value="{origem_cidade}"/>
			</label>
			</div>
		</div>
		<input type='submit' value='salvar'> <input type='button' value='cancelar' onclick="tnb_fechaFormulario('{usuario_id}');" />
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
                        	<?php if(isset($user->wp_capabilities['artista'])):?>
                        		<a href='javascript:void(0)' onclick="tnb_abreFormularioLocalidadeArtista('<?php echo $user->ID?>','<?php echo $user->origem_pais?>','<?php echo $user->origem_estado?>','<?php echo $user->origem_cidade?>','<?php echo $user->banda_pais?>','<?php echo $user->banda_estado?>','<?php echo $user->banda_cidade?>', '<?php echo $user->display_name?>');" >editar</a>
                        	<?php else:?>
                        		<a href='javascript:void(0)' onclick="tnb_abreFormularioLocalidadeProdutor('<?php echo $user->ID?>','<?php echo $user->origem_pais?>','<?php echo $user->origem_estado?>','<?php echo $user->origem_cidade?>', '<?php echo $user->display_name?>');" >editar</a>
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
