<div id="ActivitiesAdminControlPage">
    <h2>活動管理</h2>
    <div class="btn-group">
        <?php echo $this->Html->link('新增', array('action' => 'add'), array('class' => 'control btn')); ?>
    </div>
    <p>
        <?php
        $url = array();
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?></p>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="ActivitiesAdminListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Activity.name', '名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Activity.class', '類別', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Activity.description', '介紹', array('url' => $url)); ?></th>
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
                    <td><?php echo $item['Activity']['name']; ?></td>
                    <td><?php echo $item['Activity']['class']; ?></td>
                    <td><?php echo $item['Activity']['description']; ?></td>
                    <td><div class="btn-group">
                            <?php
                            echo $this->Html->link('編輯', array('action' => 'edit', $item['Activity']['id']), array('class' => 'control btn'));
                            echo $this->Html->link('刪除', array('action' => 'delete', $item['Activity']['id']), array('class' => 'btn'), '確定要刪除？');
                            ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="ActivitiesAdminControlPanel"></div>
    <script type="text/javascript">
        $(function() {
            $('#ActivitiesAdminListTable th a, #ActivitiesAdminControlPage div.paging a').click(function() {
                $('#ActivitiesAdminControlPage').load(this.href);
                return false;
            });
            $('#ActivitiesAdminControlPage a.control').click(function() {
                dialogFull(this);
                return false;
            });
        });
    </script>
</div>