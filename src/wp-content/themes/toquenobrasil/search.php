<?php

if ($_GET['tipo_busca'] == 'eventos') {
    
    global $wpdb, $paged;
    $searchString = '%' . get_query_var('s') . '%';
    $eventos_search = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type = 'eventos' AND (post_title LIKE %s OR post_content LIKE %s)", $searchString, $searchString);
    
    $eventos_ids = $wpdb->get_col($eventos_search);
    
    if (sizeof($eventos_ids) < 1) 
        $eventos_ids = array(0);
    
    add_filter('parse_query', 'tnb_set_issearch');
    
    function tnb_set_issearch(&$query) {
        $query->is_search = true;
    }
    
    query_posts(array(
        'post_type' => 'eventos',
        'post__in' => $eventos_ids,
        'paged' => $paged
    ));
    
    include('type-eventos.php');
    exit;
    
} elseif ($_GET['tipo_busca'] == 'artistas') {

    include('list-artistas.php');
    exit;

}

get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
    <div id="artists-title" class="item green clearfix">
        <div class="title pull-1 clearfix">
            <div class="shadow"></div>
            <h1><?php _e('Busca','tnb'); ?></h1>
        </div>
    </div>

    <p id="intro">
        Resultado da busca por "<?php echo $_GET['s']; ?>"
    </p>


    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    
        <div class="post">
            <div class="post-time span-14">
                <div class="shadow"></div>
                <div class="data"><div class="dia"><?php the_time("d"); ?></div><div class="mes-ano"><?php the_time("m/Y");?></div></div>
            </div>
            <div class="span-2 post-comments">
                <span><?php comments_number("0", "1", "%"); ?></span>
            </div>
            <div class="span-12 last">
                <h2 class="span-10">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="clear"></div>
                <div class="post-categories">
                    <?php the_category(' ') ?>
                </div>
                <div class="clear"></div>
                <?php if (is_single() || is_page()):?>
                    <?php the_content();?>
                <?php else:?>
                    <?php the_excerpt(); ?>
                <?php endif;?>
                <div class="clear"></div>
                <div class="post-tags">
                    <?php the_tags(" "," "," "); ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
      
    <?php endwhile;?>
		<div id="posts-navigation">
        	<?php previous_posts_link('<span id="anteriores"><span>Próximos posts</span></span>'); ?>
            <?php next_posts_link('<span id="proximos"><span>Posts anteriores</span></span>'); ?>            
        </div><!-- .navigation -->
	
	<?php else: ?>
        <div class="span-12 last">
            <h2 class="span-10">
            Não encontrado
            </h2>
        </div>
	
	<?php endif; ?>
  
</div>

<div class="span-8 last">
    <div  class='widgets'>
        <?php dynamic_sidebar("blog-sidebar");?>
    </div>
</div>

<?php get_footer(); ?>

