<div class="Comments form">
    <fieldset>
        <legend><?php
if ($id > 0) {
    echo 'Edit';
} else {
    echo '新增';
}
?>評論</legend>
        <div class="control-group">
            <div class="control-group input-prepend span4">
                <label class="add-on">啟用</label>
                <?php
                echo $this->Form->input('Comment.is_active', array(
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
                <label class="add-on">Title</label>
                <?php
                echo $this->Form->input('Comment.title', array(
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
                <label class="add-on">Content</label>
                <?php
                echo $this->Form->input('Comment.body', array(
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
        <blockquote>
            <div>關聯 Model： <?php echo $this->request->data['Comment']['model']; ?></div>
            <div>關聯 Key： <?php echo $this->request->data['Comment']['foreign_key']; ?></div>
            <div>會員名稱： <?php echo $this->request->data['Comment']['member_name']; ?></div>
            <div>IP： <?php echo $this->request->data['Comment']['ip']; ?></div>
        </blockquote>
        <?php
        if ($id > 0) {
            echo $this->Form->input('Comment.id');
        }
        ?>
    </fieldset>
</div>