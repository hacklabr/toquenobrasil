<?php 

global $wp_query;
$curauth = $wp_query->get_queried_object();
var_dump($curauth );



?>
<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">

    <div class="item green">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h1><?php echo $curauth->banda; ?></h1>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>

    <div id="intro"><?php echo $curauth->description; ?></div>

    <div class="artist clearfix">
        <?php echo get_avatar($curauth->ID); ?>
      <p>
        Responsável: <?php echo $curauth->responsavel; ?>
        <br/>
        Telefone: <?php echo $curauth->telefone_ddd; ?> <?php echo $curauth->telefone; ?>
        <br/>
        <a href="<?php echo $curauth->site; ?>"><?php echo $curauth->site; ?></a>
        <br/>
        <a href="mailto:<?php echo $curauth->user_email; ?>"><?php echo $curauth->user_email; ?></a>
      </p>
      <div class="thumb span-4">
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/9k799uxd-150x150.jpg"/>
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/prefuse73-150x150.jpg"/>
      </div>
      <div class="span-10 last">
        <object width="390" height="317"><param name="movie" value="http://www.youtube.com/v/FMhTM3e4k4w?fs=1&amp;hl=en_US&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/FMhTM3e4k4w?fs=1&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="390" height="317"></embed></object>
      </div>
      <div class="clear"></div>
      <div class="prepend-top"></div>
      <div class="hr"></div>
      <div class="span-4">Musica 1</div>
      <div class="span-4">Musica 2</div>
      <div class="span-4">Musica 3</div>
    </div>
    
</div>

<?php get_footer(); ?>
