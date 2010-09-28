      <div class="clear"></div>
      <div id="footer" class="span-24">
        <div class="span-11">
          <div id="socialmedia" class="span-3">
            <a href="<?php bloginfo('url'); ?>/feed" target="_blank"><?php theme_image("rss.png") ?></a>
            <br>
            <a href="<?php echo get_theme_option('facebook_url'); ?>" target="_blank"><?php theme_image("facebook.png") ?></a>
            <br>
            <a href="<?php echo get_theme_option('flickr_url'); ?>" target="_blank"><?php theme_image("flickr.png") ?></a>
            <br>
            <a href="<?php echo get_theme_option('twitter_url'); ?>" target="_blank"><?php theme_image("twitter.png") ?></a>
          </div>
          <div class="span-8 last">
            <div id="twit">
              <div class="content textcenter prepend-top">
              <?php if( dynamic_sidebar('rodape') ); ?>                
              </div>
            </div>
          </div>
        </div>
        <div class="span-13 last">
          <?php theme_image("contato.png", array("id"=>"contato-title")); ?>
          <?php if($_POST['contact_us']):?>
          	<div class='success span-10' id='scm_contact'><?php _e('sua mensagem foi enviada com sucesso')?></div>
          	<script>
          		jQuery.scrollTo('#scm_contact', 800);
          	</script>
          <?php endif;?>
          <form method='POST'  name='contact_us' id='contact_us_form' >
          	<input type="hidden" name='contact_us' value='1'>
            <div class="span-6 prepend-2 " >
              <textarea class='auto_clean' name='contact_message' title='Mensagem'>Mensagem</textarea>
            </div>

            <div class="span-4 last">
              <input type="text"  name="contact_name" value="Nome" title='Nome'  class="text auto_clean" />
            </div>
            <div class="span-4 last">
              <input type="text" name="contact_email" value="E-mail" title='E-mail'  class="text auto_clean" />
            </div>
            <div class="span-4 last">
              <input type="text" name="contact_site" value="http://" title='http://' class="text auto_clean" />
            </div>
            <div class="span-3 prepend-1 last">
              <input type="image" src="<?php echo get_theme_image("submit.png"); ?>" value="Enviar" class="submit" />
            </div>
            <div class="clear"></div>
          </form>
          <div class="clear"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="nav-bottom-wrapper">
  <div class="container">
    <div id="nav-bottom" class="span-16 textcenter">
      <?php wp_nav_menu(array("theme_location" => "bottom")) ?>
    </div>
    <div id="wp" class="span-8 last">
      <a href="http://wordpress.org" target="_blank" title="Powered by WordPress"><?php theme_image("wordpress.png") ?></a>
    </div>
    <div id="hacklab" class="span-24 last">      
      <a href="http://www.hacklab.com.br" target="_blank" title="Desenvolvido pelo hacklab">hacklab/</a>
    </div>
  </div>
</div>

</body>
</html>
