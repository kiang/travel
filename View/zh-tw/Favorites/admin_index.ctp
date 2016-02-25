<div id="FavoritesAdminControlPage">
    <h2>我的最愛管理</h2>
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
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="FavoritesAdminListTable">
        <thead>
            <tr>
                <?php if (empty($scope['Favorite.member_id'])): ?>
                    <th><?php echo $this->Paginator->sort('Favorite.member_id', '會員', array('url' => $url)); ?></th>
                <?php endif; ?>

                <th><?php echo $this->Paginator->sort('Favorite.model', 'Model', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Favorite.foreign_key', '外部鍵', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Favorite.created', '建立時間', array('url' => $url)); ?></th>
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
                    <?php if (empty($scope['Favorite.member_id'])): ?>
                        <td><?php
                if (empty($item['Member']['id'])) {
                    echo '--';
                } else {
                    echo $this->Html->link($item['Member']['id'], array(
                        'controller' => 'members',
                        'action' => 'view',
                        $item['Member']['id']
                    ));
                }
                        ?></td>
                    <?php endif; ?>

                    <td><?php
                if ($item['Favorite']['model']) {
                    echo $item['Favorite']['model'];
                }
                    ?></td>
                    <td><?php
                    if ($item['Favorite']['foreign_key']) {
                        echo $item['Favorite']['foreign_key'];
                    }
                    ?></td>
                    <td><?php
                    if ($item['Favorite']['created']) {
                        echo $item['Favorite']['created'];
                    }
                    ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link('刪除', array('action' => 'delete', $item['Favorite']['id']), null, '確定要刪除？'); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <div id="FavoritesAdminControlPanel"></div>
    <script type="text/javascript">
        $(function() {
            $('#FavoritesAdminListTable th a, #FavoritesAdminControlPage div.paging a').click(function() {
                $('#FavoritesAdminControlPage').load(this.href);
                return false;
            });
        });
    </script>
</div>