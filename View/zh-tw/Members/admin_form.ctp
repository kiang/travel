<div class="Members form">
    <h3><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>會員</h3>
    <?php
    if ($id > 0) {
        echo $this->Form->input('Member.id');
    }
    ?>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">帳號</label>
            <?php
            echo $this->Form->input('Member.username', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">密碼</label>
            <?php
            echo $this->Form->input('Member.password', array(
                'type' => 'password',
                'value' => '',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">信箱</label>
            <?php
            echo $this->Form->input('Member.email', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">暱稱</label>
            <?php
            echo $this->Form->input('Member.nickname', array(
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
            <label class="add-on">群組</label>
            <?php
            echo $this->Form->input('Member.group_id', array(
                'type' => 'select',
                'options' => $groups,
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span4">
            <label class="add-on">狀態</label>
            <?php
            echo $this->Form->input('Member.user_status', array(
                'type' => 'select',
                'options' => array(
                    'Y' => '啟用',
                    'N' => '停用',
                ),
                'label' => false,
                'div' => false,
                'class' => 'span3',
            ));
            ?>
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span2">
            <label class="add-on">區域</label>
            <?php
            echo $this->Form->input('Member.area_id', array(
                'type' => 'text',
                'label' => false,
                'div' => false,
                'class' => 'span1',
                'id' => 'MemberArea1',
            ));
            ?>
        </div>
        <div class="control-group input-prepend span6">
            <label class="add-on">狀態</label>
            <div id="MemberArea" class="span5">
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
        </div>
    </div>
    <div class="control-group">
        <div class="control-group input-prepend span4">
            <label class="add-on">照片</label>
            <?php
            echo $this->Form->input('Member.file', array(
                'type' => 'file',
                'label' => false,
                'div' => false,
                'class' => 'span4',
            ));
            ?>
        </div>
        <div class="control-group span4">
            <label class="add-on">照片</label>
            <div id="MemberArea" class="span4">
                <?php
                echo $this->element('icon', array(
                    'iconData' => $this->request->data['Member']));
                ?>
            </div>
        </div>
    </div>
    <?php
    $scripts = '
$(function() {
';
    if (!$replaceArea) {
        $scripts .= '
    $(\'#MemberAreaEdit\').click(function() {
    	$(\'#MemberArea\').load(\'' . $this->Html->url('/areas/getForm/Member') . '\');
    	return false;
    });';
    } else {
        $scripts .= '
    $(\'#MemberArea\').load(\'' . $this->Html->url('/areas/getForm/Member') . '\');';
    }
    $scripts .= '
});';
    echo $this->Html->scriptBlock($scripts);
    ?>
    <div class="clearfix"></div>
</div>