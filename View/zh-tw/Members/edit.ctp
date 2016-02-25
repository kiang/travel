<?php
echo $this->Form->create('Member', array('type' => 'file'));
echo $this->Form->hidden('Member.area_id', array('id' => 'MemberArea1'));
?>
<div class="block">
    <div class="list2">
        <h2 class="fillet_all color2a">編輯</h2>
        <div class="clearfix"></div>
    </div>
    <div class="span3 clear"><h4>修改個人頭像</h4></div>
    <div class="span6"><h4><?php
echo $this->request->data['Member']['username'];
?>的個人資料</h4></div>
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
            <label class="control-label">會員性別</label>
            <?php
            echo $this->Form->input('Member.gender', array(
                'type' => 'radio',
                'between' => ' - ',
                'options' => array(
                    'f' => '<div class="spot spot_XX" style="float:left;">小姐</div>',
                    'm' => '<div class="spot spot_XY" style="float:left;">先生</div>',
                ),
                'legend' => false,
                'div' => 'controls',
                'style' => 'float:left;'
            ));
            ?><div class="clearfix"></div>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">會員帳號</label>
            <?php
            echo $this->Form->input('Member.username', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">※限填半形英數字母4~12位數及「. - _」符號。</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">會員暱稱</label>
            <?php
            echo $this->Form->input('Member.nickname', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">※會員暱稱限20字內，建議不要與帳號同名。</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">電子郵件</label>
            <?php
            echo $this->Form->input('Member.email', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">※僅供系統通知使用，不會公開在個人資訊中。</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">原始密碼</label>
            <?php
            echo $this->Form->input('Member.password', array(
                'type' => 'password',
                'value' => '',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">※如需修改密碼，本欄則為必填項目</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">修改密碼</label>
            <?php
            echo $this->Form->input('Member.password_new', array(
                'type' => 'password',
                'value' => '',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">※限填4~12位數的半形字，可包含特殊符號。</span>
        </div>
        <div class="control-group input-prepend input-append">
            <label class="add-on">確認密碼</label>
            <?php
            echo $this->Form->input('Member.password_re', array(
                'type' => 'password',
                'value' => '',
                'label' => false,
                'div' => false,
                'class' => 'span2',
            ));
            ?>
            <span class="add-on">※請再輸入一次您所設定的密碼，確定密碼沒有輸入錯誤。</span>
        </div>
        <div class="control-group">
            <blockquote>
                <label class="add-on">居住地區</label>
                <div id="MemberArea">
                    <?php
                    if (!empty($areaPath)) {
                        echo implode(' >> ', Set::extract('{n}.Area.name', $areaPath));
                        echo $this->Html->link('(修改)', '#', array('id' => 'MemberAreaEdit'));
                        $replaceArea = false;
                    } else {
                        $replaceArea = true;
                    }
                    ?>
                </div>
            </blockquote>

        </div>
        <div class="control-group input-prepend">
            <label class="add-on">個人簡介</label>
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
        echo $this->Form->button('送出', array('type' => 'submit', 'class' => 'btn btn-primary btn-large'));
        echo ' &nbsp; ' . $this->Form->button('清除重填', array('type' => 'reset', 'class' => 'btn btn-large'));
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