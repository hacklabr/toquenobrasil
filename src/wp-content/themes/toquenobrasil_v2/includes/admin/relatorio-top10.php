<?php
global $wpdb;
$limit = $_GET['rel_perpage'] ? $_GET['rel_perpage'] : 10; 

// TOP ARTISTAS
$artistas = $wpdb->get_results("
SELECT 
	wp_users.*, 
	wp_usermeta.meta_value+0 AS _views 
FROM 
	wp_users, 
	wp_usermeta 
WHERE 
	wp_users.id = wp_usermeta.user_id AND 
	wp_usermeta.meta_key = '_views' AND
	{$wpdb->users}.id IN (SELECT 
							user_id 
						  FROM 
	                        {$wpdb->usermeta} 
	                      WHERE 
	                      	meta_key='{$wpdb->prefix}capabilities' AND 
	                      	meta_value LIKE '%artista%')
	 
ORDER BY _views DESC 
LIMIT $limit");
 


// TOP PRODUTORES
$produtores = $wpdb->get_results("
SELECT 
    {$wpdb->users}.*, 
	{$wpdb->usermeta}.meta_value+0 AS _views 
FROM 
	{$wpdb->users}, 
	{$wpdb->usermeta} 
WHERE 
	{$wpdb->users}.id = {$wpdb->usermeta}.user_id AND 
	{$wpdb->usermeta}.meta_key = '_views' AND
	{$wpdb->users}.id IN (SELECT 
							user_id 
						  FROM 
	                        {$wpdb->usermeta} 
	                      WHERE 
	                      	meta_key='{$wpdb->prefix}capabilities' AND 
	                      	meta_value LIKE '%produtor%')
ORDER BY _views DESC 
LIMIT $limit");
 


// TOP PLAYS
$mplays = $wpdb->get_results("
SELECT 
    {$wpdb->posts}.*, 
	{$wpdb->postmeta}.meta_value+0 AS _plays,
	{$wpdb->users}.*
FROM 
	{$wpdb->posts}, 
	{$wpdb->postmeta},
	{$wpdb->users} 
WHERE 
	{$wpdb->users}.ID = {$wpdb->posts}.post_author AND
	{$wpdb->posts}.id = {$wpdb->postmeta}.post_id AND
	{$wpdb->postmeta}.meta_key = '_plays' AND
	{$wpdb->posts}.post_type = 'attachment' AND 
	{$wpdb->posts}.post_mime_type LIKE 'audio%'
	
ORDER BY _plays DESC 
LIMIT $limit");

	
// TOP DOWNLOADS
$mdownloads = $wpdb->get_results("
SELECT 
    {$wpdb->posts}.*, 
	{$wpdb->postmeta}.meta_value+0 AS _downloads,
	{$wpdb->users}.* 
FROM 
	{$wpdb->posts}, 
	{$wpdb->postmeta},
	{$wpdb->users} 
WHERE 
	{$wpdb->users}.ID = {$wpdb->posts}.post_author AND
	{$wpdb->posts}.id = {$wpdb->postmeta}.post_id AND 
	{$wpdb->postmeta}.meta_key = '_downloads' AND
	{$wpdb->posts}.post_type = 'attachment' AND 
	{$wpdb->posts}.post_mime_type LIKE 'audio%'
ORDER BY _downloads DESC 
LIMIT $limit");
	
	
// TOP EVENTOS
$eventos = $wpdb->get_results("
SELECT 
    {$wpdb->posts}.*, 
	{$wpdb->postmeta}.meta_value+0 AS _views 
FROM 
	{$wpdb->posts}, 
	{$wpdb->postmeta} 
WHERE 
	{$wpdb->posts}.id = {$wpdb->postmeta}.post_id AND 
	{$wpdb->postmeta}.meta_key = '_views' AND
	{$wpdb->posts}.post_type = 'eventos'
ORDER BY _views DESC 
LIMIT $limit");
	
	
	
// pega os usermetas dos artistas e produtores.
// artistas
$arts_ids = array();
foreach ($artistas as $u)
    $arts_ids[] = $u->ID;

$ids = implode(',', $arts_ids);

$ametas = $wpdb->get_results("
    SELECT
    	user_id, 
    	meta_key, 
    	meta_value 
    FROM $wpdb->usermeta 
    WHERE user_id IN ($ids)");

$artistas_metas = array();
foreach ($ametas as $m)
    $artistas_metas[$m->user_id][$m->meta_key] = $m->meta_value;

// produtores
$prods_ids = array();
foreach ($produtores as $u)
    $prods_ids[] = $u->ID;

$ids = implode(',', $prods_ids);

$pmetas = $wpdb->get_results("
    SELECT
    	user_id, 
    	meta_key, 
    	meta_value 
    FROM $wpdb->usermeta 
    WHERE 
    	user_id IN ($ids) AND
    	meta_key NOT LIKE '_widget%'");

$produtores_metas = array();
foreach ($pmetas as $m)
    $produtores_metas[$m->user_id][$m->meta_key] = $m->meta_value;



$paises = get_paises();
?>

<div id='top10-eventos'>
    <h4><?php echo sprintf(__('Os %s eventos mais visitados'), $limit); ?></h4>
    <table class='widefat'>
        <thead>
            <tr>
                <th>evento</th>
                <th width="40">visitas</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($eventos as $evento):?>
            <tr>
                <td><a href='<?php echo get_permalink($evento->ID);?>'><?php echo $evento->post_title?></a></td>
                <td align="center"><?php echo $evento->_views; ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div><br />

<div id='top10-artistas'>
    <h4><?php echo sprintf(__('Os %s artistas mais visitados'), $limit); ?></h4>
    <table class='widefat'>
        <thead>
            <tr>
                <th><?php _e('artista', 'tnb'); ?></th>
                <th><?php _e('email', 'tnb'); ?></th>
                <th><?php _e('endereço (residência)', 'tnb')?></th>
                <th><?php _e('origem', 'tnb')?></th>
                <th width="40"><?php _e('visitas', 'tnb')?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($artistas as $user):?>
            <tr>
                <td><a href='<?php echo get_author_posts_url($user->ID);?>' ><?php echo $user->display_name?></a></td>
                <td>
                    <?php echo $user->user_email; ?>
                    <?php echo ($user->user_email && $artistas_metas[$user->ID]['email_publico']) ? '<br />' : ''; ?>
                    <?php echo $artistas_metas[$user->ID]['email_publico'] ? __('público','tnb').': '.$artistas_metas[$user->ID]['email_publico'] : ''; ?>
                </td>
                <td>
                    <?php echo $artistas_metas[$user->ID]['banda_cidade']?>, 
                    <?php echo $artistas_metas[$user->ID]['banda_estado']?>, 
                    <?php echo $artistas_metas[$user->ID]['banda_pais'] ? $paises[$artistas_metas[$user->ID]['banda_pais']] : ''; ?> 
                </td>
                <td>
                    <?php echo $artistas_metas[$user->ID]['origem_cidade']?>, 
                    <?php echo $artistas_metas[$user->ID]['origem_estado']?>, 
                    <?php echo $artistas_metas[$user->ID]['origem_pais'] ? $paises[$artistas_metas[$user->ID]['origem_pais']] : ''; ?> 
                </td>
                
                <td align="center"><?php echo $user->_views; ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div><br />

<div id='top10-produtores'>
    <h4><?php echo sprintf(__('Os %s produtores mais visitados'), $limit); ?></h4>
    <table class='widefat'>
        <thead>
           <tr>
                <th><?php _e('produtor', 'tnb'); ?></th>
                <th><?php _e('email', 'tnb'); ?></th>
                <th><?php _e('telefone', 'tnb'); ?></th>
                <th><?php _e('endereço', 'tnb')?></th>
                <th width="40"><?php _e('visitas', 'tnb')?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($produtores as $user):?>
            <tr>
                <td><a href='<?php echo get_author_posts_url($user->ID);?>' ><?php echo $user->display_name?></a></td>
                <td>
                    <?php echo $user->user_email; ?>
                    <?php echo ($user->user_email && $produtores_metas[$user->ID]['email_publico']) ? '<br />' : ''; ?>
                    <?php echo $produtores_metas[$user->ID]['email_publico'] ? __('público','tnb').': '.$produtores_metas[$user->ID]['email_publico'] : ''; ?>
                </td>
                <td><?php echo $produtores_metas[$user->ID]['telefone']; ?></td>
                <td>
                    <?php echo $produtores_metas[$user->ID]['origem_cidade']?>, 
                    <?php echo $produtores_metas[$user->ID]['origem_estado']?>, 
                    <?php echo $produtores_metas[$user->ID]['origem_pais'] ? $paises[$produtores_metas[$user->ID]['origem_pais']] : ''; ?> 
                </td>
                <td align="center"><?php echo $user->_views; ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div><br />

<div id='top10-plays'>
    <h4><?php echo sprintf(__('As %s músicas mais tocadas'), $limit); ?></h4>
    <table class='widefat'>
        <thead>
            <tr>
                <th><?php _e('música','tnb')?></th>
                <th><?php _e('autor','tnb')?></th>
                <th width="40"><?php _e('plays','tnb')?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($mplays as $m):?>
            <tr>
                <td><?php echo $m->post_title?></td>
                <td><a href='<?php echo get_author_posts_url($m->post_author);?>' ><?php echo $m->display_name;?></a> <?php if(is_produtor($m->post_author)):?> (<?php _e('produtor', 'tnb')?>) <?php endif;?></td>
                <td align="center"><?php echo $m->_plays; ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div><br />

<div id='top10-downloads'>
    <h4><?php echo sprintf(__('As %s músicas mais baixadas'), $limit); ?></h4>
    <table class='widefat'>
        <thead>
            <tr>
                <th><?php _e('música','tnb')?></th>
                <th><?php _e('autor','tnb')?></th>
                <th width="40">downloads</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($mdownloads as $m):?>
            <tr>
                <td><?php echo $m->post_title?></td>
                <td><a href='<?php echo get_author_posts_url($m->post_author);?>' ><?php echo $m->display_name;?></a> <?php if(is_produtor($m->post_author)):?> (<?php _e('produtor', 'tnb')?>) <?php endif;?></td>
                <td align="center"><?php echo $m->_downloads; ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php 

?>