
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
        'meta_value' => 'no'
    );
    $subevents = query_posts($query_args);
?>

<?php 
    while(have_posts()):
        the_post(); 
        
        if(get_post_meta(get_the_ID(), 'aprovado_para_superevento')
            || $current_user->ID == get_the_author_ID() 
            || $current_user->ID == $superevent_owner_id):
?>
            <div id="event-<?php echo the_ID(); ?>-content" class="subevent clearfix">
                <h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
                <?php include('evento-list-item.php'); ?>
            </div>
<?php
        endif;
    endwhile;
?>
