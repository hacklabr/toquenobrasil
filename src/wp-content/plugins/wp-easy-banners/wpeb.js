function wpeb_print_banners_local(local, logged) {
    
    if (wpeb[local].length == 0)
    	return false;
    
    
    document.write ( wpeb_get_random_banner_html(wpeb[local], logged) );
    
}

function wpeb_print_banners_local_tempo(local, logged, tempo) {
	
    if (wpeb[local].length == 0)
	return false;
	
    tempo = tempo * 1000;
    
    document.write("<span id='banner_local_" + local + "'>");
    document.write ( wpeb_get_random_banner_html(wpeb[local] , logged) );
    document.write("</span>");
    
    if (wpeb[local].length > 1)
        wpeb_timers[local + '_' + tempo + '_' + logged] = setTimeout('wpeb_rotaciona_banner_por_tempo("' + local + '", ' + tempo + ', ' + logged + ')', tempo);

}

function wpeb_rotaciona_banner_por_tempo(local, tempo, logged) {
	

    document.getElementById('banner_local_' + local).innerHTML = wpeb_get_random_banner_html(wpeb[local], logged);
    
    wpeb_timers[local + '_' + tempo + '_' + logged] = setTimeout('wpeb_rotaciona_banner_por_tempo("' + local + '", ' + tempo + ', ' + logged + ')', tempo);

}


function img_tag(banner, logged ){
	
	var image = '<img title="' + banner['name'] + '" alt="' + banner['name'] + '" src="' + wpeb_baseurl + 'view.php?banner=' + banner['file'] + '&id=' + banner['ID'] + '&posicao_ID=' + banner['posicao_ID'] + '&logged=' + logged + '" />';

	return htmlResponse = '<a title="' + banner['name'] + '" target="' + banner['target'] + '" href="' + wpeb_baseurl + 'click.php?banner=' + banner['ID'] + '&posicao_ID=' + banner['posicao_ID'] + '">' + image + '</a>';
}

function swf_object(banner, logged){
	var w = banner['width'];
	var h = banner['height'];
	var swfsrc =  banner['file'];
    var clickTag = wpeb_baseurl + 'click.php?banner=' + banner['ID'] + '%26posicao_ID=' + banner['posicao_ID'];
	var fsrc = wpeb_baseurl + 'view.php?banner=pixel.gif&id=' + banner['ID'] + '&posicao_ID=' + banner['posicao_ID'] + '&logged=' + logged;
    var image = '<img src="' + fsrc + '" style="position:absolute; left:-99; top:-99" />';
	var swf = '<object width=\''+w+'\' height=\''+h+'\'><param name=\'movie\' value=\''+swfsrc+'?clickTag=' + clickTag +'\'><embed src=\''+swfsrc+'?clickTag='+clickTag+'\' width=\''+w+'\' height=\''+h+'\'></embed></object>';
		
	return image+swf;
	
}


function wpeb_get_random_banner_html(array_of_banners, logged) {
	
    var numberOfBanners = array_of_banners.length;

    var htmlResponse = '';
    
    //var r = Math.floor(Math.random()*numberOfBanners);
    
    var numberOfBanners = array_of_banners.length;
    var total = 0;
    for( var i = 0 ; i<array_of_banners.length ; i++){
        total = total + parseFloat(array_of_banners[i]['peso']);
        array_of_banners[i]['sum'] = total;
    }
    
    var r = Math.floor(Math.random()*(total+1));
//    alert("POS:"+array_of_banners[0]['posicao_ID'] +  '\nSORT_PESO = ' + r )
    while( r  <= total ){
        for( var i = 0 ; i<array_of_banners.length ; i++){
            if(array_of_banners[i]['sum'] == r){
                banner = array_of_banners[i];
                // force end the loop;
                r=total*10;
                break;
            }
        }
        r++;
    }
    
    if(banner['is_image'] == 1)
    	htmlResponse = img_tag(banner,logged);
    else{
    	htmlResponse = swf_object(banner,logged);
    }
    
    if (banner['html'] != '') htmlResponse += banner['html'];
    
    return htmlResponse;
    
}
