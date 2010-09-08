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

    <div class="post">        
      <div id="thumb" class="span-4">
        <?php if ( has_post_thumbnail() ) : ?>
          <?php the_post_thumbnail('eventos'); ?>
        <?php else : ?>
          <?php theme_image("thumb.png") ?>
        <?php endif; ?>
      </div><!-- .thumb -->
      
      <div class="span-10 last">        
        <div id="dados-do-evento">
          <?php
	    $inicio = get_post_meta(get_the_ID(), "evento_inicio", true);
	    $fim = get_post_meta(get_the_ID(), "evento_fim", true);
	  ?>                        	
          <p><span class="labels">Tipo de evento:</span> <?php echo get_post_meta(get_the_ID(), "evento_tipo", true); ?><br />
          <span class="labels">Data do evento:</span> <?php echo (!$fim ? $inicio : "$inicio - $fim") ;?><br />
          <span class="labels">Inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_inicio", true); ?><br />
          <span class="labels">Fim das inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_fim", true); ?><br />
          <span class="labels">Local:</span> <?php echo get_post_meta(get_the_ID(), "eventos_local", true); ?><br />
          <span class="labels">Site:</span> <?php echo get_post_meta(get_the_ID(), "eventos_site", true); ?><br />
          <span class="labels">Vagas:</span> <?php echo get_post_meta(get_the_ID(), "eventos_vagas", true); ?></p>
        </div><!-- .dados-do-evento -->
      </div>

      <div class="span-14">
        <?php the_content(); ?>
        <div class="clear"></div>
        <div class="post-tags">
          <p><?php the_tags(" "," "," "); ?></p>
        </div><!-- .post-tags -->                       
      </div>                
      <div class="clear"></div>
      <div class="quero-tocar">
        <a href="#">Quero<br />tocar!</a>
        <div class="shadow"></div>
      </div><!-- .quero-tocar -->
    </div>

    <div class="item yellow">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h3>Artistas Selecionados</h3>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>    

    <div class="selected-artists">
      <div class="artist clearfix">
        <h2><a href="http://www.prefuse73.com" target="_blank">Prefuse 73</a></h2>
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/9k799uxd-150x150.jpg" class="span-3"/>
        <p>
          Responsável: Prefuse 73
          <br/>
          Telefone: 11 9997 7777
          <br/>
          <a href="http://www.prefuse73.com">http://www.prefuse73.com</a>
          <br/>
          <a href="mailto:prefuse73@prefuse73.com">prefuse@prefuse73.com</a>
        </p>
      </div>

      <div class="artist clearfix">
        <h2><a href="http://www.kid606.com" target="_blank">Kid 606</a></h2>
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/kid606_02-150x150.jpg" class="span-3"/>
        <p>
          Responsável: Kid 606
          <br/>
          Telefone: 11 9997 7777
          <br/>
          <a href="http://www.kid606.com">http://www.kid606.com</a>
          <br/>
          <a href="mailto:kid606@kid606.com">kid@kid606.com</a>
        </p>
      </div>
    </div>

    <div class="item yellow">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h3>Artistas Inscritos</h3>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>    

    <div class="selected-artists">
      <div class="artist clearfix">
        <h2><a href="http://www.prefuse73.com" target="_blank">Prefuse 73</a></h2>
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/9k799uxd-150x150.jpg" class="span-3"/>
        <p>
          Responsável: Prefuse 73
          <br/>
          Telefone: 11 9997 7777
          <br/>
          <a href="http://www.prefuse73.com">http://www.prefuse73.com</a>
          <br/>
          <a href="mailto:prefuse73@prefuse73.com">prefuse@prefuse73.com</a>
        </p>
      </div>

      <div class="artist clearfix">
        <h2><a href="http://www.kid606.com" target="_blank">Kid 606</a></h2>
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/kid606_02-150x150.jpg" class="span-3"/>
        <p>
          Responsável: Kid 606
          <br/>
          Telefone: 11 9997 7777
          <br/>
          <a href="http://www.kid606.com">http://www.kid606.com</a>
          <br/>
          <a href="mailto:kid606@kid606.com">kid@kid606.com</a>
        </p>
      </div>
    </div>

    <div class="signedup-artists">
      <div class="artist clearfix">
        <h2><a href="http://www.prefuse73.com" target="_blank">Prefuse 73</a></h2>
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/9k799uxd-150x150.jpg" class="span-3"/>
        <p>
          Responsável: Prefuse 73
          <br/>
          Telefone: 11 9997 7777
          <br/>
          <a href="http://www.prefuse73.com">http://www.prefuse73.com</a>
          <br/>
          <a href="mailto:prefuse73@prefuse73.com">prefuse@prefuse73.com</a>
        </p>
      </div>

      <div class="artist clearfix">
        <h2><a href="http://www.kid606.com" target="_blank">Kid 606</a></h2>
        <img src="http://localhost/toquenobrasil/wp-content/uploads/2010/09/kid606_02-150x150.jpg" class="span-3"/>
        <p>
          Responsável: Kid 606
          <br/>
          Telefone: 11 9997 7777
          <br/>
          <a href="http://www.kid606.com">http://www.kid606.com</a>
          <br/>
          <a href="mailto:kid606@kid606.com">kid@kid606.com</a>
        </p>
      </div>
    </div>



    <!-- .post -->
    <div id="posts-navigation">
      <?php previous_post_link('<div id="anterior">%link</div>','Evento anterior', true); ?>
      <?php next_post_link('<div id="proximo">%link</div>', 'Próximo evento', true); ?>            
    </div><!-- #posts-navigation -->
    <?php endif; ?>

    
</div>
<?php get_sidebar("blog"); ?>
<?php get_footer(); ?>
