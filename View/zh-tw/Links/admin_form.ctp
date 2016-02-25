<div class="Links form">
    <fieldset>
        <legend><?php
if ($id > 0) {
    echo '編輯';
} else {
    echo '新增';
}
?>連結</legend>
        <?php
        if ($id > 0) {
            echo $this->Form->input('Link.id');
        }
        ?><div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">啟用</label>
                <?php
                echo $this->Form->input('Link.is_active', array(
                    'type' => 'checkbox',
                    'label' => false,
                    'div' => false,
                ));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="control-group">
            <div class="control-group input-prepend span8">
                <label class="add-on">名稱</label>
                <?php
                echo $this->Form->input('Link.title', array(
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
                <label class="add-on">網址</label>
                <?php
                echo $this->Form->input('Link.url', array(
                    'type' => 'text',
                    'label' => false,
                    'div' => false,
                    'class' => 'span7',
                ));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <blockquote>
            <div>關聯 Model： <?php echo $this->request->data['Link']['model']; ?></div>
            <div>關聯 Key： <?php echo $this->request->data['Link']['foreign_key']; ?></div>
            <div>會員名稱： <?php echo $this->request->data['Link']['member_name']; ?></div>
            <div>IP： <?php echo $this->request->data['Link']['ip']; ?></div>
        </blockquote>
    </fieldset>
</div>