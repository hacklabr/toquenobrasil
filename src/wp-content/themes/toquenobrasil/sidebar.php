<div class="span-8 last">
  <div class="widgets">
	<div class="widget clearfix widget-yellow">
		<div class="title clearfix">
			<div class="shadow"></div>
			<h2 class="widgettitle">Pesquisa</h2>
		</div>	
		<form action="<?php bloginfo('url') ?>" id="searchform" method="get" role="search">
			<div>
				<label for="s" class="screen-reader-text">Pesquisar por:</label>
				<input type="text" id="s" name="s" value="">
				<input type="submit" value="Pesquisar" id="searchsubmit">
				<span class="span-3">
					<input type="checkbox" name="opcoes-de-busca" value="artistas" /> Artistas<br />
					<input type="checkbox" name="opcoes-de-busca" value="produtores" /> Produtores<br />
				</span>
				<span class="span-3 last">
				<input type="checkbox" name="opcoes-de-busca" value="eventos" /> Eventos<br />
				<input type="checkbox" name="opcoes-de-busca" value="blog" /> Blog<br />
				</span>
			</div>
		</form>
	</div>
  
    <?php
        global $in_blog;
        if(is_blog()){
            dynamic_sidebar('blog-sidebar');
        }else{
            dynamic_sidebar('tnb-sidebar');
        }    
        ?>
  </div>
</div>
