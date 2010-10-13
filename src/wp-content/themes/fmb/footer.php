      <div class="clear"></div>
      <div id="footer" class="span-24">
        <div id="nav-bottom" class="span-10 prepend-1">
            <?php wp_nav_menu(array("theme_location" => "bottom")) ?>        
        </div>
        <div class="span-13 last">
          <?php theme_image("contato.png", array("id"=>"contato-title")); ?>
          
          <?php
          global $contact_us_return;
          if(is_array($contact_us_return)){
                echo "<div class='span-10 prepend-2'>";
                  print_msgs($contact_us_return, 'stay', 'scm_contact');
                echo "</div>";  
                ?>
                <script>
              		jQuery.scrollTo('#scm_contact', 800);
              	</script>
                <?php 
            } 
              
          ?>
          	
          <form method='POST'  name='contact_us' id='contact_us_form' >
          	<?php
              	global $current_user;
            ?>
          	<input type="hidden" name='contact_us' value='1'>
            <div class="span-6 prepend-2 " >
              <textarea class='auto_clean' name='contact_message' title='<?php _e('Menssagem','tnb');?>'><?php echo isset($_POST['contact_message'])?$_POST['contact_message']: __('Menssagem', 'tnb'); ?></textarea>
            </div>

            <div class="span-4 last">
              <input type="text"  name="contact_name" value="<?php echo isset($_POST['contact_name'])?$_POST['contact_name']: (is_string($v = $current_user->banda) ? $v : __('Nome', 'tnb')); ?>" title='<?php _e('Nome','tnb');?>'  class="text auto_clean" />
            </div>
            <div class="span-4 last">
              <input type="text" name="contact_email" value="<?php echo isset($_POST['contact_email'])?$_POST['contact_email']:  (is_string($v = $current_user->user_email) ? $v : __('E-mail', 'tnb')); ?>" title='<?php _e('E-mail','tnb');?>'  class="text auto_clean" />
            </div>
            <div class="span-4 last">
              <input type="text" name="contact_site" value="<?php echo isset($_POST['contact_site'])?$_POST['contact_site']: (is_string($v = $current_user->site) ? $v : __('E-mail', 'tnb')); ?>" title='<?php _e('http://','tnb');?>' class="text auto_clean" />
            </div>
            <div class="span-3 prepend-1 last">
              <input type="image" src="<?php echo get_theme_image("submit.png"); ?>" value="<?php _e('Enviar','tnb');?>" class="submit" />
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
    <div id="wp">
      <p><a href="http://www.toquenobrasil.com.br" target="_blank" title="Toque no Brasil">toque no brasil</a> | <a href="http://www.hacklab.com.br" target="_blank" title="Desenvolvido pelo hacklab">hacklab/</a> <a href="http://wordpress.org" target="_blank" title="Powered by WordPress"><?php theme_image("wordpress.png") ?></a></p>
    </div>
  </div>
</div>

</body>
</html>
