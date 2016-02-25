<div id="MembersAdminControlPage">
    <h2>會員管理</h2>
    <div class="btn-group">
        <?php
        echo $this->Html->link('新增', array('action' => 'add'), array('class' => 'btn MembersAdminControl'));
        echo $this->Html->link('群組', array('controller' => 'groups'), array('class' => 'btn'));
        echo $this->Html->link('產生測試資料', array('action' => 'test'), array('class' => 'btn'));
        echo $this->Html->link('產生ACOs', array('action' => 'acos'), array('class' => 'btn'));
        echo $this->Html->link('發送訊息', array('action' => 'message'), array('class' => 'btn'));
        echo $this->Html->link('外部認證', array('controller' => 'oauths'), array('class' => 'btn'));
        ?>
    </div>
    <p>
        <?php echo $this->Paginator->counter(array('format' => '第 %page% 頁 / 共 %pages% 頁')); ?>
    </p>
    <table class="table table-bordered" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id', '編號'); ?></th>
                <th><?php echo $this->Paginator->sort('username', '帳號'); ?></th>
                <th><?php echo $this->Paginator->sort('nickname', '暱稱'); ?></th>
                <th><?php echo $this->Paginator->sort('user_status', '狀態'); ?></th>
                <th><?php echo $this->Paginator->sort('created', '建立時間'); ?></th>
                <th><?php echo $this->Paginator->sort('modified', '更新時間'); ?></th>
                <th class="actions">操作</th>
            </tr>
        </thead>
        <?php
        $i = 0;
        foreach ($members as $member):
            $class = null;
            if ($i++ % 2 == 1) {
                $class = ' class="even"';
            }
            ?>
            <tr<?php echo $class; ?>>
                <td><?php echo $member['Member']['id']; ?></td>
                <td><?php echo $member['Member']['username']; ?></td>
                <td><?php echo $member['Member']['nickname']; ?></td>
                <td><?php echo $member['Member']['user_status']; ?></td>
                <td><?php echo $member['Member']['created']; ?></td>
                <td><?php echo $member['Member']['modified']; ?></td>
                <td class="actions btn-group">
                    <?php
                    if ('N' === $member['Member']['user_status']) {
                        echo $this->Html->link('重送啟用信', array('action' => 'active', $member['Member']['id']), array('class' => 'btn'));
                    }
                    echo $this->Html->link('編輯', array('action' => 'edit', $member['Member']['id']), array('class' => 'MembersAdminControl btn'));
                    echo $this->Html->link('刪除', array('action' => 'delete', $member['Member']['id']), array('class' => 'btn'), '確定要刪除？');
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div class="clear"></div>
    <div id="MembersAdminControlPanel"></div>
    <script type="text/javascript">
        $(function() {
            $('#MembersAdminControlPage th a, #MembersAdminControlPage div.paging a').click(function() {
                $('#MembersAdminControlPage').load(this.href);
                return false;
            });
            $('a.MembersAdminControl').click(function() {
                dialogFull(this);
                return false;
            });
        });
    </script>
</div>