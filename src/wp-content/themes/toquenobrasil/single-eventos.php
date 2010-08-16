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
      <div class="post-time span-14">
        <div class="shadow"></div>
        <div class="data"><div class="dia"><?php the_time("d"); ?></div><div class="mes-ano"><?php the_time("m/Y");?></div></div>
      </div><!-- .post-time -->
      <?php if ( has_post_thumbnail() ) : ?>
      	<div class="span-4">
		<?php the_post_thumbnail('eventos'); ?>        
        </div>
        <div class="span-10 last">
		<?php else : ?>
        <div class="span-10 last prepend-4">	
	  <?php endif; ?>     
        <div class="clear"></div>       
        <div class="dados-do-evento">
			
            <p><span class="labels">Data do evento:</span> <?php echo get_post_meta(get_the_ID(), "evento_data", true); ?></p>
            <p><span class="labels">Início das inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_inicio", true); ?></p>
            <p><span class="labels">Fim das inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_fim", true); ?></p>
            <p><span class="labels">Email:</span><?php echo get_post_meta(get_the_ID(), "eventos_recipient", true); ?></p>
        </div><!-- .dados-do-evento -->        
        <?php the_content(); ?>
        <div class="clear"></div>
        <div class="post-tags">
          <p><?php the_tags(" "," "," "); ?></p>
        </div><!-- .post-tags -->
      </div>      
      <div class="clear"></div>
        <div class="navigation">
            <div class="alignright"><?php next_post_link('%link', 'Próximo evento &raquo;', true); ?></div>
            <div class="alignleft"><?php previous_post_link('%link', '&laquo; Evento anterior', true); ?></div>
        </div><!-- .navigation -->                
    </div><!-- .post -->
  <?php endif; ?>
</div>

<?php get_sidebar("blog"); ?>

<?php get_footer(); ?>