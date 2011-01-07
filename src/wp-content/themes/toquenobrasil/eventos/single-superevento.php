<div id="event-<?php echo the_ID(); ?>-content" class="clearfix">
    <?php include('evento-list-item.php'); ?>
</div>

<div class="item yellow clearfix">
    <div class="title pull-1 clearfix">
        <div class="shadow"></div>
        <h3>Subeventos</h3>
    </div>
</div>

<?php
    // produtor do evento pai
    $superevent_owner_id = get_the_author_ID();

    // trazer subeventos
    $query_args = array(
        'post_type' => 'eventos',
        'post_parent' => get_the_id(),
        'meta_key' => 'superevento',
        'meta_value' => 'no',
        'numberposts' => -1
    );

    /* TODO: Encontrar forma mais elegante */
    $supress_condicoes = true;
    $supress_restricoes = true;

    $subevents = get_posts($query_args);
    foreach ($subevents as $sub) :
        
        if(get_post_meta($sub->ID, 'aprovado_para_superevento')
            || $current_user->ID == $sub->post_author 
            || $current_user->ID == $superevent_owner_id):
?>
            <div id="event-<?php echo $sub->ID; ?>-content" class="subevent clearfix">
                <h2><a href="<?php echo get_permalink($sub->ID); ?>" title="<?php echo $sub->post_title;?>"><?php echo $sub->post_title;?></a></h2>
                <?php $evento_list_item_id = $sub->ID;                 
                include('evento-list-item.php'); ?>
            </div>
<?php
        endif;
    endforeach;
?>
