<?php 

global $current_user;

if(isset($_POST['_wpnonce']) &&  wp_verify_nonce($_POST['_wpnonce'], 'join_event' ) ){
    if(!in_postmeta(get_post_meta($_POST['evento_id'], 'inscrito'), $_POST['banda_id'])){
        add_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);
        
        $to = get_post_meta($_POST['evento_id'] , 'evento_recipient', true);
        if(!$to)
            $to  =  get_bloginfo('admin_email');
                    
        $banda = get_userdata($_POST['banda_id']);
        
        $event = get_post($_POST['evento_id']);
        $event_name = $event->post_title; 
    
        $msg = "A banda {$banda->banda} se inscreveu no evento {$event_name}.\n\n";
        $msg.= "Acesse o perfil da banda em :\n\n ". get_author_posts_url($banda->ID) . "\n\n";
        
        wp_mail($to, 'Nova Incrição - ' . $event_name, $msg );
        
        
    }        
        
}elseif(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'select_band' ) ){
    delete_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);
    if(!in_postmeta(get_post_meta($_POST['evento_id']), 'selecionado', $_POST['banda_id']))
        add_post_meta($_POST['evento_id'], 'selecionado', $_POST['banda_id']);
}
elseif(isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'unselect_band' ) ){
    delete_post_meta($_POST['evento_id'], 'selecionado', $_POST['banda_id']);
    add_post_meta($_POST['evento_id'], 'inscrito', $_POST['banda_id']);    
}

//var_dump($current_user);

get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
  <?php if ( have_posts() ) : the_post(); ?>
    <div class="item green">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h1><?php the_title(); ?></h1>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>

    <div class="post">        
      
       <?php get_template_part('type-evento', 'block'); ?>
      
    </div>

    <div class="item yellow">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h3>Artistas Selecionados</h3>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>    

    <div class="selected-artists">
      <?php
          $inscritos = get_post_meta( get_the_ID(), 'selecionado') ;
              
          foreach($inscritos as $banda_id){
              $banda = get_userdata($banda_id);
              include('evento-banda-block.php'); 
          }
      ?>
    </div>

    <div class="item yellow">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h3>Artistas Inscritos</h3>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>    

    <div class="selected-artists">
      <?php
          $inscritos = get_post_meta( get_the_ID(), 'inscrito') ;
              
          foreach($inscritos as $banda_id){
              $banda = get_userdata($banda_id);
              include('evento-banda-block.php'); 
          }
      ?>
	</div>
    <!-- .post -->
    <div id="posts-navigation">
      <?php previous_post_link('<div id="anterior">%link</div>','Evento anterior', true); ?>
      <?php next_post_link('<div id="proximo">%link</div>', 'Próximo evento', true); ?>            
    </div><!-- #posts-navigation -->
    <?php endif; ?>

    
</div>
<?php get_sidebar("blog"); ?>
<?php get_footer(); ?>
