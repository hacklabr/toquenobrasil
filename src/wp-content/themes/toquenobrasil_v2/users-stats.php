<?php 

function getEmptyObjectsWithDaysAsIndex($sdate, $fdate = null, $format = 'Y-m-d'){
    
    if(!$fdate) $fdate = date("Y-m-d");
    
    $interval = DateInterval::createFromDateString("1 day");
    $_sdate = new DateTime($sdate); 
    $_fdate = new DateTime($fdate);
    
    $period = new DatePeriod($_sdate, $interval, $_fdate);

    $result = array();
    foreach($period as $day){
        $obj = new stdClass();
        $obj->object_id = '';
        $obj->count = 0;
        $obj->day = (string)$day->format($format);
        
        
        $result[(string)$day->format($format)] = $obj;
    }
    $obj = new stdClass();
    $obj->object_id = '';
    $obj->count = 0;
    $obj->day = $fdate;
    
    
    $result[$fdate] = $obj;
    
    return $result;
}



wp_enqueue_script('jquery-flot',TNB_URL.'/js/flot/jquery.flot.js', array('jquery'));
    
get_header(); 
global $wp_query, $wpdb;
$profileuser = $wp_query->queried_object;

$plays = $downloads = array();
$totais = array();

$_musicas = tnb_get_artista_musicas($profileuser->ID);
$musicas = array();
foreach($_musicas as $mus)
    $musicas[] = $mus->ID;

$music_ids = implode(',', $musicas);

// define a data final: $_GET[fdate] ou CURRENT_DATE()
$where = '';
if(isset($_GET['fdate']) && preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $_GET['fdate'])){
    $__fdate = $fdate = preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1",$_GET['fdate']);
    $where .= " AND day <= '$fdate'";
}else{
    $__fdate = date('Y-m-d');
    $where .= " AND day <= CURRENT_DATE()";
}

// define a data inicial: $_GET[sdate] ou CURRENT_DATE() - 30 dias
if(isset($_GET['sdate']) && preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $_GET['sdate'])){
    $__sdate = $sdate = preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/","$3-$2-$1",$_GET['sdate']);
    $where .= " AND day >= '$sdate'";
}else{
    $start = new DateTime();
    $start->setTimestamp(time()-3600*24*30);
    $__sdate = $start->format('Y-m-d');
    
    $where .= " AND day >= '$__sdate'";
}



$profile_views = $__new_array = getEmptyObjectsWithDaysAsIndex ($__sdate, $__fdate);

$SQL_profile_views = "SELECT day, SUM(count) as count FROM {$wpdb->prefix}tnb_stats WHERE type = 'profile_views' AND object_id = $profileuser->ID $where GROUP BY day ORDER BY day";

$_profile_views = $wpdb->get_results($SQL_profile_views);

foreach($_profile_views as $r)
    $profile_views[$r->day] = $r;

if($music_ids){
    // se $music_ids estiver vazio, o artista nao tem músicas, logo não precisa executar estas queries
    
    $SQL_plays = "SELECT object_id, day, SUM(count) as count FROM {$wpdb->prefix}tnb_stats WHERE type = 'plays' AND object_id IN ($music_ids) $where GROUP BY object_id, day ORDER BY day";
    $SQL_downloads = "SELECT object_id, day, SUM(count) as count FROM {$wpdb->prefix}tnb_stats WHERE type = 'downloads' AND object_id IN ($music_ids) $where GROUP BY object_id, day ORDER BY day";

    $_plays = $wpdb->get_results($SQL_plays);
    $_downloads = $wpdb->get_results($SQL_downloads);
    
    $plays['total'] = $__new_array;
    $downloads['total'] = $__new_array;
    foreach($_plays as $r){
        $plays['total'][$r->day]->count += $r->count;
        
        if(!isset($plays[$r->object_id]))
            $plays[$r->object_id] = $__new_array;
        
        $plays[$r->object_id][$r->day] = $r;
        
        
        
        $totais['plays'] = isset($totais['plays']) ? $totais['plays'] + $r->count : $r->count;
        $totais['musicas'][$r->object_id]['plays'] = isset($totais['musicas'][$r->object_id]['plays']) ? $totais['musicas'][$r->object_id]['plays'] + $r->count : $r->count;
    }
    
    foreach($_downloads as $r){
        $downloads['total'][$r->day]->count += $r->count;
        
        
        if(!isset($downloads[$r->object_id]))
            $downloads[$r->object_id] = $__new_array;

        $downloads[$r->object_id][$r->day] = $r;
        
        
        $totais['downloads'] = isset($totais['downloads']) ? $totais['downloads'] + $r->count : $r->count;
        $totais['musicas'][$r->object_id]['downloads'] = isset($totais['musicas'][$r->object_id]['downloads']) ? $totais['musicas'][$r->object_id]['downloads'] + $r->count : $r->count;
    }
    
}

