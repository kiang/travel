<?php
echo $this->Form->create('Schedule', array('url' => array($id)));
echo $this->Form->input('Schedule.is_draft', array('type' => 'hidden'));
?>
<dl class="list4">
    <dt class="bg_gary1">Itinerary information</dt>
    <dd style="padding: 10px;">
        <div class="control-group input-prepend span7">
            <label class="add-on">Title</label>
            <?php
            echo $this->Form->input('Schedule.title', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span7',
            ));
            ?>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span2">
                <label class="add-on">Count of days</label>
                <?php
                echo $this->Form->input('Schedule.count_days', array(
                    'value' => $this->request->data['Schedule']['count_days'],
                    'readonly' => true,
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
                    'label' => false,
                    'div' => false,
                    'class' => 'span1',
                ));
                ?>
            </div>
            <div class="control-group input-prepend span3">
                <label class="add-on">Time to depart</label>
                <?php
                echo $this->Form->input('Schedule.time_start', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span3',
                ));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span7">
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
        <div class="clearfix"></div>
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
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span7">
                <label class="add-on">Introduction</label>
                <?php
                echo $this->Form->input('Schedule.intro', array(
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
        <div class="line">
            <div class="btn-group">
                <?php if($loginMember['id'] > 0) { ?>
                <a class="btn btn-primary dbtnSubmit hasPopover" title="Publish this itinerary" rel="popover" data-placement="top" data-content="Once you publish the itinerary, everybody could view your result." href="#"><i class="icon-ok icon-white"></i> Publish</a>
                <?php } ?>
                <a class="btn dbtnDraft hasPopover" title="Save this itinerary as draft" rel="popover" data-placement="top" data-content="Draft means that this itinerary is only viewable by you. Good for private one or it's not ready to be public." href="#"><i class="icon-lock"></i> Draft</a>
                <?php
                echo $this->Html->link('<i class="icon-remove"></i> Delete', '/schedules/delete/' . $id, array(
                    'title' => 'Delete this itinerary',
                    'class' => 'btn hasPopover',
                    'escape' => false,
                    'rel' => 'popover',
                    'data-placement' => 'top',
                    'data-content' => 'Once you confirmed to delete, all the related data will be removed. This step could not be recovery.',
                    'escape' => false), 'Are you sure you want to delete this?');
                ?>
            </div>
        </div>
    </dd>
</dl>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    <!--
    $(schedulesEdit);
    // -->
</script>
<?php
$this->Html->script(array('co/schedules/edit'), array('inline' => false));