<?php
/* Template Name: Cadastro de evento */
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
    <div class="item yellow ">
          <form id="cadastro-de-evento" class="background clearfix" method="POST">
          <h3>Informações Gerais</h3>
			<p>
				<label for="nome-do-evento">Nome</label><br />
				<input id="nome-do-evento" class="text" name="nome-do-evento" type="text" value="" />			
			</p>
			<p>
				<label for="evento_site">Site</label><br />
				<input id="evento_site" class="text" name="evento_site" type="text" value="" />			
			</p>
			<p>
				<label for="evento_avatar">Avatar</label><br />
				<input id="evento_avatar" class="text" name="evento_avatar" type="file" />			
			</p>			
			<p class="span-6">
				<label for="evento_tipo">Tipo de evento</label><br />
				<select id="evento_tipo">
					<option>normal</option>
					<option>superevento</option>
					<option>subevento</option>
				</select>			
			</p>
			<p class="span-6">
				<label for="evento_pai">Evento pai</label><br /> <!--se for subevento -->
				<select id="evento_pai">
					<option>nomes dos eventos pais</option>
					<option>nomes dos eventos pais</option>
					<option>nomes dos eventos pais</option>
					<option>nomes dos eventos pais</option>
				</select>			
			</p>
			<p>
				<label for="evento_patrocinadores">Patrocínio - imagem única com logos dos patrocinadores. </label><br />
				<input id="evento_patrocinadores" class="text alignleft" name="evento_patrocinadores" type="file" /> <span>(largura máxima - 000px)</span>		
			</p>
			<h3>Local</h3>
			<p class="span-5">
				<label for="evento_local">Estabelecimento</label><br />
				<input id="evento_local" class="text" name="evento_local" type="text" value="" />			
			</p>
			<p class="span-5">
				<label for="evento_cidade">Cidade</label><br />
				<input id="evento_cidade" class="text" name="evento_cidade" type="text" value="" />			
			</p>
			<p class="span-2">
				<label for="evento_estado">Estado</label><br />
				<select id="evento_estado" name="evento_estado">					
					<option value="ac">AC</option>
					<option value="al">AL</option>
					<option value="ap">AP</option>
					<option value="am">AM</option>
					<option value="ba">BA</option>
					<option value="ce">CE</option>
					<option value="df">DF</option>
					<option value="es">ES</option>
					<option value="go">GO</option>
					<option value="ma">MA</option>
					<option value="ms">MS</option>
					<option value="mt">MT</option>
					<option value="mg">MG</option>
					<option value="pa">PA</option>
					<option value="pb">PB</option>
					<option value="pr">PR</option>
					<option value="pe">PE</option>
					<option value="pi">PI</option>
					<option value="rj">RJ</option>
					<option value="rn">RN</option>
					<option value="rs">RS</option>
					<option value="ro">RO</option>
					<option value="rr">RR</option>
					<option value="sc">SC</option>
					<option value="sp">SP</option>
					<option value="se">SE</option>
					<option value="to">TO</option>
				</select>			
			</p>
			
			<h3>Data</h3>
			<p class="span-6">
				<label for="evento_inicio">Início</label><br />
				<input id="evento_inicio" class="text" name="evento_inicio" type="text" value="" />			
			</p>
			<p class="span-6">
				<label for="evento_fim">Fim</label><br />
				<input id="evento_fim" class="text" name="evento_fim" type="text" value="" />			
			</p>
			
			<h3>Inscrições</h3>
			<p class="span-6">
				<label for="evento_inscricao_inicio">Início</label><br />
				<input id="evento_inscricao_inicio" class="text" name="evento_inscricao_inicio" type="text" value="" />			
			</p>
			<p class="span-6">
				<label for="evento_inscricao_fim">Fim</label><br />
				<input id="evento_inscricao_fim" class="text" name="evento_inscricao_fim" type="text" value="" />			
			</p>
			<p class="span-2">
				<label for="evento_vagas">Vagas</label><br />
				<input id="evento_vagas" class="text" name="evento_vagas" type="text" value="" />			
			</p>
			<p class="span-4">
				<label for="evento_taxa">Taxa de inscrição</label><br />
				<input id="evento_taxa" class="text" name="evento_taxa" type="text" value="" />			
			</p>
			<p class="span-6">
				<label for="evento_recipient">Email de inscrição</label><br />
				<input id="evento_recipient" class="text" name="evento_recipient" type="text" value="" />			
			</p>
			<p>
				<label for="condicoes">Condições</label><br />
				<textarea id="condicoes" class="text" name="condicoes" value=""></textarea>
			</p>
			<p>
				<label for="restricoes">Restrições</label><br />
				<textarea id="restricoes" class="text" name="restricoes" value=""></textarea>
			</p>
			<p>
				<label for="tos">Termos</label><br />
				<textarea id="tos" class="text" name="tos" value=""></textarea>
			</p>
			
			<div class="span-2 prepend-10 last">
                 <input type="image" class="submit" value="Enviar" src="<?php echo get_theme_image("submit-comment.png"); ?>">
            </div>
			
		</form>  
    </div>
  <?php endif; ?>  
</div>

<div class="span-8 last">
    <div  class='widgets'>
        <?php dynamic_sidebar("blog-sidebar");?>
    </div>
</div>

<?php get_footer(); ?>
