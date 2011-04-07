<?php

global $wp_query, $tnb_users, $user;

switch ( $wp_query->query_vars['reg_type'] ) {
    case 'artistas':
        $title = 'Artistas';
        $role = 'artista';
    break;

    case 'produtores':
        $title = 'Produtores';
        $role = 'produtor';
    break;

    default:
        $title = 'Universo TNB';
        $role = '';
    break;
}

if ( isset($_GET['tnb_action']) && $_GET['tnb_action'] == 'tnb_busca_usuarios' ) {
    $result = get_users_search_result();
    
    $list = new ControlledList($result, $wp_query->get('paged'), TNB_USERS_COLS * get_theme_option('tnb_users_rows'));

    $tnb_users = $list->getPageSlice();
    
    $role = $_GET['user_type'] ? $_GET['user_type'] : 'usuários'; 
    $list_title = 'Resultado da pesquisa: '.$list->found.' '.$role.' encontrados.';
} else {
    $list_title = 'Últimos Cadastrados';
    $num_rows = intval($num_rows) > 0 ? intval($num_rows) : 2; 
    $limit = TNB_USERS_COLS * $num_rows;	
    $tnb_users = get_ultimos_cadastrados(get_theme_option('tnb_users_rows'), $role);
    $list = new ControlledList($tnb_users, $wp_query->get('paged'), TNB_USERS_COLS * get_theme_option('tnb_users_rows'));
}

$row_count = 0;

?>

<?php get_header(); ?>

<section id="users" class="grid_11 clearfix box-shadow">
    <h1 class="title"><?php _e($title, "tnb"); ?></h1>

    <?php get_template_part('users-search-form');?>

    <section id="results">
        <h2 class="title"><?php echo $list_title; ?></h2>
	    
	    <?php if(count($tnb_users) > 0): ?>
 		<?php foreach($tnb_users as $user): $row_count++;?>
	    
		   	<?php if($row_count == 1):?>
		    <div class="clear"></div>
	        <div class="row clearfix">
		    <?php endif;?>
		    	<div id="<?php echo $user->user_nicename; ?>" class="user">
			<?php if(is_int(strpos($user->wp_capabilities, 'artista'))): // artista?>
				<?php get_template_part('users-list-item-artista'); ?>
	            
		    <?php else: // produtor ?>
		    	<?php get_template_part('users-list-item-produtor'); ?>
		    <?php endif;?>
		    	</div>
	            
	        <?php if($row_count == TNB_USERS_COLS): $row_count = 0;?>
	        <div class='clear'></div>
	        </div> <!-- .row -->
	        <?php endif;?>
	            
        <?php endforeach;?>
       
        <?php if($row_count > 0): // se não deu um número exato?>
        	</div> <!-- .row -->
        <?php endif;?>
        
	<?php else: ?>
		<p class="text-center"><?php echo _e('Nenhum resultado encontrado', "tnb"); ?></p>
	<?php endif;?> 
	    
    	</section>
    <!-- #signedup-recently -->
    <?php if(isset($list)): ?>
    
      <div class="navigation clearfix">
        <div class="left-navigation alignleft">
            <?php $list->previous_link('Anterior'); ?>
        </div>
        <!-- .left-navigation -->
        <div class="right-navigation alignright">
            <?php $list->next_link('Próximo'); ?>
        </div>
        <!-- .right-navigation -->
    </div>
    <!-- .navigation -->
	                
	    
	<?php endif;?>
</section>
<!-- #users -->
    

<?php get_sidebar(); ?>
<?php get_footer(); ?>
