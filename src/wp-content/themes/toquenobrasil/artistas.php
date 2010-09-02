<?php
/* Template Name: Listagem dos artistas */
?>

<?php get_header(); ?>

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

    <div id="intro"><?php the_content(); ?></div>

    <div class="artist clearfix">
      <h2 class="span-14">
        <a href="">Prefuse 73</a>
      </h2>
      <p>
        Responsável: Prefuse 73
        <br/>
        Telefone: 11 9997 7777
        <br/>
        <a href="http://www.prefuse73.com">http://www.prefuse73.com</a>
        <br/>
        <a href="mailto:prefuse73@prefuse73.com">prefuse@prefuse73.com</a>
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

    <div class="artist clearfix">
      <h2 class="span-14">
        <a href="">Kid 606</a>
      </h2>
      <p>
        Responsável: Kid 606
        <br/>
        Telefone: 11 6060 6060
        <br/>
        <a href="http://www.kid606.com">http://www.kid606.com</a>
        <br/>
        <a href="mailto:kid606@kid606.com">kid606@kid606.com</a>
      </p>
      <div class="thumb span-4">
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/kid606-150x150.jpg"/>
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/kid606_02-150x150.jpg"/>
      </div>
      <div class="span-10 last">
        <object width="390" height="317"><param name="movie" value="http://www.youtube.com/v/yhowYh-gdYk?fs=1&amp;hl=en_US&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/yhowYh-gdYk?fs=1&amp;hl=en_US&amp;rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="390" height="317"></embed></object>
      </div>
      <div class="clear"></div>
      <div class="prepend-top"></div>
      <div class="hr"></div>
      <div class="span-4">Musica 1</div>
      <div class="span-4">Musica 2</div>
      <div class="span-4">Musica 3</div>
    </div>

  <?php endif; ?>

</div>

<?php get_footer(); ?>
