<div id="ToursAdminControlPage">
    <h2>旅行社管理</h2>
    <div class="btn-group"><?php
echo $this->Html->link('新增', array('action' => 'add', 'admin' => true), array('class' => 'btn'));
?></div>
    <div class="clearfix"></div>
    <div class="span3"><?php
        echo $this->Paginator->counter(array('format' => '第 %page% 頁 / 共 %pages% 頁（ 共 %count% 筆資料）'));
?></div>
    <div class="span3">
        <?php
        echo $this->Form->create('Tour', array('type' => 'get', 'url' => array('action' => 'index')));
        echo $this->Form->text('keyword', array('class' => 'span-10', 'value' => $keyword));
        echo $this->Form->submit('查詢', array('div' => false));
        echo $this->Form->end();
        ?>
    </div>
    <div class="paging span3"><?php echo $this->element('paginator'); ?></div>
    <div class="clearfix"></div>
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="ToursAdminListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Tour.title_en_us', '名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Tour.count_views', '瀏覽', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Tour.count_schedules', '行程', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Tour.count_comments', '評論', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Tour.count_links', '連結', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Tour.count_ranks', '評分', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Tour.modified', '更新時間', array('url' => $url)); ?></th>
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
            echo $this->Html->link($this->Travel->getValue($item['Tour'], 'title'), '/tours/view/' . $item['Tour']['id'], array('target' => '_blank')
            );
            if ($item['Tour']['area_id'] == 0) {
                echo ' (未設定區域) ';
            }
            if ($item['Tour']['is_active'] == 0) {
                echo ' (停用中) ';
            }
                ?></td>
                    <td><?php echo $item['Tour']['count_views']; ?></td>
                    <td><?php echo $item['Tour']['count_schedules']; ?></td>
                    <td><?php echo $item['Tour']['count_comments']; ?></td>
                    <td><?php echo $item['Tour']['count_links']; ?></td>
                    <td><?php echo $item['Tour']['count_ranks']; ?></td>
                    <td><?php echo $item['Tour']['modified']; ?></td>
                    <td class="actions">
                        <div class="btn-group">
                            <?php
                            echo $this->Html->link('<i class="icon-pencil"></i> 編輯', array('action' => 'edit', $item['Tour']['id']), array(
                                'class' => 'btn ToursAdminControl',
                                'title' => '點選這裡可以編輯這個旅行社',
                                'escape' => false,
                            ));
                            echo $this->Html->link('<i class="icon-plus"></i> 複製', array('action' => 'add', $item['Tour']['id']), array(
                                'class' => 'btn ToursAdminControl',
                                'title' => '點選這裡可以複製這個旅行社',
                                'escape' => false,
                            ));
                            echo $this->Html->link('<i class="icon-remove"></i> 刪除', array('action' => 'delete', $item['Tour']['id']), array(
                                'class' => 'btn',
                                'title' => '點選這裡可以刪除這個旅行社',
                                'escape' => false,
                                    ), '確定要刪除？');
                            if ($item['Tour']['website']) {
                                echo $this->Html->link('<i class="icon-globe"></i> 網站', $item['Tour']['website'], array(
                                    'target' => '_blank',
                                    'class' => 'btn',
                                    'title' => '點選後會在新視窗開啟旅行社的網站',
                                    'escape' => false,
                                ));
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="ToursAdminControlPanel"></div>
    <?php
    $scripts = '
$(function() {
    $(\'#ToursAdminListTable th a, #ToursAdminControlPage div.paging a\').click(function() {
        $(\'#ToursAdminControlPage\').load(this.href);
        return false;
    });
    $(\'a.ToursAdminControl\').click(function() {
    	dialogFull(this);
        return false;
    });
';
    $scripts .= '});';
    echo $this->Html->scriptBlock($scripts);
    ?>
</div>