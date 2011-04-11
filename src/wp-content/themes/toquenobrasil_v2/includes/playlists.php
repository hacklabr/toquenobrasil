<?php

function printFullPlayer($playlist) {

    if (!is_array($playlist))
        return false;
        
    
    ?>
    <script type="text/javascript">
    
    jQuery(document).ready(function(){
    
        var audioPlaylist = new Playlist("1", [
            
            <?php $first = true; foreach ($playlist as $song): ?>
            <?php if ($first) {$first = false;} else {echo ',';} ?>
            {
                name:'<span><?php echo addslashes($song['title'] ? $song['title'] : __('Sem Título', 'tnb')); ?></span>',
                free:<?php echo $song['download'] ? 'true' : 'false'; ?>,
                id: <?php echo $song['id']; ?>,
                downloads: <?php echo $song['downloads']; ?>,
                plays: <?php echo $song['plays']; ?>,
                author_name: '<?php echo addslashes($song['author_name']); ?>',
                author_url: '<?php echo $song['author_url']; ?>',
                author_image: '<?php echo str_replace("'", '"', $song['author_image']); ?>',
                mp3:"<?php bloginfo('siteurl'); ?>/play/?id=<?php echo $song['id']; ?>",
                download_url:"<?php bloginfo('siteurl'); ?>/download/?id=<?php echo $song['id']; ?>"
            }
            <?php endforeach; ?>

        ], {
            ready: function() {
                audioPlaylist.displayPlaylist();
                audioPlaylist.playlistInit(false); // Parameter is a boolean for autoplay.
            },
            ended: function() {
                audioPlaylist.playlistNext();
            },
            play: function() {
                jQuery(this).jPlayer("pauseOthers");
            },
            swfPath: tnb.baseurl+'/lib/jQuery.jPlayer.2.0.0/',
            supplied: "mp3"
        });
        

    });
    
    </script>
    <div class="jplayer" id="jquery_jplayer_1"></div>
        <div class="jp-audio">
            <div class="jp-type-playlist">
                <div id="jp_interface_1" class="jp-interface clearfix">
                    <ul class="jp-controls">
                        <li><a href="#" class="jp-previous" tabindex="1">previous</a></li>
                        <li><a href="#" class="jp-play" tabindex="1">play</a></li>
                        <li><a href="#" class="jp-pause" tabindex="1">pause</a></li>
                        <li><a href="#" class="jp-next" tabindex="1">next</a></li>
                    </ul>
                    <div class="jp-progress">
                        <div class="jp-seek-bar">
                            <div class="jp-play-bar"></div>
                        </div>
                    </div>
                    <ul class="jp-controls">
                        <li><a href="#" class="jp-mute" tabindex="1">mute</a></li>
                        <li><a href="#" class="jp-unmute" tabindex="1">unmute</a></li>
                    </ul>
                    <div class="jp-volume-bar">
                        <div class="jp-volume-bar-value"></div>
                    </div>
                    <div class="jp-current-time"></div>
                    <div class="jp-duration"></div>
                </div>
                <!-- .jp-controls -->
                <div class="clear"></div>
                <div id="jp_playlist_1" class="jp-playlist">
                    <ul>
                        <!-- The method Playlist.displayPlaylist() uses this unordered list -->
                        <li></li>
                    </ul>
                </div>
            </div>
            <!-- .jp-type-playlist -->
        </div>
        <!-- .jp-audio -->
    
    <?php
    

}



