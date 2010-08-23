<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>
<div class="span-14 prepend-1 right-colborder">
    <?php if ( have_posts() ) : the_post(); ?>
    <div class="item green">
        <div class="title pull-1">
            <div class="shadow"></div>
            <h1>
                <?php the_title(); ?>
            </h1>
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
                <span class="labels">Vagas:</span> <?php echo get_post_meta(get_the_ID(), "eventos_vagas", true); ?><br />
                </p>
            </div><!-- .dados-do-evento -->
        </div>
        <div class="span-14">
            <?php the_content(); ?>
            <div class="clear"></div>
            <div class="post-tags">
                <p>
                    <?php the_tags(" "," "," "); ?>
                </p>
            </div>
            <!-- .post-tags -->                       
        </div>                
        <div class="clear"></div>
        <div class="quero-tocar">
            <a href="#">Quero<br />tocar!</a>
            <div class="shadow"></div>
        </div><!-- .quero-tocar -->
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
