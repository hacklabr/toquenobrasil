<?php
/*
  Template Name: Cadastre-se
*/

?>
<?php

	wp_enqueue_script('cadastre-se', get_stylesheet_directory_uri(). '/js/cadastre-se.js',array('jquery')); 
	get_header();

 ?>

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
        <div class="prepend-top"></div>       
    </div>    
			<?php the_content(); ?>
            <div id="formularios-de-cadastro">
                <div id="abas" class="clearfix">                        
                    <div id="aba-produtores" class="title">                    	
                    	<a href="#">Produtores<span class="shadow"></span></a>
                    </div>
                    <div id="aba-artistas" class="title current">                    	
                        <a href="#">Artistas<span class="shadow"></span></a>
                    </div>
                </div><!-- #abas -->
                <div id="conteudo">
                    <div id="artistas" class="item green">
                        <form class="background clearfix">
                            <p>
                                <label for="banda">Banda:</label>
                                <br />
                                <input type="text" id="banda" name="banda" value="" />
                            </p>
                            <p class="span-6">
                                <label for="responsavel">Responsável:</label>
                                <br />
                                <input type="text" id="responsavel" name="responsavel" value="" />
                            </p>                       
                            <p class="span-6">
                                <label for="site">Site:</label>
                                <br />
                                <input type="text" id="site" name="site" value="" />
                            </p>
                            <p class="span-4">
                                 <label for="estado">Estado:</label>
                                <br />
                                <select name="estado">                            
                                    <option value="ac">Acre</option>
                                    <option value="al">Alagoas</option>
                                    <option value="ap">Amapá</option>
                                    <option value="am">Amazonas</option>
                                    <option value="ba">Bahia</option>
                                    <option value="ce">Ceará</option>
                                    <option value="df">Distrito Federal</option>
                                    <option value="es">Espirito Santo</option>
                                    <option value="go">Goiás</option>
                                    <option value="ma">Maranhão</option>
                                    <option value="ms">Mato Grosso do Sul</option>
                                    <option value="mt">Mato Grosso</option>
                                    <option value="mg">Minas Gerais</option>
                                    <option value="pa">Pará</option>
                                    <option value="pb">Paraíba</option>
                                    <option value="pr">Paraná</option>
                                    <option value="pe">Pernambuco</option>
                                    <option value="pi">Piauí</option>
                                    <option value="rj">Rio de Janeiro</option>
                                    <option value="rn">Rio Grande do Norte</option>
                                    <option value="rs">Rio Grande do Sul</option>
                                    <option value="ro">Rondônia</option>
                                    <option value="rr">Roraima</option>
                                    <option value="sc">Santa Catarina</option>
                                    <option value="sp">São Paulo</option>
                                    <option value="se">Sergipe</option>
                                    <option value="to">Tocantins</option>
                                </select>
                            </p>
                            <p class="span-6">
                                <label for="telefone">Telefone:</label>
                                <br />
                                <input type="text" id="ddd" name="ddd" value="" /> - <input type="text" id="telefone" name="telefone" value="" />
                            </p>
                            <p class="span-6">
                                <label for="email">E-mail:</label>
                                <br />
                                <input type="text" id="email" name="email" value="" />
                            </p>
                            <p class="span-6">
                                <label for="senha">Senha:</label>
                                <br />
                                <input type="text" id="senha" name="senha" value="" />
                            </p>
                            <div class="span-2 prepend-10 last">
                                <input type="image" src="<?php echo get_theme_image("submit-green.png"); ?>" value="Enviar" class="submit" />
                            </div>
                        </form>
                    </div><!-- #artistas -->
                    <div id="produtores" class="item blue">
                        <form class="background clearfix">
                            <p class="span-6">
                                <label for="responsavel">Nome:</label>
                                <br />
                                <input type="text" id="nome" name="nome" value="" />
                            </p>                       
                            <p class="span-6">
                                <label for="site">Site:</label>
                                <br />
                                <input type="text" id="site" name="site" value="" />
                            </p>
                            <p class="span-4">
                                 <label for="estado">Estado:</label>
                                <br />
                                <select name="estado">                            
                                    <option value="ac">Acre</option>
                                    <option value="al">Alagoas</option>
                                    <option value="ap">Amapá</option>
                                    <option value="am">Amazonas</option>
                                    <option value="ba">Bahia</option>
                                    <option value="ce">Ceará</option>
                                    <option value="df">Distrito Federal</option>
                                    <option value="es">Espirito Santo</option>
                                    <option value="go">Goiás</option>
                                    <option value="ma">Maranhão</option>
                                    <option value="ms">Mato Grosso do Sul</option>
                                    <option value="mt">Mato Grosso</option>
                                    <option value="mg">Minas Gerais</option>
                                    <option value="pa">Pará</option>
                                    <option value="pb">Paraíba</option>
                                    <option value="pr">Paraná</option>
                                    <option value="pe">Pernambuco</option>
                                    <option value="pi">Piauí</option>
                                    <option value="rj">Rio de Janeiro</option>
                                    <option value="rn">Rio Grande do Norte</option>
                                    <option value="rs">Rio Grande do Sul</option>
                                    <option value="ro">Rondônia</option>
                                    <option value="rr">Roraima</option>
                                    <option value="sc">Santa Catarina</option>
                                    <option value="sp">São Paulo</option>
                                    <option value="se">Sergipe</option>
                                    <option value="to">Tocantins</option>
                                </select>
                            </p>
                            <p class="span-6">
                                <label for="telefone">Telefone:</label>
                                <br />
                                <input type="text" id="ddd" name="ddd" value="" /> - <input type="text" id="telefone" name="telefone" value="" />
                            </p>
                            <p class="span-6">
                                <label for="email">E-mail:</label>
                                <br />
                                <input type="text" id="email" name="email" value="" />
                            </p>
                            <p class="span-6">
                                <label for="senha">Senha:</label>
                                <br />
                                <input type="text" id="senha" name="senha" value="" />
                            </p>
                            <div class="span-2 prepend-10 last">
                                <input type="image" src="<?php echo get_theme_image("submit.png"); ?>" value="Enviar" class="submit" />
                            </div>
                        </form>
                    </div><!-- #produtores -->
                </div><!-- #content -->
            </div>
            <!-- #formularios-de-cadastro -->        
    <?php endif; ?>
</div>
<?php get_sidebar("blog"); ?>
<?php get_footer(); ?>
