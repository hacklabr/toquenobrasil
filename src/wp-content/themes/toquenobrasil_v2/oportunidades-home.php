 <?php 
 global $evento_list_item_id;
 $limit = get_theme_option('tnb_eventos_rows');
 $limit = intval($limit) > 0 ? intval($limit) : 5; 
 
 // TODO: Essa query não está ordenando direito, apesar de aparentemente estar certa
 // mysql pra debug: SELECT SQL_CALC_FOUND_ROWS  wp_posts.ID, wp_postmeta.meta_value FROM wp_posts  JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id)  WHERE 1=1  AND wp_posts.post_parent = 0  AND wp_posts.post_type = 'eventos' AND (wp_posts.post_status = 'publish') AND wp_postmeta.meta_key = '_views' AND wp_postmeta.meta_value >= '1'  GROUP BY wp_posts.ID ORDER BY wp_postmeta.meta_value DESC LIMIT 0, 5;
 $mais_acessados = get_posts(array(
    'post_type' => 'eventos',
    'post_parent' => 0,
    'orderby' => 'meta_value',
    'order' => 'DESC', 
    'meta_key' => '_views',
    'meta_value' => 1,
    'meta_compare' => '>=',
    'limit' => $limit
    ));
    
    
 
 $ultimos_cadastrados = get_posts('post_type=eventos&post_parent=0&orderby=post_date&order=DESC&limit='.$limit);

 ?>
 <section id="more-accessed">
        <h2 class="title">mais acessados</h2>
        <?php foreach($mais_acessados as $evento) : $evento_list_item_id = $evento->ID;?>
            <?php get_template_part('oportunidades-list-item');?>
        <?php endforeach;?>
    </section>
    <!-- #more-accessed -->
        
    <section id="more-recent">
        <h2 class="title">mais recentes</h2>
        <?php foreach($ultimos_cadastrados as $evento) : $evento_list_item_id = $evento->ID;?>
            <?php get_template_part('oportunidades-list-item');?>
        <?php endforeach;?>
    </section>
    <!-- #more-recent -->
