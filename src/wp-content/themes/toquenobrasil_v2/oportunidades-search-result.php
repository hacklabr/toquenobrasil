<?php 
global $wp_query, $evento_list_item_id;

$oportunidadesID = get_oportunidades_search_results();

if(!$oportunidadesID)
	$oportunidadesID = array('-1');

$perpage = get_theme_option('tnb_eventos_rows') ? get_theme_option('tnb_eventos_rows') : 5;

$perpage = $perpage*2; // mostrar 2 eventos por linha
 

$q = array(
	'post_type' => 'eventos',
	'post__in' => $oportunidadesID,
	'meta_key' => 'evento_inicio', 
	'orderby' => 'meta_value',
	'order' => 'DESC',
	'showposts' => $perpage,
	'paged' => $wp_query->get('paged') 
);

query_posts($q);
if(count($oportunidadesID) == 1 && $oportunidadesID[0] == '-1')
    $list_title = "Nenhuma oportunidade encontrada";
elseif(count($oportunidadesID) == 1)
    $list_title = 'Resultado da pesquisa: %s oportunidade encontrada.';
else
    $list_title = 'Resultado da pesquisa: %s oportunidades encontradas.';

?>
	<h2 class="title"><?php echo sprintf(__($list_title,'tnb'),count($oportunidadesID)); ?></h2>
	
	<div class="clear"></div>
	<section id="results" >
        
        <?php if ( have_posts() ):  ?>
            <?php while (have_posts()): the_post(); $evento_list_item_id = get_the_ID(); $impar = $impar ? false : true;?>
            
                
	      		<?php get_template_part('oportunidades-list-item');?>
            
                <?php if(!$impar):?> 
                    <div class='clear'></div>
                <?php endif;?>
            
	        <?php endwhile;?>
        <?php endif;?>    
        
        
		
    </section>
     <!-- #results -->
   

    
    <div class="clear"></div>
    
  	<div class="navigation clearfix">
  		<?php previous_posts_link('<div class="left-navigation alignleft">Próximos eventos</div>'); ?>
		<?php next_posts_link('<div class="right-navigation alignright">Eventos anteriores</div>'); ?>            
	</div> 
    
    
    <?php if(false and isset($list)): ?>
    
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
    <!-- .navigation -->