function printCompactPlayer($playlist) {

    if (!is_array($playlist))
        return false;
    
    ?>
    <script type="text/javascript">
    
    jQuery(document).ready(function(){
        
        var audioPlaylist = new PlaylistCompact("1", [
            
            <?php $first = true; foreach ($playlist as $song): ?>
            <?php if ($first) {$first = false;} else {echo ',';} ?>
            {
                name:'<?php echo addslashes($song['title'] ? $song['title'] : __('Sem Título', 'tnb')); ?>',
                free:<?php echo $song['download'] ? 'true' : 'false'; ?>,
                id: <?php echo $song['id']; ?>,
                downloads: <?php echo $song['downloads']; ?>,
                plays: <?php echo $song['plays']; ?>,
                author_url: '<?php echo $song['author_url']; ?>',
                author_name: '<?php echo addslashes($song['author_name']); ?>',
                author_image: '<?php echo str_replace("'", '"', $song['author_image']); ?>',
                mp3: "<?php bloginfo('siteurl'); ?>/play/?id=<?php echo $song['id']; ?>",
                download_url: "<?php bloginfo('siteurl'); ?>/download/?id=<?php echo $song['id']; ?>"
            }
            <?php endforeach; ?>

        ], {
            ready: function() {
                //audioPlaylist.setInitState();
                if (!this.alreadyInitialized) {
                    this.alreadyInitialized = true;
                    audioPlaylist.playlistInit(false); // Parameter is a boolean for autoplay.
                
                }
                
            },
            ended: function() {
                audioPlaylist.playlistNext();
            },
            play: function() {
                jQuery(this).jPlayer("pauseOthers");
            },
            swfPath: tnb.baseurl+'/lib/jQuery.jPlayer.2.0.0/',
            supplied: "mp3"
        });
        
    });
    
    </script>
    <div class="jplayer clearfix" id="jquery_jplayer_1"></div>
        <div class="jp-audio clearfix">
            <div class="jp-type-playlist clearfix">
                <div id="jp_interface_1" class="jp-interface clearfix">
                    
                    <div class="jp-current-details clearfix">
                        <div class="jp-current-author_image"></div>
                        <p>
                            <span class="jp-current-author_name"></span>
                            <br/>
                            <span class="jp-current-title"></span>
                            <span class="jp-current-downloads">
                                <span></span> <?php _e('downloads', 'tnb'); ?>
                            </span>
                            |
                            <span class="jp-current-plays">
                                <span></span> <?php _e('plays', 'tnb'); ?>
                            </span>
                        </p>
                        <p class="bottom">
                            <span class="jp-current-download_button">
                                <a href="" class="bg-yellow"><?php _e('Download', 'tnb'); ?></a>
                            </span>

                            <span class="jp-current-author_profile_button">
                                <a href="" class="bg-blue"><?php _e('Perfil do artista', 'tnb'); ?></a>
                            </span>
                        </p>
                    </div>
                    
                    <ul class="jp-controls clearfix">
                        <li><a href="#" class="jp-previous" tabindex="1">previous</a></li>
                        <li><a href="#" class="jp-play" tabindex="1">play</a></li>
                        <li><a href="#" class="jp-pause" tabindex="1">pause</a></li>
                        <li><a href="#" class="jp-next" tabindex="1">next</a></li>
                    </ul>
                    
                    <div class="jp-progress alignleft clearfix">
                        <div class="jp-seek-bar">
                            <div class="jp-play-bar"></div>
                        </div>
                    </div>                    

                    <div class="jp-current-time"></div>
                    <div class="jp-duration"></div>

                    <div class="jp-volume clearfix">
                        <ul class="jp-controls alignleft">
                            <li><a href="#" class="jp-mute" tabindex="1">mute</a></li>
                            <li><a href="#" class="jp-unmute" tabindex="1">unmute</a></li>
                        </ul>
                        <div class="jp-volume-bar">
                            <div class="jp-volume-bar-value"></div>
                        </div>
                    </div>

                    <div class="jp-next-details clear">
                        <?php _e('Próxima música:', 'tnb'); ?>
                        <span></span>
                    </div>
                </div>
                <!-- .jp-controls -->
            </div>
            <!-- .jp-type-playlist -->
        </div>
        <!-- .jp-audio -->
    
    <?php
    

}



// recebe um array com IDs de musicas e retorna um array playlist pronto para invocar o player
function ids2playlist($ids) {

    if (!is_array($ids))
        return false;
    
    $result = array();
    
    foreach ($ids as $id) {
    
        if (!is_numeric($id))
            continue;
        
        $downloads = get_post_meta($id, '_downloads', true);
        if (!$downloads) $downloads = 0;
        
        $plays = get_post_meta($id, '_plays', true);
        if (!$plays) $plays = 0;
        
        $p = get_post($id);
        
        $title = $p->post_title;
        
        $download = get_post_meta($id, '_download', true);
        
        $author_url = get_author_posts_url($p->post_author);
        
        $author_image = get_avatar($p->post_author);
        
        global $wpdb;
        $author_name = $wpdb->get_var($wpdb->prepare("SELECT display_name FROM $wpdb->users WHERE ID = %d", $p->post_author));
        
        $result[] = array(
            'title' => $title,
            'download' => $download,
            'downloads' => $downloads,
            'author_url' => $author_url,
            'author_name' => $author_name,
            'author_image' => $author_image,
            'id' => $id,
            'plays' => $plays
        );
    
    }
    
    return $result;

}

?>