$total_views = 0;
?>
<script type="text/javascript">
var total = 'total';
var views = [
<?php
// criando o array de profileviews
foreach ($profile_views as $data) {
    $total_views += $data->count;
    
    echo '[' . strtotime($data->day) . '000,' . $data->count . '],';
}
?>
];

var plays = {
<?php
// criando o array de dias, views
foreach ($plays as $mid => $data) {
    echo "
    'mus_$mid': {'data': [";
    foreach($data as $m)
        echo '[' . strtotime($m->day) . '000,' . $m->count . '],';
    echo '
    ]},';
}
?>    
};


var downloads = {
<?php
// criando o array de dias, views
foreach ($downloads as $mid => $data) {
    echo "
    'mus_$mid': {'data': [";
    foreach($data as $m)
        echo '[' . strtotime($m->day) . '000,' . $m->count . '],';
    echo '
    ]},';
}
?>    
};


jQuery(document).ready(function(){
    jQuery.plot(jQuery("#profile-views"), [{data: views, color:3}], { 
        xaxes: [ { mode: 'time' , timeformat: "%d/%m/%y" } ]
    });
    
    function plotAccordingToChoices() {
        var data_plays = [];
        var data_downloads = [];

        jQuery(".music-choice:checked").each(function () {
            
            var key = jQuery(this).attr("name");
            
            if (key && plays[key])
                data_plays.push(plays[key]);
            
            if (key && downloads[key])
                data_downloads.push(downloads[key]);
        });

        
        if (data_plays.length > 0)
            jQuery.plot(jQuery("#music-plays"), data_plays, {
                xaxis: { mode: 'time' , timeformat: "%d/%m/%y" }
            });
        
        
        if(data_downloads.length > 0)
            jQuery.plot(jQuery("#music-downloads"), data_downloads, {
                xaxis: { mode: 'time' , timeformat: "%d/%m/%y" }
            });
    }

    jQuery(".music-choice").change(plotAccordingToChoices)
    plotAccordingToChoices();
    
    jQuery('#sdate').datepicker();
    jQuery('#fdate').datepicker();
    
});
</script>


<section id="users" class="profile grid_16 box-shadow clearfix">
    <header class="clearfix">
        <h1 class="profile-name">
            <span class="bg-yellow"><?php echo $profileuser->display_name; ?></span>
        </h1>
        <form>
            data inicial: <input name="sdate" id='sdate' value="<?php echo isset($_GET['sdate']) ? $_GET['sdate'] : '' ?>" /> data final: <input name="fdate" id='fdate' value="<?php echo isset($_GET['fdate']) ? $_GET['fdate'] : '' ?>" />
            <input type="submit" value="filtrar"/>
        </form>
    </header>
    
    <section class="content">
        <h4><?php echo $total_views.' '. __('visualizações do perfil no período','tnb'); ?> </h4>
        <div id='profile-views' class='graph' style='width:100%; height:250px;'></div>
    </section>
    <div class="grid_3">
        <h4>músicas</h4>
        <label title="Total:plays: <?php echo $totais['plays']?><br/> downloads: <?php echo $totais['downloads'] ?>" class="hltip"><input type="checkbox" class="music-choice" checked="checked" name="mus_total"> Total</label><br/>
        <?php foreach($_musicas as $m): ?>
        <label title="<?php echo $m->post_title; ?>:plays: <?php echo $totais['musicas'][$m->ID]['plays'] ?><br/> downloads: <?php echo $totais['musicas'][$m->ID]['downloads'] ?>" class="hltip"><input type="checkbox" class="music-choice" name="mus_<?php echo $m->ID; ?>"> <?php echo $m->post_title; ?></label><br/>
        <?php endforeach; ?>
    </div>
    <div class="grid_12 last">
        <h4>total de plays no período: <?php echo $totais['plays']?></h4>
        <div id='music-plays' class='graph' style='width:100%; height:250px;'></div>
        <h4>total de downloads no período: <?php echo $totais['downloads']?></h4>
        <div id='music-downloads' class='graph' style='width:100%; height:250px;'></div>
    </div>
</section>
<?php get_footer(); ?>