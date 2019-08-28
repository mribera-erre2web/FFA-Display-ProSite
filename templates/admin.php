<br>
<h2>Insert leagueID</h2>
<form name="update" method="post">
    <input type="text" name="leagueId" value="<?=get_option('leagueId'); ?>">
    <button name="submit" type="submit">Update Data</button> - Last Update <?=get_option('lastUpdate');?>
</form>

<h3 style="color:red">Don't close the page if reload is long. It will take a wile as there are N request to ESL API for player Info</h3>
<?php
global $wpdb;
$match_week_table_name = $wpdb->prefix . 'ffa_stats_match_week';
$player_table_name = $wpdb->prefix . 'ffa_stats_player';
$match_player_table_name = $wpdb->prefix . 'ffa_stats_match_player';

$match_week = $wpdb->get_results("SELECT * FROM `".$match_week_table_name."`");
$player = $wpdb->get_results("SELECT * FROM `".$player_table_name."`");
$match_player = $wpdb->get_results("SELECT * FROM `".$match_player_table_name."`");

?>
<h3>Table list</h3>
<h4>Match Week</h4>
<?php
foreach ($match_week as $mw){
    echo "DATE: ".$mw->beginAt."<br> MATCH N.:".$mw->position."<br> MATCH STATE:".$mw->state."<br> MATCH WEEK:".$mw->week;
    echo "<br><br>";
}
?>
<h4>Player</h4>
<?php
foreach ($player as $pl){
    echo "PLAYER ID:".$pl->playerId."<br> PLAYER NICK:".$pl->nickname."<br> PLAYER PROFILE:".$pl->profileImg."<br> PLAYER REGION:".$pl->region;
    echo "<br><br>";
}
?>
<h4>Match Player</h4>
<?php
foreach ($match_player as $mp){
    echo "PLAYER ID: ".$mp->playerId."<br> MATCH ID: ".$mp->matchId."<br> WEEK ID: ".$mp->weekId."<br> PLAYER MATCH: ".$mp->points."<br> PLAYER MATCH POSITION: ".$mp->position;
    echo "<br><br>";
}

$init = 0;

if(isset($_POST['submit'])){
    update_option('leagueId', $_POST['leagueId']);
    $json = file_get_contents('https://api.eslgaming.com/play/v1/leagues/'.$_POST['leagueId'].'/results');
    $decoded_data = json_decode($json);
    $match_week_table_name = $wpdb->prefix . 'ffa_stats_match_week';
    $player_table_name = $wpdb->prefix . 'ffa_stats_player';
    $match_player_table_name = $wpdb->prefix . 'ffa_stats_match_player';
    $query = $wpdb->query("DELETE FROM `".$match_week_table_name."`");
    $query = $wpdb->query("DELETE FROM `".$player_table_name."`");
    $query = $wpdb->query("DELETE FROM `".$match_player_table_name."`");
    foreach ($decoded_data as $data):
        $query = $wpdb->query("INSERT INTO `".$match_week_table_name."` (`id`,`beginAt`,`position`,`state`,`week`) VALUES (NULL, '".$data->beginAt."', '".$data->position."', '".$data->state."', '".$data->round."');");
        foreach ($data->participants as $player):
            if($init==0) {
                $player_data_json = file_get_contents('https://api.eslgaming.com/play/v1/users/' . $player->id . '/basicprofile');
                $player_data = json_decode($player_data_json);
                $query = $wpdb->query("INSERT INTO `" . $player_table_name . "` (`id`,`playerId`,`nickname`, `profileImg`,`region`) VALUES (NULL, '" . $player->id . "', '" . $player_data->nickname . "', '" . $player_data->photo . "', '" . $player_data->region . "');");
            }
            $query = $wpdb->query("INSERT INTO `".$match_player_table_name."` (`id`,`playerId`,`matchId`, `weekId`,`points`,`position`) VALUES (NULL, '". $player->id ."', '". $data->position ."', '". $data->round ."', '". $player->points[0] ."', '". $player->place ."');");
        endforeach;
        $init = 1;
    endforeach;
    update_option('lastUpdate', date("Y-m-d H:i:s"));
}
