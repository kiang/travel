<?php
echo $this->Html->meta(array('property' => 'og:type', 'content' => 'article'), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:url', 'content' => $this->Html->url('/schedules/view/' . $this->request->data['Schedule']['id'], true)), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:title', 'content' => $this->request->data['Schedule']['title']), null, array('inline' => false));
echo $this->Html->meta(array('property' => 'og:description',
    'content' => "{$this->request->data['Schedule']['member_name']}'s {$this->request->data['Schedule']['title']} is a {$this->request->data['Schedule']['count_days']} days itinerary, bypass {$this->request->data['Schedule']['count_points']} points"
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
        <div id="Breadcrumb" class="hasPopover" rel="popover" data-placement="top" data-content="If you want to share the itinerary, please select Publish (Public)" data-original-title="Set the status" style="width: 50%;"><strong>Status: </strong>
            <?php
            if (empty($this->request->data['Schedule']['is_draft'])) {
                $this->request->data['Schedule']['is_draft'] = '0';
            }
            echo $this->Form->input('view_draft', array(
                'type' => 'radio',
                'options' => array(
                    '0' => 'Publish (Public)',
                    '1' => 'Draft (Hidden)',
                ),
                'value' => $this->request->data['Schedule']['is_draft'],
                'legend' => false,
                'div' => 'inline_label btn',
                'class' => 'scheduleDraft',
                'style' => 'margin-left: 20px;'
            ));
            ?> <span class="mark_txt"> * This option is viewable by you only</span>
        </div>
    <?php } ?>
    <div class="fields_2">
        <div id="Mapbox">
            <div id="map" style="width: 97%; min-width: 300px; height: 320px; background: #DDD;"></div>
        </div>
    </div>
    <div class="fields_2">
        <div class="title2">
            <h2 class="spot spot_route float-l">Itinerary information
                <?php
                if ($ableEdit) {
                    echo $this->Html->link('<i class="icon-pencil"></i> Edit', '/schedules/edit/' . $this->request->data['Schedule']['id'], array(
                        'title' => 'Edit basic information of the itinerary',
                        'class' => 'dialogSchedule btn hasPopover',
                        'escape' => false,
                        'rel' => 'popover',
                        'data-placement' => 'bottom',
                        'data-content' => 'Only some major information here. You could edit more details for each day by clicking corresponding buttons below.',
                    ));
                }
                ?></h2>
            <?php if (empty($this->request->data['Schedule']['is_draft'])) { ?>
                <div id="InfoBox">
                    <div class="btn-group">
                        <a id="InfoBox_tab2" class="btn"><i class="icon-share"></i> Share</a>
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
            <dt>Name: </dt>
            <dd><?php echo $this->request->data['Schedule']['title']; ?></dd>
            <dt>Days: </dt>
            <dd><?php echo $this->request->data['Schedule']['count_days']; ?> days / <?php
            if (empty($this->request->data['Schedule']['time_start'])) {
                $timeEnd = '?';
            } else {
                $timeStart = strtotime($this->request->data['Schedule']['time_start']);
                $days = $this->request->data['Schedule']['count_days'] - 1;
                $timeEnd = date('Y-m-d', strtotime("+{$days} days", $timeStart));
            }
            echo date('Y-m-d', $timeStart);
            ?> ~ <?php echo $timeEnd; ?></dd>
            <dt>Path: </dt>
            <dd>Bypass <span class="mark_txt"><?php
                echo $this->request->data['Schedule']['count_points'];
            ?></span> points<?php if (!empty($this->request->data['Schedule']['point_text'])) { ?> / Departed from <span class="mark_txt"><?php
                    if (!empty($this->request->data['Schedule']['point_id'])) {
                        echo $this->Html->link($this->request->data['Schedule']['point_text'], '/points/view/' . $this->request->data['Schedule']['point_id'], array(
                            'target' => '_blank'
                        ));
                    } else {
                        echo $this->request->data['Schedule']['point_text'];
                    }
                ?></span>
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
    echo $this->Html->link('<i class="icon-chevron-left"></i> Back', '/schedules', array(
        'title' => 'Back to schedules list',
        'class' => 'btn hasPopover',
        'escape' => false,
        'rel' => 'popover',
        'data-placement' => 'top',
        'data-content' => 'Back to schedules list',
    ));
    ?>
    <div class="dropdown" style="float: right;">
        <a class="dropdown-toggle btn hasPopover" rel="popover" data-placement="top" data-content="Convert the itinerary to several formats" data-original-title="Export" data-toggle="dropdown" href="#"><i class="icon-print"></i> Export</a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
            <li><?php
    echo $this->Html->link('<i class="icon-print"></i> Full', '/schedules/output/' . $this->request->data['Schedule']['id'], array(
        'title' => 'Full itinerary',
        'class' => 'btn hasPopover',
        'escape' => false,
        'rel' => 'popover',
        'data-placement' => 'left',
        'data-content' => 'This version good for taking out with when traveling. You could click buttons next to the point to have the map together.',
        'target' => '_blank',
    ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-print"></i> Summary', '/schedules/note/' . $this->request->data['Schedule']['id'], array(
                    'title' => 'Summary itinerary',
                    'class' => 'btn hasPopover',
                    'escape' => false,
                    'rel' => 'popover',
                    'data-placement' => 'left',
                    'data-content' => 'This is a summary good for copy the text to ask advices in forum or social network websites.',
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-gift"></i> Blog', '/schedules/blog_export/' . $this->request->data['Schedule']['id'], array(
                    'title' => 'Blog tool',
                    'class' => 'btn hasPopover',
                    'escape' => false,
                    'rel' => 'popover',
                    'data-placement' => 'left',
                    'data-content' => 'If you have a blog and want to share the itinerary there. This tool could provide you some base information in HTML format.',
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-download-alt"></i> GPX', '/schedules/export/' . $this->request->data['Schedule']['id'] . '/gpx', array(
                    'title' => ' GPX ',
                    'class' => 'btn',
                    'escape' => false,
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-download-alt"></i> KML', '/schedules/export/' . $this->request->data['Schedule']['id'] . '/kml', array(
                    'title' => ' KML ',
                    'class' => 'btn',
                    'escape' => false,
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-download-alt"></i> OV2', '/schedules/export/' . $this->request->data['Schedule']['id'] . '/ov2', array(
                    'title' => ' OV2 ',
                    'class' => 'btn',
                    'escape' => false,
                    'target' => '_blank',
                ));
    ?></li>
            <li><?php
                echo $this->Html->link('<i class="icon-download-alt"></i> Google Map', 'https://maps.google.com/maps?q=' . urlencode($this->Html->url('/schedules/export/' . $this->request->data['Schedule']['id'] . '/kml', true)), array(
                    'title' => 'View this itinerary on Google Map',
                    'class' => 'btn',
                    'escape' => false,
                    'target' => '_blank',
                ));
    ?></li>
        </ul>
    </div>
    <?php
    if (!empty($loginMember['id'])) {
        echo $this->Html->link('<i class="icon-th"></i> Copy', '/schedules/copy/' . $this->request->data['Schedule']['id'], array(
            'title' => 'Copy as a new itinerary',
            'class' => 'btn hasPopover',
            'escape' => false,
            'rel' => 'popover',
            'data-placement' => 'top',
            'data-content' => 'Click this will copy the viewing itinerary to your own one. You could then edit it as you wish.',
        ));
        echo $this->Html->link('<i class="icon-forward"></i> Import', '/schedules/import/' . $this->request->data['Schedule']['id'], array(
            'title' => 'Import this itinerary to mine',
            'class' => 'dialogSchedule btn hasPopover',
            'escape' => false,
            'rel' => 'popover',
            'data-placement' => 'top',
            'data-content' => 'You could choose to import all or part of this itinerary to one of yours.',
        ));
        ?><span id="scheduleViewWatch" class="hasPopover" rel="popover" data-placement="top" data-content="Click this to swith this itinerary in your favorite list" data-original-title="Set favorite"></span><?php
}
    ?>
</div>
<div class="clearfix"></div>
<div class="block">
    <div id="scheduleViewTab">
        <ul>
            <li><a href="#scheduleSummary" title="Summary of the day">Summary</a></li>
            <li><?php
    echo $this->Html->link('Notes', '/schedule_notes/schedule/' . $this->request->data['Schedule']['id']);
    ?></li>
            <li><?php
                echo $this->Html->link('Comments', '/comments/schedule/' . $this->request->data['Schedule']['id']);
    ?></li>
            <li><?php
                echo $this->Html->link('Links', '/links/schedule/' . $this->request->data['Schedule']['id']);
    ?></li>
            <?php if ($ableEdit) { ?>
                <li><?php
            echo $this->Html->link('Areas', '/areas/getList/Schedule/' . $this->request->data['Schedule']['id']);
                ?></li>
            <?php } ?>
        </ul>
        <div id="scheduleSummary">
            <div class="clearfix"></div>
            <div class="selecter">
                <div class="float-l">
                    <?php
                    $options = array('0' => 'Summary');
                    $countDay = 1;
                    foreach ($this->request->data['ScheduleDay'] AS $day) {
                        $options[$day['id']] = "Day {$countDay} ";
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
                    Bypass <span class="mark_txt"><?php echo $this->request->data['Schedule']['count_points']; ?></span> points
                </div>
                <?php
                if ($ableEdit) {
                    echo $this->Html->link('<i class="icon-plus"></i> Add one day', '/schedule_days/add/' . $this->request->data['Schedule']['id'], array(
                        'title' => 'Add another day to the itinerary',
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
                    1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'
                );
                foreach ($this->request->data['ScheduleDay'] as $item) {
                    $theDay = strtotime('+' . ($i - 1) . ' days', $baseTime);
                    ?>
                    <dl class="list3 dTable">
                        <dt class="table-cell fillet_left">
                        <strong>Day <?php echo $i; ?></strong>
                        <?php if ($ableEdit) { ?>
                            <span class="dbtn dbtn_move" title="Drag and drop to change the sort">Move</span>
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
                        echo $this->Html->link('<i class="icon-pencil"></i> Edit', '/schedule_days/edit/' . $item['id'], array(
                            'title' => 'Edit this day',
                            'class' => 'btn',
                            'escape' => false,
                        ));
                        echo $this->Html->link('<i class="icon-globe"></i> Map mode', '/schedules/map_mode/' . $this->request->data['Schedule']['id'] . '/' . $item['id'], array(
                            'title' => 'Edit this day in map mode',
                            'class' => 'btn',
                            'escape' => false,
                        ));
                        echo $this->Html->link('<i class="icon-remove"></i> Delete', '/schedule_days/delete/' . $item['id'], array(
                            'title' => 'Delete this day',
                            'class' => 'btn',
                            'escape' => false,
                                ), 'Are you sure you want to delete this?');
                        echo $this->Html->link('<i class="icon-list-alt"></i> Batch import', '/schedule_days/quick_day/' . $item['id'], array(
                            'title' => 'Batch add multiple points',
                            'class' => 'btn dialogSchedule',
                            'escape' => false,
                        ));
                        echo '</div>';
                    }
                        ?>
                            </h3>
                            <?php if (!empty($item['point_name'])) { ?>
                                <h4>Accommodation: <?php
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
                                <div class="float-r">Bypass <span class="mark_txt"><?php
                            echo $item['count_lines'];
                            ?></span> points</div>
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
    window.___gcfg = {lang: 'en-US'};

    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>
<?php
$this->Html->script(array('co/schedules/edit'), array('inline' => false));
$this->Html->script(array('co/schedule_days/view'), array('inline' => false));