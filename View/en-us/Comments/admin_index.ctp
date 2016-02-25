<div id="CommentsAdminControlPage">
    <h2>評論管理</h2>
    <p>
        <?php
        $url = array();
        if (!empty($foreignId) && !empty($foreignModel)) {
            $url = array($foreignModel, $foreignId);
        }
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?></p>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="CommentsAdminListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Comment.title', 'Subject', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Comment.body', 'Content', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Comment.is_active', '啟用', array('url' => $url)); ?></th>
                <th class="actions">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($items as $item):
                $class = null;
                if ($i++ % 2 == 1) {
                    $class = ' class="even"';
                }
                ?>
                <tr<?php echo $class; ?>>
                    <td><?php
            if ($item['Comment']['title']) {
                echo $item['Comment']['title'];
            }
                ?></td>
                    <td><?php
                    if ($item['Comment']['body']) {
                        echo $item['Comment']['body'];
                    }
                    echo '<br />' . long2ip($item['Comment']['ip']);
                    echo ' / ' . $this->Html->link($item['Comment']['foreign_title'], array(
                        'controller' => $foreignControllers[$item['Comment']['model']],
                        'action' => 'view',
                        $item['Comment']['foreign_key']
                    ));
                    echo ' / ' . $this->Html->link("{$item['Member']['nickname']}({$item['Member']['username']})", array(
                        'controller' => 'members',
                        'action' => 'view',
                        $item['Member']['id']
                    ));
                ?></td>
                    <td><?php
                    if ($item['Comment']['is_active']) {
                        echo $item['Comment']['is_active'];
                    }
                ?></td>
                    <td><div class="btn-group">
                            <?php
                            echo $this->Html->link('Edit', array('action' => 'edit', $item['Comment']['id']), array('class' => 'control btn'));
                            echo $this->Html->link('Delete', array('action' => 'delete', $item['Comment']['id']), array('class' => 'btn'), 'Are you sure you want to delete this?');
                            ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="CommentsAdminControlPanel"></div>
    <script type="text/javascript">
        $(function() {
            $('#CommentsAdminListTable th a, #CommentsAdminControlPage div.paging a').click(function() {
                $('#CommentsAdminControlPage').load(this.href);
                return false;
            });
            $('#CommentsAdminControlPage a.control').click(function() {
                dialogFull(this);
                return false;
            });
        });
    </script>
</div>