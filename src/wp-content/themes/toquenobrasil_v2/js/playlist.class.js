jQuery(document).ready(function(){

	// Classe de playlist normal //
    
    Playlist = function(instance, playlist, options) {
		var self = this;

		this.instance = instance; // String: To associate specific HTML with this playlist
		this.playlist = playlist; // Array of Objects: The playlist
		this.options = options; // Object: The jPlayer constructor options for this playlist

		this.current = 0;

		this.cssId = {
			jPlayer: "jquery_jplayer_",
			interface: "jp_interface_",
			playlist: "jp_playlist_"
		};
		this.cssSelector = {};

		jQuery.each(this.cssId, function(entity, id) {
			self.cssSelector[entity] = "#" + id + self.instance;
		});

		if(!this.options.cssSelectorAncestor) {
			this.options.cssSelectorAncestor = this.cssSelector.interface;
		}
        
		jQuery(this.cssSelector.jPlayer).jPlayer(this.options);

		jQuery(this.cssSelector.interface + " .jp-previous").click(function() {
			self.playlistPrev();
			jQuery(this).blur();
			return false;
		});

		jQuery(this.cssSelector.interface + " .jp-next").click(function() {
			self.playlistNext();
			jQuery(this).blur();
			return false;
		});
	};

	Playlist.prototype = {
		displayPlaylist: function() {
			var self = this;
			jQuery(this.cssSelector.playlist + " ul").empty();
			for (i=0; i < this.playlist.length; i++) {
				var listItem = (i === this.playlist.length-1) ? "<li class='jp-playlist-last clearfix'>" : "<li class='clearfix'>";
				listItem += "<a href='#' id='" + this.cssId.playlist + this.instance + "_item_" + i +"' tabindex='1'>"+ this.playlist[i].name +"</a>";

				// Create links to free media
				if(this.playlist[i].free) {
					var first = true;
                    var thisPlaylist = this;
					listItem += "<div class='jp-free-media alignright'>";
					jQuery.each(this.playlist[i], function(property,value) {
						if(jQuery.jPlayer.prototype.format[property]) { // Check property is a media format.
							if(first) {
								first = false;
							} else {
								listItem += " | ";
							}
							listItem += "<a id='" + self.cssId.playlist + self.instance + "_item_" + i + "_" + property + "' href='" + thisPlaylist.playlist[i].download_url + "' tabindex='1' class='button'>Download</a>";
						}
					});
				}

				listItem += "</li>";

				// Associate playlist items with their media
				jQuery(this.cssSelector.playlist + " ul").append(listItem);
				jQuery(this.cssSelector.playlist + "_item_" + i).data("index", i).click(function() {
					var index = jQuery(this).data("index");
					if(self.current !== index) {
						self.playlistChange(index);
					} else {
						jQuery(self.cssSelector.jPlayer).jPlayer("play");
					}
					jQuery(this).blur();
					return false;
				});

				
			}
		},
		playlistInit: function(autoplay) {
			if(autoplay) {
				this.playlistChange(this.current);
			} else {
				this.playlistConfig(this.current);
			}
		},
		playlistConfig: function(index) {
			jQuery(this.cssSelector.playlist + "_item_" + this.current).removeClass("jp-playlist-current").parent().removeClass("jp-playlist-current");
			jQuery(this.cssSelector.playlist + "_item_" + index).addClass("jp-playlist-current").parent().addClass("jp-playlist-current");
			this.current = index;
			jQuery(this.cssSelector.jPlayer).jPlayer("setMedia", this.playlist[this.current]);
		},
		playlistChange: function(index) {
			this.playlistConfig(index);
			jQuery(this.cssSelector.jPlayer).jPlayer("play");
		},
		playlistNext: function() {
			var index = (this.current + 1 < this.playlist.length) ? this.current + 1 : 0;
			this.playlistChange(index);
		},
		playlistPrev: function() {
			var index = (this.current - 1 >= 0) ? this.current - 1 : this.playlist.length - 1;
			this.playlistChange(index);
		}
	};
    
    
    
    // Classe de playlist resumida (home)
    
    PlaylistCompact = function(instance, playlist, options) {
		var self = this;

		this.instance = instance; // String: To associate specific HTML with this playlist
		this.playlist = playlist; // Array of Objects: The playlist
		this.options = options; // Object: The jPlayer constructor options for this playlist

		this.current = 0;

		this.cssId = {
			jPlayer: "jquery_jplayer_",
			interface: "jp_interface_",
			playlist: "jp_playlist_"
		};
		this.cssSelector = {};

		jQuery.each(this.cssId, function(entity, id) {
			self.cssSelector[entity] = "#" + id + self.instance;
		});

		if(!this.options.cssSelectorAncestor) {
			this.options.cssSelectorAncestor = this.cssSelector.interface;
		}
        
		jQuery(this.cssSelector.jPlayer).jPlayer(this.options);

		jQuery(this.cssSelector.interface + " .jp-previous").click(function() {
			self.playlistPrev();
			jQuery(this).blur();
			return false;
		});

		jQuery(this.cssSelector.interface + " .jp-next").click(function() {
			self.playlistNext();
			jQuery(this).blur();
			return false;
		});
	};

	PlaylistCompact.prototype = {

		playlistInit: function(autoplay) {
			
            var self = this;
            //evento de click da proxima musica
            
            jQuery(this.cssSelector.interface + ' .jp-next-details span').click(function() {
                var index = jQuery(this).data("index");
                
                if(self.current !== index) {
                    self.playlistChange(index);
                } else {
                    jQuery(self.cssSelector.jPlayer).jPlayer("play");
                }
                jQuery(this).blur();
                //return false;
			});
            
            
            
            if(autoplay) {
				this.playlistChange(this.current);
			} else {
				this.playlistConfig(this.current);
			}
            
            
		},
		playlistConfig: function(index) {
			
            var currentDetailsSelector = this.cssSelector.interface + ' .jp-current-details';
            var nextDetailsSelector = this.cssSelector.interface + ' .jp-next-details';
            
            //Coloca HTML da música atual
            
                //nome do artista
                jQuery(currentDetailsSelector + ' .jp-current-author_name').html(this.playlist[index].author_name);
                
                //foto do artista
                jQuery(currentDetailsSelector + ' .jp-current-author_image').html(this.playlist[index].author_image);
                
                //nome da música
                jQuery(currentDetailsSelector + ' .jp-current-title').html(this.playlist[index].name);
                
                //downloas
                jQuery(currentDetailsSelector + ' .jp-current-downloads span').html(this.playlist[index].downloads);
                
                //plays
                jQuery(currentDetailsSelector + ' .jp-current-plays span').html(this.playlist[index].plays);
                
                //botão de download
                if (this.playlist[index].free) {
                    jQuery(currentDetailsSelector + ' .jp-current-download_button a').attr('href', this.playlist[index].download_url);
                    jQuery(currentDetailsSelector + ' .jp-current-download_button').show()
                } else {
                    jQuery(currentDetailsSelector + ' .jp-current-download_button a').attr('href', '');
                    jQuery(currentDetailsSelector + ' .jp-current-download_button').hide();
                }
                
                //botão do artista
                jQuery(currentDetailsSelector + ' .jp-current-author_profile_button a').attr('href', this.playlist[index].author_url);
                
            //Coloca HTML da próxima música
            
                //nome da música
                var nextIndex = (index + 1 < this.playlist.length) ? index + 1 : 0;
                jQuery(nextDetailsSelector + ' span').data('index', nextIndex).html(this.playlist[nextIndex].name + ' - ' + this.playlist[nextIndex].author_name);
          
			this.current = index;
			jQuery(this.cssSelector.jPlayer).jPlayer("setMedia", this.playlist[this.current]);
            
		},
		playlistChange: function(index) {
			this.playlistConfig(index);
			jQuery(this.cssSelector.jPlayer).jPlayer("play");
		},
		playlistNext: function() {
			var index = (this.current + 1 < this.playlist.length) ? this.current + 1 : 0;
			this.playlistChange(index);
		},
		playlistPrev: function() {
			var index = (this.current - 1 >= 0) ? this.current - 1 : this.playlist.length - 1;
			this.playlistChange(index);
		}
	};
    

});
