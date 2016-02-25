<?php
echo $this->Html->meta(array('property' => 'og:type', 'content' => 'article'), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:url', 'content' => $this->Html->url('/schedules/view/' . $this->request->data['Schedule']['id'], true)), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:title', 'content' => $this->request->data['Schedule']['title']), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:description',
    'content' => "{$this->request->data['Schedule']['member_name']} 的 {$this->request->data['Schedule']['title']} 是一個 {$this->request->data['Schedule']['count_days']} 天的行程，行經 {$this->request->data['Schedule']['count_points']} 個地點"
        ), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:published_time', 'content' => $this->request->data['Schedule']['created']), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:modified_time', 'content' => $this->request->data['Schedule']['modified']), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:author', 'content' => $this->Html->url('/members/view/' . $this->request->data['Schedule']['member_id'], true)), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:section', 'content' => 'schedules'), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'article:tag', 'content' => 'travel, trip, tour'), null, array('inline' => false));
$genderClass = 'spot_XY';
if (isset($this->request->data['Member']['gender']) && $this->request->data['Member']['gender'] === 'f') {
    $genderClass = 'spot_XX';
}
?>
<div class="block">
    <?php if ($ableEdit && !empty($loginMember['id'])) { ?>
        <div id="Breadcrumb" class="hasPopover" rel="popover" data-placement="top" data-content="如果希望行程讓人可以公開讀取，請點選 發表(公開)" data-original-title="設定狀態" style="width: 50%;"><strong>狀態：</strong>
            <?php
            if (empty($this->request->data['Schedule']['is_draft'])) {
                $this->request->data['Schedule']['is_draft'] = '0';
            }
            echo $this->Form->input('view_draft', array(
                'type' => 'radio',
                'options' => array(
                    '0' => '發表(公開)',
                    '1' => '暫存(隱藏)',
                ),
                'value' => $this->request->data['Schedule']['is_draft'],
                'legend' => false,
                'div' => 'inline_label btn',
                'class' => 'scheduleDraft',
                'style' => 'margin-left: 20px;'
            ));
            ?> <span class="mark_txt">※本項目僅編輯者個人可見。</span>
        </div>
    <?php } ?>
    <div class="fields_2">
        <div id="Mapbox">
            <div id="map" style="width: 97%; min-width: 300px; height: 320px; background: #DDD;"></div>
        </div>
    </div>
    <div class="fields_2">
        <div class="title2">
            <h2 class="spot spot_route float-l">行程資訊
                <?php
                if ($ableEdit) {
                    echo $this->Html->link('<i class="icon-pencil"></i> 編輯', '/schedules/edit/' . $this->request->data['Schedule']['id'], array(
                        'title' => '編輯行程的基本資訊',
                        'class' => 'dialogSchedule btn hasPopover',
                        'escape' => false,
                        'rel' => 'popover',
                        'data-placement' => 'bottom',
                        'data-content' => '這裡只有行程的主要資訊，如果希望進一步編輯個別行程的細節，請透過下面行程標題右邊的編輯功能',
                    ));
                }
                ?>
            </h2>

            <?php if (empty($this->request->data['Schedule']['is_draft'])) { ?>
                <div id="InfoBox">
                    <div class="btn-group">
                        <a id="InfoBox_tab2" class="btn"><i class="icon-share"></i> 分享</a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="InfoBox_contentbox">
                        <div id="InfoBox_content2">
                            <a name="fb_share" type="icon_link"></a>
                            <g:plusone size="small" annotation="inline"></g:plusone>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
        <dl class="dl-horizontal topbox">
            <dt>名稱：</dt>
            <dd><?php echo $this->request->data['Schedule']['title']; ?></dd>
            <dt>時程：</dt>
            <dd><?php echo $this->request->data['Schedule']['count_days']; ?>天 / <?php
            if (empty($this->request->data['Schedule']['time_start'])) {
                $timeEnd = '?';
            } else {
                $timeStart = strtotime($this->request->data['Schedule']['time_start']);
                $days = $this->request->data['Schedule']['count_days'] - 1;
                $timeEnd = date('Y-m-d', strtotime("+{$days} days", $timeStart));
            }
            echo date('Y-m-d', $timeStart);
            ?> ~ <?php echo $timeEnd; ?></dd>
            <dt>路程：</dt>
            <dd>行經 <span class="mark_txt"><?php
                echo $this->request->data['Schedule']['count_points'];
            ?></span> 個地點<?php if (!empty($this->request->data['Schedule']['point_text'])) { ?> / 從 <span class="mark_txt"><?php
                    if (!empty($this->request->data['Schedule']['point_id'])) {
                        echo $this->Html->link($this->request->data['Schedule']['point_text'], '/points/view/' . $this->request->data['Schedule']['point_id'], array(
                            'target' => '_blank'
                        ));
                    } else {
                        echo $this->request->data['Schedule']['point_text'];
                    }
                ?></span>出發
                <?php } ?></dd>
        </dl>
        <blockquote><?php echo $this->request->data['Schedule']['intro']; ?></blockquote>

        <ul class="list1">
            <li class="spot overspots <?php echo $genderClass; ?>"><?php
                echo $this->Html->link($this->request->data['Schedule']['member_name'], '/members/view/' . $this->request->data['Schedule']['member_id']);
                ?></li>
            <li class="txt_S color1b"><?php
                echo $this->request->data['Schedule']['created'];
                ?> posted</li>
            <li class="txt_S color1b"><?php
                echo $this->request->data['Schedule']['modified'];
                ?> updated</li>
        </ul>
    </div>
</div>
<div class="clearfix"></div>
<hr class="line" />
<div class="btn-group float-r">
    <?php
    echo $this->Html->link('<i class="icon-chevron-left"></i> 返回', '/schedules', array(
        'title' => '返回行程列表',
        'class' => 'btn hasPopover',
        'escape' => false,
        'rel' => 'popover',
        'data-placement' => 'top',
        'data-content' => '回到行程列表頁',
    ));
    ?>
    <div class="dropdown" style="float: right;">
        <a class="dropdown-toggle btn hasPopover" rel="popover" data-placement="top" data-content="將行程轉換為多種格式，方便輸出或轉貼" data-original-title="輸出" data-toggle="dropdown" href="#"><i class="icon-print"></i> 輸出</a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
            <li><?php
    echo $this->Html->link('<i class="icon-print"></i> 完整', '/schedules/output/' . $this->request->data['Schedule']['id'], array(
        'title' => '完整行程表',
        'class' => 'btn hasPopover',
        'escape' => false,
        'rel' => 'popover',
        'data-placement' => 'left',
        'data-content' => '這個版本適合印出來隨身攜帶，個別行程如果有座標資訊也可以在該畫面中將地圖展開一起輸出',
        'target' => '_blank',
    ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-print"></i> 簡表', '/schedules/note/' . $this->request->data['Schedule']['id'], array(
                    'title' => '行程簡表',
                    'class' => 'btn hasPopover',
                    'escape' => false,
                    'rel' => 'popover',
                    'data-placement' => 'left',
                    'data-content' => '行程簡表只會列出每天行程經過的地點，方便將內容複製後在討論區或個人網站跟人分享',
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-gift"></i> Blog', '/schedules/blog_export/' . $this->request->data['Schedule']['id'], array(
                    'title' => '部落客工具',
                    'class' => 'btn hasPopover',
                    'escape' => false,
                    'rel' => 'popover',
                    'data-placement' => 'left',
                    'data-content' => '這個功能會將行程轉換為適合放入個人部落格的 HTML格式，讓你可以將行程與地圖帶到自己的部落格中，進一步可以跟自己的遊記混搭',
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-download-alt"></i> GPX', '/schedules/export/' . $this->request->data['Schedule']['id'] . '/gpx', array(
                    'title' => '下載 GPX 格式',
                    'class' => 'btn',
                    'escape' => false,
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-download-alt"></i> KML', '/schedules/export/' . $this->request->data['Schedule']['id'] . '/kml', array(
                    'title' => '下載 KML 格式',
                    'class' => 'btn',
                    'escape' => false,
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-download-alt"></i> OV2', '/schedules/export/' . $this->request->data['Schedule']['id'] . '/ov2', array(
                    'title' => '下載 OV2 格式',
                    'class' => 'btn',
                    'escape' => false,
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-download-alt"></i> Google Map', 'https://maps.google.com/maps?q=' . urlencode($this->Html->url('/schedules/export/' . $this->request->data['Schedule']['id'] . '/kml', true)), array(
                    'title' => '直接在 Google Map 上瀏覽',
                    'class' => 'btn',
                    'escape' => false,
                    'target' => '_blank',
                ));
    ?></li>
        </ul>
    </div>
    <?php
    if (!empty($loginMember['id'])) {
        echo $this->Html->link('<i class="icon-th"></i> 複製', '/schedules/copy/' . $this->request->data['Schedule']['id'], array(
            'title' => '複製一份行程表進行編輯',
            'class' => 'btn hasPopover',
            'escape' => false,
            'rel' => 'popover',
            'data-placement' => 'top',
            'data-content' => '點選後會將目前檢視的行程複製一份後放入自己的行程中，這個副本可以自由編輯',
        ));
        echo $this->Html->link('<i class="icon-forward"></i> 加入', '/schedules/import/' . $this->request->data['Schedule']['id'], array(
            'title' => '將行程加入我的行程表',
            'class' => 'dialogSchedule btn hasPopover',
            'escape' => false,
            'rel' => 'popover',
            'data-placement' => 'top',
            'data-content' => '這個功能可以選擇將目前檢視行程的部份或全部內容匯入到自己的行程中',
        ));
        ?><span id="scheduleViewWatch" class="hasPopover" rel="popover" data-placement="top" data-content="點選這裡可以切換是否訂閱這個行程的異動" data-original-title="訂閱行程"></span><?php
}
    ?>
</div>
<div class="clearfix"></div>
<div class="block">
    <div id="scheduleViewTab">
        <ul>
            <li><a href="#scheduleSummary" title="本行程日程表">行程彙總</a></li>
            <li><?php
    echo $this->Html->link('行程記事', '/schedule_notes/schedule/' . $this->request->data['Schedule']['id']);
    ?></li>
            <li><?php
    echo $this->Html->link('留言評論', '/comments/schedule/' . $this->request->data['Schedule']['id']);
    ?></li>
            <li><?php
                echo $this->Html->link('相關連結', '/links/schedule/' . $this->request->data['Schedule']['id']);
    ?></li>
            <?php if ($ableEdit) { ?>
                <li><?php
            echo $this->Html->link('設定區域', '/areas/getList/Schedule/' . $this->request->data['Schedule']['id']);
                ?></li>
            <?php } ?>
        </ul>
        <div id="scheduleSummary">
            <div class="clearfix"></div>
            <div class="selecter">
                <div class="float-l">
                    <?php
                    $options = array('0' => '行程總覽');
                    $countDay = 1;
                    foreach ($this->request->data['ScheduleDay'] AS $day) {
                        $options[$day['id']] = "第 {$countDay} 天";
                        if (!empty($day['title'])) {
                            $options[$day['id']] .= ' - ' . $day['title'];
                        }
                        ++$countDay;
                    }
                    if (!isset($options[$scheduleDayId])) {
                        $scheduleDayId = 0;
                    }
                    echo $this->Form->input('ScheduleDay.id', array(
                        'type' => 'select',
                        'options' => $options,
                        'label' => false,
                        'div' => false,
                    ));
                    ?>
                    行經 <span class="mark_txt"><?php echo $this->request->data['Schedule']['count_points']; ?></span>個地點
                </div>
                <?php
                if ($ableEdit) {
                    echo $this->Html->link('<i class="icon-plus"></i> 新增一天', '/schedule_days/add/' . $this->request->data['Schedule']['id'], array(
                        'title' => '新增一天',
                        'class' => 'btn float-r',
                        'escape' => false,
                    ));
                }
                ?>
                <div class="clearfix"></div>
                <a href="#" class="ui-icon ui-icon-circle-arrow-w scheduleDayPrevious" style="float:left;"></a>
                <a href="#" class="ui-icon ui-icon-circle-arrow-n scheduleDaySummary" style="float:left;"></a>
                <a href="#" class="ui-icon ui-icon-circle-arrow-e scheduleDayNext" style="float:left;"></a>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
            <div id="scheduleDayBlock"></div>
            <div id="scheduleDaySummary">
                <?php
                $i = 1;

                if ($this->request->data['Schedule']['time_start'] == '0000-00-00 00:00:00') {
                    $this->request->data['Schedule']['time_start'] = $this->request->data['Schedule']['created'];
                }
                $baseTime = strtotime($this->request->data['Schedule']['time_start']);
                $countDays = count($this->request->data['ScheduleDay']);
                $weekDays = array(
                    1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 6 => '六', 7 => '日'
                );
                foreach ($this->request->data['ScheduleDay'] as $item) {
                    $theDay = strtotime('+' . ($i - 1) . ' days', $baseTime);
                    ?>
                    <dl class="list3 dTable">
                        <dt class="table-cell fillet_left">
                        <strong>第<?php echo $i; ?>天</strong>
                        <?php if ($ableEdit) { ?>
                            <span class="dbtn dbtn_move" title="拖曳調整順序">移動</span>
                            <?php
                            echo $this->Form->hidden('ScheduleDay', array(
                                'name' => $item['id'],
                                'id' => false,
                                'class' => 'daySort',
                                'value' => $item['sort'],
                            ));
                        }
                        ?>
                        </dt>
                        <dd class="table-cell fillet_right">
                            <h3><?php
                    $dayTitle = date('Y-m-d', $theDay) . ' ( ' . $weekDays[date('N', $theDay)] . ' )';
                    if (!empty($item['title'])) {
                        $dayTitle .= ' - ' . $item['title'];
                    }
                    echo $this->Html->link($dayTitle, '/schedules/view/' . $this->request->data['Schedule']['id'] . '/' . $item['id'], array(
                        'rel' => $item['id'],
                        'class' => 'scheduleDayLink',
                    ));
                    if ($ableEdit) {
                        echo '<div class="btn-group float-r">';
                        echo $this->Html->link('<i class="icon-pencil"></i> 編輯', '/schedule_days/edit/' . $item['id'], array(
                            'title' => '編輯行程表',
                            'class' => 'btn',
                            'escape' => false,
                        ));
                        echo $this->Html->link('<i class="icon-globe"></i> 地圖模式', '/schedules/map_mode/' . $this->request->data['Schedule']['id'] . '/' . $item['id'], array(
                            'title' => '透過地圖模式編輯這一天',
                            'class' => 'btn',
                            'escape' => false,
                        ));
                        echo $this->Html->link('<i class="icon-remove"></i> 刪除', '/schedule_days/delete/' . $item['id'], array(
                            'title' => '刪除本日',
                            'class' => 'btn',
                            'escape' => false,
                                ), '確定要刪除？');
                        echo $this->Html->link('<i class="icon-list-alt"></i> 匯入', '/schedule_days/quick_day/' . $item['id'], array(
                            'title' => '快速新增單日行程',
                            'class' => 'btn dialogSchedule',
                            'escape' => false,
                        ));
                        echo '</div>';
                    }
                        ?>
                            </h3>
                            <?php if (!empty($item['point_name'])) { ?>
                                <h4>住宿地點: <?php
                        if (empty($item['point_id'])) {
                            echo $item['point_name'];
                        } else {
                            echo $this->Html->link($item['point_name'], '/points/view/' . $item['point_id'], array(
                                'title' => $item['point_name'],
                                'target' => '_blank',
                            ));
                        }
                                ?></h4>
                            <?php } ?>
                            <div class="clearfix"></div>
                            <div class="fields_2 color1b">
                                &nbsp;<?php
                        if (!empty($item['summary'])) {
                            echo mb_substr($item['summary'], 0, 100, 'UTF-8') . '...';
                        }
                            ?>
                            </div>
                            <div class="fields_2">
                                <div class="float-r">行經 <span class="mark_txt"><?php
                            echo $item['count_lines'];
                            ?></span> 個地點</div>
                            </div>
                            <div class="clearfix"></div>
                        </dd>
                    </dl>
                    <div class="clearfix"></div>
                    <?php
                    ++$i;
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    <!--
    $(function(){
        $('#scheduleViewTab').tabs({
            cache: true
        });
        
        $('#InfoBox_tab2').hover(function() {
            $('#InfoBox_content2').dialog({
                position: {
                    my: 'left top',
                    at: 'left bottom',
                    of: this
                }
            });
        });
	
        $('span#scheduleViewWatch').load('<?php echo $this->Html->url('/favorites/add/Schedule/' . $this->request->data['Schedule']['id']); ?>');
        
<?php if ($ableEdit) { ?>
                                                                                                                                                    
            $('div#scheduleDaySummary').sortable({
                handle: 'span.dbtn_move',
                axis: 'y',
                update: function() {
                    var newSort = 0;
                    var sortingResult = {};
                    $('input.daySort', this).each(function() {
                        var obj = $(this);
                        ++newSort;
                        sortingResult[obj.attr('name')] = newSort;
                    });
                    $.post('<?php echo $this->Html->url('/schedules/sort/' . $this->request->data['Schedule']['id']); ?>', sortingResult);
                }
            });
                                                                                                                                                    
            //開啟編輯介面
            $('a.dialogSchedule').click(function() {
                dialogFull(this);
                return false;
            });
            $('input.scheduleDraft').change(function() {
                $.get('<?php echo $this->Html->url('/schedules/update_status/' . $this->request->data['Schedule']['id'] . '/'); ?>' + $(this).val());
            });
                                                                                                                                                    
<?php } ?>
        $('a.scheduleDayLink').click(function() {
            $('select#ScheduleDayId').val($(this).attr('rel')).trigger('change');
            return false;
        });
        var dayPoints = <?php echo $this->JqueryEngine->value($dayPoints); ?>;
        $('select#ScheduleDayId').change(function() {
            var selectedVal = $(this).val();
            if(selectedVal == 0) {
                $('div#scheduleDaySummary').show();
                $('div#scheduleDayBlock').hide();
                pointsToMap(dayPoints);
            } else {
                $('div#scheduleDayBlock').show();
                $('div#scheduleDaySummary').hide();
                $('div#scheduleDayBlock').load('<?php echo $this->Html->url('/schedule_days/view/'); ?>' + selectedVal);
            }
        });
        $('a.scheduleDaySummary').click(function() {
            $('select#ScheduleDayId').val('0').trigger('change');
            return false;
        });
        $('a.scheduleDayPrevious').click(function() {
            var target = $('select#ScheduleDayId');
            var targetIndex = target.prop('selectedIndex') - 1;
            if(targetIndex < 0) {
                targetIndex = 0;
            }
            target.prop('selectedIndex', targetIndex).trigger('change');
            return false;
        });
        $('a.scheduleDayNext').click(function() {
            var target = $('select#ScheduleDayId');
            var targetIndex = target.prop('selectedIndex') + 1;
            if(targetIndex == <?php echo $countDay; ?>) {
                targetIndex -= 1;
            }
            target.prop('selectedIndex', targetIndex).trigger('change');
            return false;
        });
<?php if ($scheduleDayId > 0) { ?>
            $('select#ScheduleDayId').val('<?php echo $scheduleDayId; ?>').trigger('change');
<?php } else { ?>
            $('select#ScheduleDayId').trigger('change');
<?php } ?>
        if('' == dayPoints) {
            resetMap();
        }
        $('.hasPopover').popover({
            trigger: 'hover'
        });
    });
    // -->
</script>
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
<script type="text/javascript">
    window.___gcfg = {lang: 'zh-TW'};

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>
<?php
$this->Html->script(array('co/schedules/edit'), array('inline' => false));
$this->Html->script(array('co/schedule_days/view'), array('inline' => false));