<?php
$countLines = count($this->request->data['ScheduleLine']);
$pageScope = 'ScheduleDay' . $this->request->data['ScheduleDay']['id'];
if ($owner == $loginMember['id']) {
    $ownerCheck = true;
} else {
    $ownerCheck = false;
}
if ($ownerCheck && ($countLines > 1)) {
    $sortCheck = true;
} else {
    $sortCheck = false;
}
if (!$isAjax) {
    echo '<div id="map" style="width: 100%; height: 300px;"></div>';
}
?>
<dl class="list3 table" id="<?php echo $pageScope; ?>">
    <?php
    $baseTime = strtotime($this->request->data['Schedule']['time_start']);
    $weekDays = array(
        1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 6 => '六', 7 => '日'
    );
    $theDay = strtotime('+' . ($this->request->data['ScheduleDay']['sort'] - 1) . ' days', $baseTime);
    ?>
    <dt class="table-cell fillet_left">
    <strong>第<?php echo $this->request->data['ScheduleDay']['sort']; ?>天</strong>
    </dt>
    <dd class="table-cell fillet_right pointHotel">
        <h3><span><?php echo date('Y-m-d', $theDay); ?> (<?php echo $weekDays[date('N', $theDay)]; ?>) </span><?php echo $this->request->data['ScheduleDay']['title']; ?>
            <?php
            if ($ownerCheck) {
                echo '<div class="btn-group float-r">';
                if ($loginMember['id'] > 0) {
                    echo $this->Html->link('<i class="icon-leaf"></i> 新增記事', "/schedule_notes/add/{$this->request->data['Schedule']['id']}/{$this->request->data['ScheduleDay']['id']}", array(
                        'title' => '為這個行程加入待辦事項、必看、必買等等',
                        'class' => 'btn dialogSchedule',
                        'escape' => false,
                    ));
                }
                echo $this->Html->link('<i class="icon-pencil"></i> 編輯', '/schedule_days/edit/' . $this->request->data['ScheduleDay']['id'], array(
                    'title' => '編輯行程表',
                    'class' => 'btn',
                    'escape' => false,
                ));
                echo $this->Html->link('<i class="icon-globe"></i> 地圖模式', '/schedules/map_mode/' . $this->request->data['Schedule']['id'] . '/' . $this->request->data['ScheduleDay']['id'], array(
                    'title' => '透過地圖模式模式編輯這一天',
                    'class' => 'btn',
                    'escape' => false,
                ));
                echo $this->Html->link('<i class="icon-remove"></i> 刪除', '/schedule_days/delete/' . $this->request->data['ScheduleDay']['id'], array(
                    'title' => '刪除本日',
                    'class' => 'btn',
                    'escape' => false,
                        ), '確定要刪除？');
                echo $this->Html->link('<i class="icon-list-alt"></i> 匯入', '/schedule_days/quick_day/' . $this->request->data['ScheduleDay']['id'], array(
                    'title' => '快速新增單日行程',
                    'class' => 'btn dialogSchedule',
                    'escape' => false,
                ));
                echo '</div>';
            }
            ?>
        </h3>
        <?php if (!empty($this->request->data['ScheduleDay']['point_name'])) { ?>
            <h4>住宿地點: <?php
        if ($this->request->data['ScheduleDay']['point_id'] > 0) {
            echo $this->Html->link($this->request->data['ScheduleDay']['point_name'], '/points/view/' . $this->request->data['ScheduleDay']['point_id'], array(
                'title' => $this->request->data['ScheduleDay']['point_name'],
                'target' => '_blank',
            ));
        } else {
            echo $this->request->data['ScheduleDay']['point_name'];
            if ($ownerCheck && $loginMember['id'] > 0) {
                echo ' &nbsp; ' . $this->Html->link('<i class="icon-plus"></i> 建檔', "/points/add/{$this->request->data['ScheduleDay']['id']}/ScheduleDay", array(
                    'title' => '建立這個地點的詳細資訊到共用資料庫',
                    'class' => 'btn',
                    'escape' => false,
                ));
            }
        }
            ?></h4>
            <?php
        }
        if (!empty($notes[0])) {
            foreach ($notes[0] AS $note) {
                echo '<br /><i class="icon-leaf"></i><span class="color3a">[' . $note['title'] . ']</span><span class="color2b">' . $note['body'] . '</span>';
                if ($ownerCheck) {
                    echo ' &nbsp; ' . $this->Html->link('<i class="icon-remove"></i>', '/schedule_notes/delete/' . $note['id'], array(
                        'escape' => false,
                        'class' => 'scheduleDayNotesDelete',
                    ));
                }
            }
        }
        ?>
        <div class="clearfix"></div>
        <?php
        $dayListPoints = $dayTablePoints = array();
        if (empty($this->request->data['ScheduleLine'])) {
            echo '<div class="fields_2" align="center"> ~ 目前這一天沒有行程 ~ </div>';
        } else {
            ?>
            <div class="fields_2">
                <ul class="list1">
                    <li><div><a class="dbtn dbtn3 fillet_all current" title="切換為文字清單 (文字模式)" id="scheduleDayListButton">文字清單</a></div></li>
                    <li><div><a class="dbtn dbtn3 fillet_all" title="切換為圖文表格 (圖片模式)" id="scheduleDayTableButton">圖文表格</a></div></li>
                </ul>
            </div>
            <div class="fields_2">
                <div class="float-r">行經 <span class="mark_txt"><?php echo $this->request->data['ScheduleDay']['count_lines']; ?></span> 個地點</div>
            </div>
            <div class="clearfix"></div>
            <ul class="list2" id="scheduleDayList" style="display: none;">
                <?php
                foreach ($this->request->data['ScheduleLine'] as $item) {
                    $mapPointBody = '<hr />';
                    if (!empty($item['latitude']) && !empty($item['longitude'])) {
                        $dayListPoints[] = array(
                            'id' => '#sList' . $item['id'] . ' > div:first',
                            'model' => 'ScheduleLine',
                            'key' => $item['id'],
                            'title' => $item['point_name'],
                            'body' => $mapPointBody,
                            'latitude' => $item['latitude'],
                            'longitude' => $item['longitude'],
                        );
                        $dayTablePoints[] = array(
                            'id' => '#sTable' . $item['id'] . ' > div:first',
                            'model' => 'ScheduleLine',
                            'key' => $item['id'],
                            'title' => $item['point_name'],
                            'body' => $mapPointBody,
                            'latitude' => $item['latitude'],
                            'longitude' => $item['longitude'],
                        );
                    }
                    ?>
                    <li class="dTable" id="sList<?php echo $item['id']; ?>">
                        <div class="table-cell" style="width: 22px;"></div>
                        <?php if ($ownerCheck) { ?>
                            <div class="table-cell" style="width: 22px;">
                                <input type="checkbox" class="scheduleLineBox" data-id="<?php echo $item['id']; ?>" />
                            </div>
                        <?php } ?>
                        <div class="table-cell txt">
                            <span class="color1a"><?php
                echo $this->Travel->formatTimePeriod($item['time_arrive'], $item['minutes_stay']);
                        ?></span>
                            <?php
                            if (!empty($item['transport_name'])) {
                                echo '<span class="color3a">' . $item['transport_name'] . '</span> / ';
                            }
                            if (!empty($item['foreign_key'])) {
                                echo $this->Html->link($item['point_name'], '/points/view/' . $item['foreign_key'], array(
                                    'target' => '_blank',
                                    'title' => '瀏覽地點詳細資訊',
                                ));
                            } else {
                                echo $item['point_name'];
                                if ($ownerCheck && $loginMember['id'] > 0) {
                                    echo $this->Html->link('<i class="icon-plus"></i> 建檔', '/points/add/' . $item['id'], array(
                                        'title' => '建立這個地點的詳細資訊到共用資料庫',
                                        'class' => 'btn float-r',
                                        'escape' => false,
                                    ));
                                }
                            }
                            if ($ownerCheck && $loginMember['id'] > 0) {
                                echo $this->Html->link('<i class="icon-leaf"></i> 新增記事', "/schedule_notes/add/{$this->request->data['Schedule']['id']}/{$this->request->data['ScheduleDay']['id']}/{$item['id']}", array(
                                    'title' => '為這個行程加入待辦事項、必看、必買等等',
                                    'class' => 'btn float-r dialogSchedule',
                                    'escape' => false,
                                ));
                            }
                            if (!empty($item['activity_name'])) {
                                echo ' / <span class="color2b">' . $item['activity_name'] . '</span>';
                            }
                            if (!empty($notes[$item['id']])) {
                                foreach ($notes[$item['id']] AS $note) {
                                    echo '<br /><i class="icon-leaf"></i><span class="color3a">[' . $note['title'] . ']</span><span class="color2b">' . $note['body'] . '</span>';
                                    if ($ownerCheck) {
                                        echo ' &nbsp; ' . $this->Html->link('<i class="icon-remove"></i>', '/schedule_notes/delete/' . $note['id'], array(
                                            'escape' => false,
                                            'class' => 'scheduleDayNotesDelete',
                                        ));
                                    }
                                }
                            }
                            ?>
                        </div>
                    </li>
                    <?php
                }
                if (!empty($this->request->data['ScheduleDay']['latitude']) && !empty($this->request->data['ScheduleDay']['longitude'])) {
                    $dayTablePoints[] = array(
                        'id' => '#ScheduleDay' . $this->request->data['ScheduleDay']['id'] . ' > .pointHotel:first',
                        'model' => 'ScheduleDay',
                        'key' => $this->request->data['ScheduleDay']['id'],
                        'title' => $this->request->data['ScheduleDay']['point_name'],
                        'body' => $mapPointBody,
                        'latitude' => $this->request->data['ScheduleDay']['latitude'],
                        'longitude' => $this->request->data['ScheduleDay']['longitude'],
                    );
                    $dayListPoints[] = array(
                        'id' => '#ScheduleDay' . $this->request->data['ScheduleDay']['id'] . ' > .pointHotel:first',
                        'model' => 'ScheduleDay',
                        'key' => $this->request->data['ScheduleDay']['id'],
                        'title' => $this->request->data['ScheduleDay']['point_name'],
                        'body' => $mapPointBody,
                        'latitude' => $this->request->data['ScheduleDay']['latitude'],
                        'longitude' => $this->request->data['ScheduleDay']['longitude'],
                    );
                }
                ?>
            </ul>
            <ul class="list2" id="scheduleDayTable" style="display: none;">
                <li class="dTable">
                    <div class="table-cell_center table_td_5p bg_gary1"></div>
                    <div class="table-cell_center table_td_10p bg_gary1">交通</div>
                    <div class="table-cell_center bg_gary1">時間地點</div>
                    <div class="table-cell_center table_td_10p bg_gary1">活動</div>
                    <div class="table-cell_center table_td_30p bg_gary1">備註</div>
                </li>
                <?php
                foreach ($this->request->data['ScheduleLine'] as $item) {
                    $transportClass = 'categoryT04';
                    if (isset($transports[$item['transport_id']])) {
                        $transportClass = $transports[$item['transport_id']]['class'];
                    }
                    $activityClass = 'categoryA01';
                    if (isset($activities[$item['activity_id']])) {
                        $activityClass = $activities[$item['activity_id']]['class'];
                    }
                    ?>
                    <li class="dTable" id="sTable<?php echo $item['id']; ?>">
                        <div class="table-cell table_td_5p" style="width: 25px;"></div>
                        <div class="table-cell table_td_10p">
                            <div class="category <?php echo $transportClass; ?>"><span title="<?php echo $item['transport_name']; ?>"><?php echo $item['transport_name']; ?></span></div>
                        </div>
                        <div class="table-cell_middle">
                            <?php
                            $timeValue = $this->Travel->formatTimePeriod($item['time_arrive'], $item['minutes_stay']);
                            if (!empty($timeValue)) {
                                echo '<span class="color1a">' . $timeValue . '</span><br />';
                            }
                            if (!empty($item['foreign_key'])) {
                                echo $this->Html->link($item['point_name'], '/points/view/' . $item['foreign_key'], array(
                                    'target' => '_blank',
                                    'title' => '瀏覽地點詳細資訊',
                                ));
                            } else {
                                echo $item['point_name'];
                            }
                            if (!empty($notes[$item['id']])) {
                                foreach ($notes[$item['id']] AS $note) {
                                    echo '<br /><i class="icon-leaf"></i><span class="color3a">[' . $note['title'] . ']</span><span class="color2b">' . $note['body'] . '</span>';
                                    if ($ownerCheck) {
                                        echo ' &nbsp; ' . $this->Html->link('<i class="icon-remove"></i>', '/schedule_notes/delete/' . $note['id'], array(
                                            'escape' => false,
                                        ));
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="table-cell table_td_10p">
                            <div class="category <?php echo $activityClass; ?>"><span title="<?php echo $item['activity_name']; ?>"><?php echo $item['activity_name']; ?></span></div>
                        </div>
                        <div class="table-cell_middle table_td_30p"><?php echo $item['note']; ?></div>
                    </li>
                <?php } ?>
            </ul>
            <?php if ($ownerCheck) { ?>
                <hr />
                <?php
                $dayOptions = array();
                foreach ($otherDays AS $key => $val) {
                    $dayOptions[$key] = '第 ' . $val[0] . ' 天 ' . $val[1];
                }
                echo $this->Form->input('schedule_day_id', array(
                    'type' => 'select',
                    'options' => $dayOptions,
                    'empty' => array(
                        0 => '新增一天'
                    ),
                    'label' => false,
                    'div' => false,
                    'class' => 'scheduleDayBox',
                    'style' => 'margin: 0px;',
                ));
                ?>
                <a href="#" class="btn scheduleLineMove"><i class="icon-forward"></i> 移動</a>
                <a href="#" class="btn scheduleLineRemove"><i class="icon-remove"></i> 刪除</a>
            <?php } ?>
            <?php
        }
        ?>
        <div class="clearfix"></div>
    </dd>
    <script type="text/javascript">
        //<![CDATA[
<?php
if (!$isAjax) {
    echo 'loadMap();';
}
?>
    var dayListPoints = <?php echo $this->JqueryEngine->value($dayListPoints); ?>;
    var dayTablePoints = <?php echo $this->JqueryEngine->value($dayTablePoints); ?>;
    var dayViewInfo = <?php
echo $this->JqueryEngine->value(array(
    'id' => $this->request->data['ScheduleDay']['id'],
    'schedule_id' => $this->request->data['ScheduleDay']['schedule_id'],
));
?>;
    $(scheduleDaysView);
    //]]>
    </script>
</dl>
<div class="clearfix"></div>
<?php
$this->Html->script(array('co/schedule_days/view'), array('inline' => false));