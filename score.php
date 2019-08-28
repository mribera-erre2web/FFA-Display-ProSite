<?php
function render($all){
    global $wpdb;

    if($all=="true"){

        $player_table_name = $wpdb->prefix . 'ffa_stats_player';
        $match_player_table_name = $wpdb->prefix . 'ffa_stats_match_player';
        $query_match_player = $wpdb->get_results("SELECT * FROM `".$match_player_table_name."` WHERE `weekId`=1;");

        $total = array();

        foreach ($query_match_player as $player){
            array_push($total, array('playerId' => $player->playerId, 'points' => $player->points));
        }

        $query_match_player = $wpdb->get_results("SELECT `playerId`, SUM(`points`) AS points FROM `".$match_player_table_name."` GROUP BY `playerId` ORDER BY `points` DESC;");

        $position = 1;
        ?>
        <section>
            <table class="standings">
                <tbody>
                <tr>
                    <th colspan="1" class="header rank ">#</th>
                    <th colspan="2" class="header name">Player</th>
                    <th colspan="1" class="header points">Points</th>
                </tr>
                <?php foreach ($query_match_player as $player):
                    $player_data = $wpdb->get_results("SELECT * FROM `".$player_table_name."` WHERE `playerId`=".$player->playerId.";")[0];
                    ?>
                    <tr class="competition_group">
                        <td class="field tier1 rank" style="text-align: center;"><?=$position?></td>
                        <td class="field tier1 logo">
                            <a href="https://play.eslgaming.com/italy/player/<?=$player_data->playerId?>/" target="_blank">
                                <img src="<?=$player_data->profileImg?>">
                            </a>
                        </td>
                        <td class="field tier1 name">
                            <a href="https://play.eslgaming.com/italy/player/<?=$player_data->playerId?>/" target="_blank">
                                <img class="country" src="https://www.esl-one.com/images/icons/flags_16/<?php if($player_data->region!=""): echo strtolower($player_data->region); else: echo "eu"; endif; ?>.png"> <?=$player_data->nickname?>
                            </a>
                        </td>
                        <td class="field tier1 points"><?php if(isset($player->points)): echo $player->points; else: echo "0"; endif;?></td>
                    </tr>
                <?php
                $position++;
                endforeach;?>
                </tbody>
            </table>
        </section>
        <?php

    }else{

        if(isset($_GET['week'])){
            $week = $_GET['week'];
        }else{
            $week = 1;
        }

        if(isset($_GET['match'])){
            $match = $_GET['match'];
        }else{
            $match = 1;
        }

        $match_week_table_name = $wpdb->prefix . 'ffa_stats_match_week';
        $player_table_name = $wpdb->prefix . 'ffa_stats_player';
        $match_player_table_name = $wpdb->prefix . 'ffa_stats_match_player';
        $query_match = $wpdb->get_results("SELECT * FROM `".$match_week_table_name."`;");
        $query_match_player = $wpdb->get_results("SELECT * FROM `".$match_player_table_name."` WHERE `matchId`=".$match." AND `weekId`=".$week.";");
        $round_check = 0;
        ?>

        <div class="eslpro_container">
            <ffa-ranking>
                <section class="row">
                    <div class="col">
                        <div class="weeksselect">
                            <div class="title">Week</div>
                            <div class="days">
                                <?php foreach($query_match as $data):
                                    if($round_check != $data->week):
                                        ?>
                                        <a class="day skew nextday" href="?week=<?=$data->week?>">
                                            <div class="day_inner unskew <?php if($data->week==$week): ?>active<?php endif;?>">
                                                <span><?=$data->week?></span>
                                            </div>
                                        </a>
                                    <?php
                                    endif;
                                    $round_check = $data->week;
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <!--
                        DISABLED GROUPS FOR FASTER IMPLEMENTATION AS PS CHAMPIONSHIP WILL HAVE ONLY ONE GROUP
                    -->
                </section>
                <?php
                $query_match = $wpdb->get_results("SELECT * FROM `".$match_week_table_name."` WHERE `week`=".$week.";");
                ?>

                <ffa-rankings>
                    <section>
                        <div class="bigvideos_container">
                            <?php foreach ($query_match as $data): ?>
                                <a class="bigvideo-item"style="height:50px!important" href="?week=<?=$data->week?>&match=<?=$data->position?>">
                                    <div class="bigvideo-item-inner">
                                        <div class="title <?php if($data->position==$match): ?>active<?php endif;?>">
                                            <div class="title_inner">
                                                <?php $date = new DateTime($data->beginAt); echo $date->format('Y-m-d H:i')?>
                                                <div class="title_inner_inner ng-binding">Match #<?=$data->position?> - <?=$data->state?></div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <section>
                        <table class="standings">
                            <tbody>
                            <tr>
                                <th colspan="1" class="header rank ">#</th>
                                <th colspan="2" class="header name">Player</th>
                                <th colspan="1" class="header points">Points</th>
                            </tr>
                            <?php foreach ($query_match_player as $player):
                                $player_data = $wpdb->get_results("SELECT * FROM `".$player_table_name."` WHERE `playerId`=".$player->playerId.";")[0];
                                ?>
                                <tr class="competition_group">
                                    <td class="field tier1 rank" style="text-align: center;"><?php if(isset($player->position)): echo $player->position; else: echo "0"; endif;?></td>
                                    <td class="field tier1 logo">
                                        <a href="https://play.eslgaming.com/italy/player/<?=$player_data->playerId?>/" target="_blank">
                                            <img src="<?=$player_data->profileImg?>">
                                        </a>
                                    </td>
                                    <td class="field tier1 name">
                                        <a href="https://play.eslgaming.com/italy/player/<?=$player_data->playerId?>/" target="_blank">
                                            <img class="country" src="https://www.esl-one.com/images/icons/flags_16/<?php if($player_data->region!=""): echo strtolower($player_data->region); else: echo "eu"; endif; ?>.png"> <?=$player_data->nickname?>
                                        </a>
                                    </td>
                                    <td class="field tier1 points"><?php if(isset($player->points)): echo $player->points; else: echo "0"; endif;?></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </section>
                </ffa-rankings>
            </ffa-ranking>
        </div>

    <?php
    }
}
?>
