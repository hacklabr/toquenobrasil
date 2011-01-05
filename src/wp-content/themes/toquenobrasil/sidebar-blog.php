<div class="span-8 last">
  <div class="widgets">
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
