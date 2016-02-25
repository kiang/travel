<?php
if (!empty($agreementFail)) {
    $this->validationErrors['Member']['agree'] = 'You must agree the service agreement to sign up';
}
echo $this->Form->create('Member', array('type' => 'file', 'class' => 'form-horizontal'));
echo $this->Form->hidden('Member.area_id', array('id' => 'MemberArea1'));
?>
<div class="block">
    <div class="list2">
        <h2 class="fillet_all color2a">Sign up</h2>
        <br />
    </div>
    <div class="span3 clear"><h4>Icon</h4></div>
    <div class="span6"><h4>Profile</h4></div>

    <div class="span3 clear">
        <div class="img-m float-c memberHeadEdit img-polaroid" style="margin: 10px;"><?php
echo $this->element('icon', array('iconSize' => 'memberL'));
?></div>
        <p>
            <?php
            echo $this->Form->input('Member.file', array(
                'type' => 'file',
                'label' => false,
                'div' => false,
                'class' => false,
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
            ?>
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
            <label class="add-on">Password</label>
            <?php
            echo $this->Form->input('Member.password', array(
                'type' => 'password',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">The password you want to use</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">Confirm password</label>
            <?php
            echo $this->Form->input('Member.password_re', array(
                'type' => 'password',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">Type the same password again</span>
        </div>
        <div class="control-group input-prepend">
            <label class="add-on">Area</label>
            <div id="MemberArea"></div>
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
        <div class="control-group input-prepend">
            <label class="add-on">Agree?</label>
            <label class="checkbox">
                <?php
                echo $this->Form->input('Member.agree', array(
                    'type' => 'checkbox',
                    'value' => 'agree',
                    'label' => false,
                    'div' => false,
                )) . ' I have read and agree ';
                echo $this->Html->link('Service Agreement', '/pages/service_agreement', array('target' => '_blank'));
                ?>
            </label>
        </div>
    </div>
    <div class="clearfix"></div>
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
        $('input.buttonSubmit').click(function() {
            $(this).parents('form').submit();
        });
        $('#MemberArea').load('<?php echo $this->Html->url('/areas/getForm/Member'); ?>');
    });
    //]]>
</script>