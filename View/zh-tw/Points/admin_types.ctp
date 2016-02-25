<div id="PointsTypesAdminControlPage">
    <h2>地點類型管理</h2>
    <div class="btn-group"><?php
echo $this->Html->link('新增', array('action' => 'type_add'), array('class' => 'btn PointsTypesAdminControl'));
echo $this->Html->link('回到地點', array('action' => 'index'), array('class' => 'btn'));
?></div>
    <div><?php
        echo $this->Paginator->counter(array('format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'));
?></div>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="PointsTypesAdminListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('PointType.name', '名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('PointType.alias', '代稱', array('url' => $url)); ?></th>
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
                    <td><?php echo $item['PointType']['name']; ?></td>
                    <td><?php echo $item['PointType']['alias']; ?></td>
                    <td><div class="btn-group">
                            <?php
                            echo $this->Html->link('編輯', array('action' => 'type_edit', $item['PointType']['id']), array('class' => 'PointsTypesAdminControl btn'));
                            ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="PointsTypesAdminControlPanel"></div>
    <?php
    $scripts = '
$(function() {
    $(\'#PointsTypesAdminListTable th a, #PointsTypesAdminControlPage div.paging a\').click(function() {
        $(\'#PointsTypesAdminControlPage\').load(this.href);
        return false;
    });
    $(\'a.PointsTypesAdminControl\').click(function() {
    	dialogFull(this);
        return false;
    });
';
    $scripts .= '});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>