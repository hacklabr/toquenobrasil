<?php
class ListControl{
    function ListControl($paged , $perpage , $found){
         $this->per_page = $perpage ;
        $this->max_num_pages = ceil($found / $this->per_page);
        $this->paged = $paged ;
    }
    
    function next_link($label = null ) {
    
        $pp = $this->paged == 0 ? 1 : $this->paged;
        
        if ($pp >= $this->max_num_pages)
            return;
        
        if (is_null($label)) $label = __('&lt;&lt; Older', 'tnb');
        $cleanURL = preg_replace('/(.+)(\/page\/\d{1,})(.*)/', '$1$3', $_SERVER['REQUEST_URI']);
        
        $cleanURL  = explode("?",$cleanURL);
        
        
        $url = $cleanURL[0] . 'page/' . ($pp + 1) . ( isset($cleanURL[1]) ? '?' . $cleanURL[1] : '' );
        
        echo "<div class='prev alignright'><a href='$url'>$label</a></div>";
        
    
    }
    
    function previous_link($label = null) {
    
        if ($this->paged == 0)
            return;
        
        if (is_null($label)) $label = __('Newer &gt;&gt;', 'tnb');
        
        $cleanURL = preg_replace('/(.+)(\/page\/\d{1,})(.*)/', '$1$3', $_SERVER['REQUEST_URI']);
        
        $pp = $this->paged == 0 ? 1 : $this->paged;
        $previous = $pp - 1;
        
        
        if ($previous > 1) {
            $cleanURL  = explode("?",$cleanURL);
            $url = $cleanURL[0] . 'page/' . ($pp - 1) . ( isset($cleanURL[1]) ? '?' . $cleanURL[1] : '' );
        } else {
            $url = $cleanURL;
        }
        
        echo "<div class='next alignleft'><a href='$url'>$label</a></div>";
        
    
    }
    
    function dropdown_pages() {

        if ($this->max_num_pages < 2)
            return;
        
        $current = array();
        $current[$this->paged] = 'selected';
        
        echo "<div class='alignright goto_page'>";
          echo "<select name='dropdown_posts_pages' id='dropdown_posts_pages'>";
            echo "<option value='0' {$current[0]}>1</option>";
            for ($i=2; $i<=$this->max_num_pages; $i++) {
                echo "<option value='$i' {$current[$i]}>$i</option>";
            }
          echo "</select>";
        echo "</div>";
        
        $cleanURL = preg_replace('/(.+)(\/page\/\d{1,})(.*)/', '$1$3', $_SERVER['REQUEST_URI']);
        
        $cleanURL = explode("?",$cleanURL);
        
        ?>
        <script>
        jQuery(document).ready(function() {
        
            jQuery('#dropdown_posts_pages').change(function() {
                
                var gotoPage = jQuery(this).val();
                var cleanURL_p1 = '<?php echo $cleanURL[0]; ?>';
                var cleanURL_p2 = '<?php echo ($cleanURL[1]? '?' . $cleanURL[1] :'' ); ?>';
                if (gotoPage > 1) {
                    window.location = cleanURL_p1+'/page/'+gotoPage + cleanURL_p2;
                } else {
                    window.location = cleanURL_p1+cleanURL_p2;
                }
                
            });
        
        });
        </script>
        <?php
        

    }
}