<div id="LinksAdminControlPage">
    <h2>連結管理</h2>
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
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="LinksAdminListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Link.title', '標題', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Link.body', '內容', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Link.is_active', '啟用', array('url' => $url)); ?></th>
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
            if ($item['Link']['title']) {
                echo $item['Link']['title'];
            }
                ?></td>
                    <td><?php
                    if ($item['Link']['body']) {
                        echo $item['Link']['body'];
                    }
                    echo '<br />' . $item['Link']['ip'];
                    echo ' / ' . $this->Html->link($item['Link']['foreign_title'], array(
                        'controller' => $foreignControllers[$item['Link']['model']],
                        'action' => 'view',
                        $item['Link']['foreign_key']
                    ));
                    echo ' / ' . $this->Html->link("{$item['Member']['nickname']}({$item['Member']['username']})", array(
                        'controller' => 'members',
                        'action' => 'view',
                        $item['Member']['id']
                    ));
                ?></td>
                    <td><?php
                    if ($item['Link']['is_active']) {
                        echo $item['Link']['is_active'];
                    }
                ?></td>
                    <td><div class="btn-group">
                            <?php
                            echo $this->Html->link('編輯', array('action' => 'edit', $item['Link']['id']), array('class' => 'control btn'));
                            echo $this->Html->link('刪除', array('action' => 'delete', $item['Link']['id']), array('class' => 'btn'), '確定要刪除？');
                            ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="LinksAdminControlPanel"></div>
    <script type="text/javascript">
        //<![CDATA[
        $(function() {
            $('#LinksAdminListTable th a, #LinksAdminControlPage div.paging a').click(function() {
                $('#LinksAdminControlPage').load(this.href);
                return false;
            });
            $('#LinksAdminControlPage a.control').click(function() {
                dialogFull(this);
                return false;
            });
        });
        //]]>
    </script>
</div>