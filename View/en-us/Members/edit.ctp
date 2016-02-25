<?php
echo $this->Form->create('Member', array('type' => 'file'));
echo $this->Form->hidden('Member.area_id', array('id' => 'MemberArea1'));
?>
<div class="block">
    <div class="list2">
        <h2 class="fillet_all color2a">Edit</h2>
        <div class="clearfix"></div>
    </div>
    <div class="span3 clear"><h4>Change your icon</h4></div>
    <div class="span6"><h4>Personal information of <?php
echo $this->request->data['Member']['username'];
?></h4></div>
    <div class="span3 clear">
        <div class="img-m float-c memberHeadEdit img-polaroid" style="margin: 10px;"><?php
            echo $this->element('icon', array('iconData' => $this->request->data['Member'], 'iconSize' => 'memberL'));
?></div>
        <p>
            <?php
            echo $this->Form->input('Member.file', array(
                'type' => 'file',
                'label' => false,
                'div' => false,
                'class' => 'textBoxM',
            ));
            ?>
        </p>
    </div>
    <div class="span6">
        <div class="control-group">
            <label class="control-label">Gender</label>
            <?php
            echo $this->Form->input('Member.gender', array(
                'type' => 'radio',
                'between' => ' - ',
                'options' => array(
                    'f' => '<div class="spot spot_XX" style="float:left;">Female</div>',
                    'm' => '<div class="spot spot_XY" style="float:left;">Male</div>',
                ),
                'legend' => false,
                'div' => 'controls',
                'style' => 'float:left;'
            ));
            ?><div class="clearfix"></div>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">Username</label>
            <?php
            echo $this->Form->input('Member.username', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">Recommended to use 4 ~ 12 alphanumberic characters</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">Nickname</label>
            <?php
            echo $this->Form->input('Member.nickname', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">Recommended not to use the same one as your username</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">Email</label>
            <?php
            echo $this->Form->input('Member.email', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">For notification only, won't be available in public.</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">Original password</label>
            <?php
            echo $this->Form->input('Member.password', array(
                'type' => 'password',
                'value' => '',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">If you want to modify current password, you must fill this field</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">New password</label>
            <?php
            echo $this->Form->input('Member.password_new', array(
                'type' => 'password',
                'value' => '',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">The new password you want to use</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">Confirm password</label>
            <?php
            echo $this->Form->input('Member.password_re', array(
                'type' => 'password',
                'value' => '',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">Type the same new password again</span>
        </div>
        <div class="control-group">
            <blockquote>
                <label class="add-on">Area</label>
                <div id="MemberArea">
                    <?php
                    if (!empty($areaPath)) {
                        echo implode(' >> ', Set::extract('{n}.Area.name', $areaPath));
                        echo $this->Html->link('(Change)', '#', array('id' => 'MemberAreaEdit'));
                        $replaceArea = false;
                    } else {
                        $replaceArea = true;
                    }
                    ?>
                </div>
            </blockquote>

        </div>
        <div class="control-group input-prepend">
            <label class="add-on">Introduction</label>
            <?php
            echo $this->Form->input('Member.intro', array(
                'type' => 'textarea',
                'label' => false,
                'div' => false,
                'class' => 'span5',
                'rows' => 3,
            ));
            ?>
        </div>
    </div>
    <p align="center">
        <?php
        echo $this->Form->button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary btn-large'));
        echo ' &nbsp; ' . $this->Form->button('Clear', array('type' => 'reset', 'class' => 'btn btn-large'));
        ?>
    </p>
</div>
<div class="clearfix"></div>
<?php
echo $this->Form->end();
?>
<script type="text/javascript">
    //<![CDATA[
    $(function() {
<?php if (!$replaceArea) { ?>
            $('#MemberAreaEdit').click(function() {
                $('#MemberArea').load('<?php echo $this->Html->url('/areas/getForm/Member'); ?>');
                return false;
            });
<?php } else { ?>
            $('#MemberArea').load('<?php echo $this->Html->url('/areas/getForm/Member'); ?>');
<?php } ?>
    });
    //]]>
</script>