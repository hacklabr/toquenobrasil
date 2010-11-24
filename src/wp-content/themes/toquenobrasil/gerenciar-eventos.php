<?php
/* Template Name: Gerenciar eventos */
?>
<?php get_header(); ?>

<div class="clear"></div>
<div class="prepend-top"></div>

<div class="span-14 prepend-1 right-colborder">
  <?php if ( have_posts() ) : the_post(); ?>
    <div class="item green clearfix">
      <div class="title pull-1">
        <div class="shadow"></div>
        <h1><?php the_title(); ?></h1>
      </div>
    </div>
    <ul id="gerenciar-eventos">
		<li>
			<h2><a href="#" title="Nome do Evento Normal">Nome do Evento Normal</a></h2>
			<div><a href="#" title="Editar">Editar</a> <a href="#" title="Excluir">Excluir</a> <a href="#" title="Pagamentos">Pagamentos</a></div>
		</li>
		<li>
			<h2><a href="#" title="Nome do Superevento">Nome do Superevento</a></h2>
			<div><a href="#" title="Editar">Editar</a> <a href="#" title="Excluir">Excluir</a> <a href="#" title="Pagamentos">Pagamentos</a></div>
		<ul>
			<li>
				<h3><a href="#" title="Nome do Subevento">Nome do Subevento</a></h3>
				<div><a href="#" title="Editar">Editar</a> <a href="#" title="Excluir">Excluir</a> <a href="#" title="Pagamentos">Pagamentos</a> <a href="#" title="Aprovar">Aprovar</a></div>
			</li>
			<li>
				<h3><a href="#" title="Nome do Subevento">Nome do Subevento</a></h3>
				<div><a href="#" title="Editar">Editar</a> <a href="#" title="Excluir">Excluir</a> <a href="#" title="Pagamentos">Pagamentos</a></div>
			</li>
			<li>
				<h3><a href="#" title="Nome do Subevento">Nome do Subevento</a></h3>
				<div><a href="#" title="Editar">Editar</a> <a href="#" title="Excluir">Excluir</a> <a href="#" title="Pagamentos">Pagamentos</a></div>
			</li>
		</ul>
    </li>
    
    </ul>
     

  <?php endif; ?>  
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
