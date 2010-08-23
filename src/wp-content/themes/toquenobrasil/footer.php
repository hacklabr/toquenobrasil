      <div class="clear"></div>
      <div id="footer" class="span-24">
        <div class="span-11">
          <div id="socialmedia" class="span-3">
            <a href=""><?php theme_image("rss.png") ?></a>
            <br>
            <a href=""><?php theme_image("facebook.png") ?></a>
            <br>
            <a href=""><?php theme_image("flickr.png") ?></a>
            <br>
            <a href=""><?php theme_image("twitter.png") ?></a>
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
          <form>
            <div class="span-6 prepend-2">
              <textarea>Mensagem</textarea>
            </div>

            <div class="span-4 last">
              <input type="text" name="" value="Nome" id="" class="text" />
            </div>
            <div class="span-4 last">
              <input type="text" name="" value="E-mail" id="" class="text" />
            </div>
            <div class="span-4 last">
              <input type="text" name="" value="http://" id="" class="text" />
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
      <?php wp_nav_menu(array("theme_location" => "bottom")) ?> contato@toquenobrasil.com.br
    </div>
    <div id="wp" class="span-8 last">
      <a href="http://wordpress.org" title="Powered by WordPress"><?php theme_image("wordpress.png") ?></a>
    </div>
    <div id="hacklab" class="span-24 last">      
      <a href="http://www.hacklab.com.br" title="Desenvolvido pelo hacklab">hacklab/</a>
    </div>
  </div>
</div>

</body>
</html>