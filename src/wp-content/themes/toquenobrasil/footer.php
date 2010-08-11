      <div class="clear"></div>
      <div class="prepend-top"></div>
      <div id="footer" class="span-24">
        <div class="span-11">
          <div id="socialmedia" class="span-3">
            <a href=""><?php theme_image("rss.png") ?></a>
            <br>
            <a href=""><?php theme_image("facebook.png") ?></a>
            <br>
            <a href=""><?php theme_image("wordpress.png") ?></a>
            <br>
            <a href=""><?php theme_image("twitter.png") ?></a>
          </div>
          <div class="span-8 last">
            <div id="twit">
              <div class="content textcenter prepend-top">
                <img src="http://a2.twimg.com/profile_images/1099073678/0809-TwitterIcon_bigger.png"/>                
                <br/>
                <span class="author">threadless</span>
                <span class="twit">Ac. Sagittis quis facilisis sit ultrices. Porta scelerisque ac mus et hac, vut elementum porta mattis dapibus aenean nisi cras! Sit vut dic.</span>
                <span class="twit-meta">42 minutes ago via HootSuite</span>
              </div>
            </div>
          </div>
        </div>
        <div class="span-13 last">
          <?php theme_image("contato.png", array("id"=>"contato-title")); ?>
          <form>
            <div class="span-6 prepend-2">
              <label></label>
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
      <?php wp_nav_menu(array("theme_location" => "bottom")) ?>
    </div>
    <div id="hacklab" class="span-8 last">
      <a href="http://www.hacklab.com.br">hacklab/</a>
    </div>
  </div>
</div>

</body>
</html>