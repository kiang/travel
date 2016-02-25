<div id="AreasAdminControlPage">
    <h2>地理區域管理</h2>
    <div class="btn-group">
        <?php echo $this->Html->link('新增', array('action' => 'add', $parentId), array('class' => 'control btn')); ?>
    </div>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'
        ));
        ?></p>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <?php
    if (!empty($parents)) {
        $this->Html->addCrumb('最上層', array('action' => 'index'));
        foreach ($parents AS $parent) {
            $this->Html->addCrumb($parent['Area']['name'], array('action' => 'index', $parent['Area']['id'])
            );
        }
        echo $this->Html->getCrumbs();
    }
    ?>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="AreasListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Area.name', '名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Area.countMember', 'Member使用次數', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Area.countPoint', 'Point使用次數', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Area.countSchedule', 'Schedule使用次數', array('url' => $url)); ?></th>
                <th class="actions">操作</th>
            </tr>
        </thead>
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
        if ($item['Area']['name']) {
            echo $item['Area']['name'];
        }
            ?></td>
                <td><?php echo $item['Area']['countMember']; ?></td>
                <td><?php echo $item['Area']['countPoint']; ?></td>
                <td><?php echo $item['Area']['countSchedule']; ?></td>
                <td><div class="btn-group">
                        <?php
                        echo $this->Html->link('子區域', array('action' => 'index', $item['Area']['id']), array('class' => 'btn'));
                        echo $this->Html->link('新增', array('action' => 'add', $item['Area']['id']), array('class' => 'control btn'));
                        echo $this->Html->link('編輯', array('action' => 'edit', $item['Area']['id']), array('class' => 'control btn'));
                        echo $this->Html->link('刪除', array('action' => 'delete', $item['Area']['id']), array('class' => 'btn'), '確定要刪除？');
                        ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <script type="text/javascript">
        $(function() {
            $('#AreasListTable th a, #AreasAdminControlPage div.paging a').click(function() {
                $('#AreasAdminControlPage').load(this.href);
                return false;
            });
            $('#AreasAdminControlPage a.control').click(function() {
                dialogFull(this);
                return false;
            });
        });
    </script>
</div>