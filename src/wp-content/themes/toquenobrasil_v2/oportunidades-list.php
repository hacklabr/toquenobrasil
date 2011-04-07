<?php
/*
    Template Name: Listagem de Oportunidades
*/


?>

<?php get_header(); ?>

<section id="opportunities" class="grid_11 box-shadow clearfix">
    <h1 class="title"><?php _e("Oportunidades", "tnb"); ?></h1>

	<?php get_template_part('oportunidades-search-form');?>
        
    <?php if(isset($_GET['tnb_action']) && $_GET['tnb_action'] == 'tnb_busca_oportunidades'): ?>
    	<?php get_template_part('oportunidades-search-result');?>
    <?php else:?>
    	<?php get_template_part('oportunidades-home');?>
    <?php endif; ?>    
   

</section>
<!-- #opportunities -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>