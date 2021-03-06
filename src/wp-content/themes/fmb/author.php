<?php 

global $wp_query, $current_user;
$curauth = $wp_query->get_queried_object();

get_header();
?>

<div class="prepend-top"></div>

<div id="artist-<?php echo $curauth->ID; ?>" class="artist span-14 prepend-1 right-colborder">
  <div id="artist-<?php echo $curauth->ID; ?>-title" class="item green clearfix">
    <div class="title pull-1 clearfix">
      <div class="shadow"></div>
      <h1><?php echo $curauth->banda; ?></h1>
    </div>
  </div>

  <div id="artist-<?php echo $curauth->ID; ?>-content" class="clearfix">
    <div id="artist-<?php echo $curauth->ID; ?>-info" class="info clearfix">
      <div class="span-2">
        <?php echo get_avatar($curauth->ID, 70); ?>
      </div>
      
      <div class="span-11 last">
        <?php echo preg_replace("/\n/", '<br/>', $curauth->description); ?>
		<br/><br/>
        <p>
          <?php if($curauth->origem_estado !=''): $estados = get_estados();?>
   	      	<strong><?php _e('Local de origem da banda:','tnb'); ?></strong>  <?php echo $estados[$curauth->origem_estado], ' - ', $curauth->origem_cidade ?><br/>
          <?php endif;?>
          
          <?php if($curauth->integrantes !=''): $estados = get_estados();?>
   	      	<strong><?php _e('Integrantes da banda:','tnb'); ?></strong> <br/> <p class='prepend-1'><?php echo preg_replace("/\n/", '<br/>', $curauth->integrantes);  ?></p><br/>
          <?php endif;?>
          
          
		    
          
          <?php if ($curauth->site) : ?>
            <p><strong><?php _e('Link:','tnb'); ?></strong> <a href="<?php echo $curauth->site; ?>" target="_blank"><?php echo $curauth->site; ?></a></p>
          <?php endif; ?>
          
          <?php if(current_user_can('select_artists') || current_user_can('select_other_artists')  || $curauth->ID == $current_user->ID ):?>
            <br/>
            <h3>Dados Pessoais <?php theme_image('lock.png', array('title' => 'teste')); ?></h3>
            <?php if ($curauth->responsavel) : ?>
                <strong><?php _e('Responsável:','tnb') ?></strong> <?php echo $curauth->responsavel; ?>
                <br/>
            <?php endif;?>
            
            <?php if ($curauth->user_email) : ?>
                <strong><?php _e('E-mail:','tnb'); ?></strong> <a href="mailto:<?php echo $curauth->user_email; ?>"><?php echo $curauth->user_email; ?></a>
                <br/>
            <?php endif;?>
            
            <?php if ($curauth->telefone_ddd) : ?>
                <strong><?php _e('Telefone:','tnb'); ?></strong> <?php echo $curauth->telefone_ddd; ?> <?php echo $curauth->telefone; ?>
                <br/>
            <?php endif;?>
            
            <?php if($curauth->banda_estado !=''): $estados = get_estados();?>
                 <strong><?php _e('Local de residência da banda:','tnb'); ?></strong>  <?php echo $estados[$curauth->banda_estado], ' - ', $curauth->banda_cidade; ?><br/>
            <?php endif;?>
            
            <?php if($curauth->categoria !=''): global $fmb_categorias, $fmb_subcategorias; ?>
          
              <strong><?php _e('Categoria:','tnb'); ?></strong> <br/> <p class='prepend-1'><?php echo $fmb_categorias[$curauth->categoria]; ?>
              <?php if ($curauth->subcategoria) echo ' / ',  $fmb_subcategorias[$curauth->subcategoria]; ?></p><br/>
          
            <?php endif; ?>
        
          <?php endif; ?>
          
          
        </p>
      </div>
    </div>
	<?php 
        $medias = get_posts("post_type=music&author={$curauth->ID}&meta_key=_media_index&orderby=menu_order&order=ASC");
		if ($medias) : ?>
			<h3>Músicas</h3>
	
			<div id="artist-<?php echo $curauth->ID; ?>-music">
			  <?php
				foreach( $medias as $media ) {
				  echo '<div class="span-4">';
				  //echo get_post_meta($media->ID, '_media_index', true);
				  print_audio_player($media->ID);
				  echo '<br/>';
				  echo $media->post_title;
				  echo '</div>';
				}
			  ?>
			</div>
				
			<div class="clear"></div>
			<div class="prepend-top"></div>
			<div class="hr"></div>
    <?php endif;?>  
    
    <div id="artist-<?php echo $curauth->ID; ?>-images" class="thumb span-4">
      <?php 
		$medias = get_posts("post_type=images&meta_key=_media_index&author={$curauth->ID}&orderby=menu_order&order=ASC");
		foreach ($medias as $media) {	        
          $meta = get_post_meta($media->ID, '_wp_attachment_metadata');
          preg_match('/(\d{4}\/\d\d\/).+/', $meta[0]['file'], $folder);
          $images_url = get_option('siteurl') . '/wp-content/uploads/';
          if (isset($meta[0]['sizes']) && array_key_exists('thumbnail', $meta[0]['sizes'])) {
            $thumb = $folder[1] . $meta[0]['sizes']['thumbnail']['file'];
          } else {
            $thumb = $meta[0]['file'];
          }
          
          if (isset($meta[0]['sizes']) && array_key_exists('medium', $meta[0]['sizes'])) {
            $medium = $folder[1] . $meta[0]['sizes']['medium']['file'];
          } else {
            $medium = $meta[0]['file'];
          }
          
          if (isset($meta[0]['sizes']) && array_key_exists('large', $meta[0]['sizes'])) {
            $large = $folder[1] . $meta[0]['sizes']['large']['file'];
          } else {
            $large = $meta[0]['file'];
          }
	
          $thumburl = $images_url . $thumb;
          $mediumurl = $images_url . $medium;
          $largeurl = $images_url . $large;
	
          echo "<a href='". $largeurl."' rel='lightbox-images' ><img src='" . $thumburl ."'/></a>";
        }
      ?>
    </div>

    <div id="artist-<?php echo $curauth->ID; ?>-video" class="span-10 last">
      <?php 
          if(strlen($curauth->youtube)>0 && preg_match("/\/watch\?v=/", $curauth->youtube) ) {
            $width = 390;
            $height = 317;
            $videoUrl = preg_replace("/\/watch\?v=/", "/v/" ,$curauth->youtube);
      ?>
      <object width='<?php echo $width; ?>' height='<?php echo $height; ?>' data='<?php echo $videoUrl; ?>?fs=1&amp;hl=en_US&amp;rel=0'>
        <param name='allowScriptAccess' value='always'/>
        <param name='allowFullScreen' value='True'/>
        <param name='movie' value='<?php echo $videoUrl; ?>&autoplay=0&border=0&showsearch=0&enablejsapi=1&playerapiid=ytplayer&fs=1'></param>
        <param name='wmode' value='transparent'></param>
        <embed src='<?php echo $videoUrl; ?>&autoplay=0&border=0&showsearch=0&fs=1' type='application/x-shockwave-flash' wmode='transparent' width='<?php echo $width; ?>' height='<?php echo $height; ?>' allowfullscreen='1'></embed>
      </object>
      <?php } ?> 
    </div>

    <div class="clear"></div>
    <div class="prepend-top"></div>
    <div class="hr"></div>
      
     <?php 
      $medias = get_posts("post_type=rider&author={$curauth->ID}");
      foreach( $medias as $media ) : ?>
        <div class="span-5">
          <h3 class="no-margin"><?php _e('Rider','tnb'); ?></h3><br/>
          <a href='<?php echo $media->guid; ?>' target='_blank'>
            <?php theme_image('tnb-rider.png'); ?>
          </a>
        </div>
    <?php endforeach; ?>
    
    <?php 
      $medias = get_posts("post_type=mapa_palco&author={$curauth->ID}");
      foreach( $medias as $media ) : ?>
        <div class="span-5">
          <h3 class="no-margin"><?php _e('Mapa de Palco','tnb'); ?></h3><br/>
          <a href='<?php echo $media->guid; ?>' target='_blank' >
            <?php theme_image('tnb-map.png'); ?>
          </a>
        </div>
    <?php endforeach; ?>
      
   
  </div>
</div>

<div class="span-8 last">
  <div  class='widgets'>
    <?php dynamic_sidebar("tnb-sidebar");?>
  </div>
</div>

<?php get_footer(); ?>
