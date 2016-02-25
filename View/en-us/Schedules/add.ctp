<?php
echo $this->Form->create('Schedule');
echo $this->Form->input('Schedule.is_draft', array('type' => 'hidden', 'value' => '0'));
?>
<dl class="list4">
    <dt class="bg_gary1">Itinerary information</dt>

    <dd style="padding: 10px;">
        <div class="control-group input-prepend span8">
            <label class="add-on">Title</label>
            <?php
            echo $this->Form->input('Schedule.title', array(
                'value' => date('Y-m-d') . ' Itinerary',
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span8',
            ));
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span2">
                <label class="add-on">Count of days</label>
                <?php
                echo $this->Form->input('Schedule.count_days', array(
                    'value' => '1',
                    'label' => false,
                    'div' => false,
                    'class' => 'span1',
                ));
                ?>
            </div>
            <div class="control-group input-prepend span2">
                <label class="add-on">Count of joinees</label>
                <?php
                echo $this->Form->input('Schedule.count_joins', array(
                    'value' => '1',
                    'label' => false,
                    'div' => false,
                    'class' => 'span1',
                ));
                ?>
            </div>
            <div class="control-group input-prepend span4">
                <label class="add-on">Time to depart</label>
                <?php
                echo $this->Form->input('Schedule.time_start', array(
                    'type' => 'text',
                    'label' => false,
                    'value' => date('Y-m-d H:i:00'),
                    'div' => false,
                    'class' => 'span4',
                ));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="advancedForm" style="display:none;">
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">Point of departing</label>
                    <?php
                    echo $this->Form->input('Schedule.point_text', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    echo $this->Form->input('Schedule.point_id', array('type' => 'hidden'));
                    ?>
                    <a class="btn hasPopover" rel="popover" data-placement="top" data-content="Click to open the map helper, you could check the coordinates of specified address" href="#" title="Check coordinates using address" id="scheduleLatLng"><i class="icon-search"></i> Coordinates</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Longitude</label>
                    <?php
                    echo $this->Form->input('Schedule.longitude', array(
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">Latitude</label>
                    <?php
                    echo $this->Form->input('Schedule.latitude', array(
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">Introduction</label>
                    <?php
                    echo $this->Form->input('Schedule.intro', array(
                        'type' => 'textarea',
                        'label' => false,
                        'div' => false,
                        'class' => 'span8',
                        'rows' => 3,
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="line" style="margin-left: 20px;">
            <div class="btn-group">
                <?php if($loginMember['id'] > 0) { ?>
                <a class="btn btn-primary dbtnSubmit hasPopover" title="Publish this itinerary" rel="popover" data-placement="top" data-content="Once you publish the itinerary, everybody could view your result." href="#"><i class="icon-ok icon-white"></i> Publish</a>
                <?php } ?>
                <a class="btn dbtnDraft hasPopover" title="Save this itinerary as draft" rel="popover" data-placement="top" data-content="Draft means that this itinerary is only viewable by you. Good for private one or it's not ready to be public." href="#"><i class="icon-lock"></i> Draft</a>
                <a class="btn dbtnAdvanced hasPopover" title="Extend advanced form" rel="popover" data-placement="top" data-content="If you do familar with related operations. Click this button will show you more details of the form." href="#"><i class="icon-book"></i> Advanced</a>
            </div>
        </div>
    </dd>
</dl>
<div class="advancedForm" style="display:none;">
    <div class="selecter">
        <div class="float-l">
            Day 1
        </div>
        <ul class="list1 float-r">
            <li><a href="#" class="btn addLine" title="Add point"><i class="icon-plus"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    <dl class="list3 table">
        <dt class="table-cell fillet_left">
        <a href="#" class="olcSprite mDot lightbox_map">&nbsp;</a>
        </dt>
        <dd class="table-cell fillet_right">
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Title of this day</label>
                    <?php
                    echo $this->Form->input('ScheduleDay.title', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
                <div class="control-group span4">
                    <?php
                    echo $this->Html->link('<i class="icon-search"></i> Transport', '/transports', array(
                        'title' => 'Choose a transport from list',
                        'class' => 'btn lightbox_page transportBtn',
                        'data-target' => 'ScheduleDayTransportId',
                        'escape' => false,
                    ));
                    echo $this->Form->hidden('ScheduleDay.transport_id');
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="control-group">
                <div class="control-group input-prepend span5">
                    <label class="add-on">Accommodation</label>
                    <?php
                    echo $this->Form->input('ScheduleDay.point_name', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    echo $this->Form->hidden('ScheduleDay.point_id');
                    ?>
                    <a class="btn hasPopover" rel="popover" data-placement="top" data-content="Click to open the map helper, you could check the coordinates of specified address" href="#" title="Check coordinates using address" id="scheduleDayLatLng"><i class="icon-search"></i> Coordinates</a>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group input-prepend span3">
                    <label class="add-on">Time arrive</label>
                    <?php
                    echo $this->Form->input('ScheduleDay.time_arrive', array(
                        'type' => 'text',
                        'div' => false,
                        'class' => 'span2 timepick',
                        'label' => false,
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Longitude</label>
                    <?php
                    echo $this->Form->input('ScheduleDay.longitude', array(
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">Latitude</label>
                    <?php
                    echo $this->Form->input('ScheduleDay.latitude', array(
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">Note</label>
                    <?php
                    echo $this->Form->input('ScheduleDay.note', array(
                        'type' => 'textarea',
                        'label' => false,
                        'div' => false,
                        'class' => 'span7',
                        'rows' => 3,
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </dd>
    </dl>
    <div class="clearfix"></div>
    <div id="lineBlock"></div>
    <div class="clearfix"></div>
    <div class="selecter">
        <ul class="list1 float-r">
            <li><a href="#" class="btn addLine" title="Add point"><i class="icon-plus"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    <div class="float-l">
        <div class="btn-group">
            <?php if($loginMember['id'] > 0) { ?>
            <a class="btn btn-primary dbtnSubmit" href="#" title="Publish this itinerary"><i class="icon-ok icon-white"></i> Publish</a>
            <?php } ?>
            <a class="btn dbtnDraft" href="#" title="Save as draft"><i class="icon-lock"></i> Draft</a>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    <!--
    $(schedulesAdd);
    // -->
</script>
<script id="lineTemplate" type="text/x-jquery-tmpl">
    <dl class="list3 table">
        <dt class="table-cell fillet_left">
        <a href="#" class="olcSprite lightbox_map">02</a>
        <a href="#" class="dbtn dbtn_move hasPopover" rel="popover" data-placement="top" data-content="You could drag and drop the point to change the sort of the itinerary" title="Drag and drop to change the sort">Move</a>
        <a href="#" class="dbtn dbtn_delete hasPopover" rel="popover" data-placement="right" data-content="Click to delete this line" title="Delete this line">Delete</a>
        </dt>
        <dd class="table-cell fillet_right">
            <div class="control-group">
                <div class="control-group input-prepend span5">
                    <label class="add-on">Point name</label>
                    <?php
                    echo $this->Form->hidden('ScheduleLine.sort', array(
                        'name' => 'data[ScheduleLine][sort][]',
                        'id' => false,
                        'class' => 'lineSort',
                        'value' => '${lineSort}',
                    ));
                    echo $this->Form->hidden('ScheduleLine.point_id', array(
                        'name' => 'data[ScheduleLine][point_id][]',
                        'id' => false,
                        'class' => 'linePointId',
                    ));
                    echo $this->Form->input('ScheduleLine.point_name.', array(
                        'name' => 'data[ScheduleLine][point_name][]',
                        'id' => false,
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3 linePoint',
                    ));
                    ?>
                    <a class="btn lineLatLon hasPopover" rel="popover" data-placement="top" data-content="Click to open the map helper, you could check the coordinates of specified address" href="#" title="Check coordinates using address"><i class="icon-search"></i> Coordinates</a>
                    <div class="clearfix"></div>
                </div>
                <div class="control-group input-prepend span3">
                    <label class="add-on">Time to stay</label>
                    <?php
                    echo $this->Form->input('ScheduleLine.time_arrive', array(
                        'name' => 'data[ScheduleLine][time_arrive][]',
                        'id' => false,
                        'type' => 'text',
                        'div' => false,
                        'class' => 'input-small inline timepick',
                        'label' => false,
                    )) . '<span class="add-on">~</span>';
                    echo $this->Form->input('ScheduleLine.time_leave', array(
                        'name' => 'data[ScheduleLine][time_leave][]',
                        'id' => false,
                        'type' => 'text',
                        'div' => false,
                        'class' => 'input-small inline timepick',
                        'label' => false,
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Longitude</label>
                    <?php
                    echo $this->Form->input('ScheduleLine.longitude', array(
                        'name' => 'data[ScheduleLine][longitude][]',
                        'id' => false,
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3 lineLon',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">Latitude</label>
                    <?php
                    echo $this->Form->input('ScheduleLine.latitude', array(
                        'name' => 'data[ScheduleLine][latitude][]',
                        'id' => false,
                        'value' => 0,
                        'label' => false,
                        'div' => false,
                        'class' => 'span3 lineLat',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group span4">
                    <?php
                    echo $this->Html->link('<i class="icon-search"></i> Activity', '/activities', array(
                        'title' => 'Choose an activity from list',
                        'class' => 'btn lightbox_page activityBtn',
                        'data-target' => 'ScheduleLineActivityN${lineSort}',
                        'escape' => false,
                    ));
                    ?>
                    <input id="ScheduleLineActivityN${lineSort}" type="hidden" name="data[ScheduleLine][activity_id][]" value="0" />
                </div>
                <div class="control-group span4">
                    <?php
                    echo $this->Html->link('<i class="icon-search"></i> Transport', '/transports', array(
                        'title' => 'Choose a transport from list',
                        'class' => 'btn lightbox_page transportBtn',
                        'data-target' => 'ScheduleLineTransportN${lineSort}',
                        'escape' => false,
                    ));
                    ?>
                    <input id="ScheduleLineTransportN${lineSort}" type="hidden" name="data[ScheduleLine][transport_id][]" value="0" />
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">Note</label>
                    <?php
                    echo $this->Form->input('ScheduleLine.note', array(
                        'name' => 'data[ScheduleLine][note][]',
                        'id' => false,
                        'type' => 'textarea',
                        'label' => false,
                        'div' => false,
                        'class' => 'span7',
                        'rows' => 3,
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </dd>
    </dl>
</script>
<?php
$this->Html->script(array('co/schedules/add'), array('inline' => false));