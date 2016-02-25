<?php
echo $this->Form->create('Tour', array('url' => array('action' => 'add', $scheduleLineId, $from)));
?>
<div class="clearfix"></div>
<div class="block"> <span class="mark_txt"></span>
    <dl class="list4">
        <dt class="bg_gary1">Basic information</dt>
        <dd>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">English name</label>
                    <?php
                    echo $this->Form->input('Tour.title_en_us', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">Chinese name</label>
                    <?php
                    echo $this->Form->input('Tour.title_zh_tw', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Original name</label>
                    <?php
                    echo $this->Form->input('Tour.title', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </dd>
        <dt class="bg_gary1">Contact information</dt>
        <dd>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Telephone</label>
                    <?php
                    echo $this->Form->input('Tour.telephone', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">Fax</label>
                    <?php
                    echo $this->Form->input('Tour.fax', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Email</label>
                    <?php
                    echo $this->Form->input('Tour.email', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">Website</label>
                    <?php
                    echo $this->Form->input('Tour.website', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span7',
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </dd>
        <dt class="bg_gary1">Transportation information</dt>
        <dd>
            <div class="control-group">
                <div class="control-group span8">
                    <label class="add-on">Area</label>
                    <?php
                    echo $this->Form->hidden('Tour.area_id', array('id' => 'TourArea1'));
                    if (!empty($this->validationErrors['Tour']['area_id'][0])) {
                        echo '<div id="tourArea" class="error table-cell table_td_85p">';
                    } else {
                        echo '<div id="tourArea" class="table-cell table_td_85p">';
                    }
                    if (!empty($areaPath)) {
                        echo implode(' >> ', Set::extract('{n}.Area.name', $areaPath));
                        echo $this->Html->link('(修改)', '#', array('id' => 'tourAreaEdit'));
                        $replaceArea = false;
                    } else {
                        $replaceArea = true;
                    }
                    echo '</div>';
                    if (!empty($this->validationErrors['Tour']['area_id'][0])) {
                        echo '<div class="error-message error">' . $this->validationErrors['Tour']['area_id'][0] . '</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Longitude</label>
                    <?php
                    echo $this->Form->input('Tour.longitude', array(
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
                <div class="control-group input-prepend span4">
                    <label class="add-on">Latitude</label>
                    <?php
                    echo $this->Form->input('Tour.latitude', array(
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span4">
                    <label class="add-on">Post code</label>
                    <?php
                    echo $this->Form->input('Tour.postcode', array(
                        'label' => false,
                        'div' => false,
                        'class' => 'span3',
                    ));
                    ?>
                    <a id="tourAddLatLng" href="#" class="btn" title="Check coordinates using google map"><i class="icon-search"></i> Coordinates</a>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">English address</label>
                    <?php
                    echo $this->Form->input('Tour.address_en_us', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span7',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">Chinese address</label>
                    <?php
                    echo $this->Form->input('Tour.address_zh_tw', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span7',
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group">
                <div class="control-group input-prepend span8">
                    <label class="add-on">Original address</label>
                    <?php
                    echo $this->Form->input('Tour.address', array(
                        'type' => 'text',
                        'label' => false,
                        'div' => false,
                        'class' => 'span7',
                    ));
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </dd>
    </dl>
    <div class="float-l">
        <a class="btn btn-primary dbtnSubmit" href="#" title="Save this tour"><i class="icon-ok icon-white"></i> Save</a>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
    <!--
    $(function() {
        $('a.dbtnSubmit').click(function() {
            $(this).parents('form').submit();
            return false;
        });
        $('#tourAddLatLng').click(function() {
            findLatLng($('#TourLatitude'), $('#TourLongitude'));
            return false;
        });
        $('#tourArea').load('<?php echo $this->Html->url('/areas/getForm/Tour'); ?>');
    });
    // -->
</script>