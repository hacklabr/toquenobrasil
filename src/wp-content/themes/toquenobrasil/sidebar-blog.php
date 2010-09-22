<div class="span-8 last">
  <ul class="widgets">
    <?php
        global $in_blog;
        if(is_blog()){
            dynamic_sidebar('blog-sidebar');
        }else{
            dynamic_sidebar('tnb-sidebar');
        }    
        ?>
  </ul>
</div>