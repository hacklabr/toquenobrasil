<?php
/* Template Name: Listagem dos produtores */
?>

<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div id="producers" class="span-14 prepend-1 right-colborder">
	<div id="producers-title" class="item green clearfix">
		<div class="title pull-1">
			<div class="shadow"></div>
			<h1>Produtores</h1>
			<div class="clear"></div>
		</div>
	</div>

	<p id="intro">
        <?php 
          echo get_page_by_path('produtores')->post_content;
        ?>
    </p>
</div>

<?php get_sidebar("blog"); ?>

<?php get_footer(); ?>
