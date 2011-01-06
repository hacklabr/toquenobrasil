<?php


add_action('admin_menu', 'itsnoon_add_export_users_menu');

function itsnoon_add_export_users_menu() {

    add_users_page('Export Users', 'Export', 'delete_user', 'export_users', 'itsnoon_export_users_page');

}


function itsnoon_export_users_page() {


    ?>
    <div class="wrap">
    
    <h2>Click the button to Export users</h2>
    
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" >
    <input type="hidden" name="itsnoon_export_users" value="now">
    <input type="submit" value="Export Users">
    </form>
    
    
    </div>
    <?php


}

add_action('init', 'itsnoon_do_export_users');

function itsnoon_do_export_users() {

    if (!isset($_POST['itsnoon_export_users']) || $_POST['itsnoon_export_users'] !== 'now')
        return;
    
    
    global $wpdb, $current_blog;
    $blog_id = $current_blog->blog_id;
    $users_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->users}");
    
    $outputUsers = array();
    
    foreach ($users_ids as $u) {
    
        $user = get_userdata($u);
        $userObj = new WP_User( $u );
        $outputUsers['Role'][$u] = $userObj->roles[0];
        foreach($user as $key => $value) {
        
            $outputUsers[$key][$u] = $value;
        
        }
    
    }
    
    // Header
    header('Expires: 0');
    header('Cache-control: private');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera
    header("Content-type: application/x-msexcel");                    // This should work for the rest 
    header('Content-disposition: attachment; filename=TNB_Users_' . date('Y-m-d') . '.xls');
    
    
    // table header
    
    echo '<table>';
    
    echo '<tr>'; echo '</tr>';
    foreach ($outputUsers as $columnName => $v) {
    
        echo "<th>$columnName</th>";
    
    }
    
    echo '</tr>';
    
    foreach ($users_ids as $id) {
    
        echo '<tr>';
    
        foreach ($outputUsers as $columnName => $v) {
    
            echo '<td>' . $v[$id] . '</td>';
        
        }
        
        echo '</tr>';
    
    }

    echo '</table>';
    
    exit;
    

}


?>
