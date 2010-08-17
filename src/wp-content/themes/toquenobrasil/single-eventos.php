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
        <div class="post-time span-14">
            <div class="shadow"></div>
            <div class="data">
                <div class="dia">
                    <?php the_time("d"); ?>
                </div>
                <div class="mes-ano">
                    <?php the_time("m/Y");?>
                </div>
            </div>
        </div>
        <!-- .post-time -->
        <div id="thumb" class="span-4">
            <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail('eventos'); ?>
            <?php else : ?>
            <?php theme_image("thumb.png") ?>
            <?php endif; ?>
        </div><!-- .thumb -->
        <div class="span-10 last">
            <?php the_content(); ?>
            <div class="clear"></div>
            <div class="post-tags">
                <p>
                    <?php the_tags(" "," "," "); ?>
                </p>
            </div>
            <!-- .post-tags -->            
        </div>
        <div id="dados-do-evento">
                <div class="title">
                    <div class="shadow"></div>
                    <span><h4 class="no-margin">Dados do evento</h4></span>
                    <div class="clear"></div>
                </div><!-- .title -->			
                <p><span class="labels">Data do evento:</span> <?php echo get_post_meta(get_the_ID(), "evento_data", true); ?><br />
                <span class="labels">Início das inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_inicio", true); ?><br />
                <span class="labels">Fim das inscrições:</span> <?php echo get_post_meta(get_the_ID(), "eventos_inscricao_fim", true); ?><br />
                <span class="labels">Email:</span><?php echo get_post_meta(get_the_ID(), "eventos_recipient", true); ?></p>
                
            </div><!-- .dados-do-evento -->         
        <div class="clear"></div>
        <div class="navigation">
            <div class="alignright">
                <?php next_post_link('%link', 'Próximo evento &raquo;', true); ?>
            </div>
            <div class="alignleft">
                <?php previous_post_link('%link', '&laquo; Evento anterior', true); ?>
            </div>
        </div>
        <!-- .navigation --> 
    </div>
    <!-- .post -->
    <?php endif; ?>
</div>
<?php get_sidebar("blog"); ?>
<?php get_footer(); ?>
