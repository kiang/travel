<div id="TransportsAdminControlPage">
    <h2>交通方式管理</h2>
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
    <table class="table table-bordered" cellpadding="0" cellspacing="0" id="TransportsListTable">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('Transport.name', '名稱', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Transport.class', '類別', array('url' => $url)); ?></th>
                <th><?php echo $this->Paginator->sort('Transport.description', '介紹', array('url' => $url)); ?></th>
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
                    <td><?php echo $item['Transport']['name']; ?></td>
                    <td><?php echo $item['Transport']['class']; ?></td>
                    <td><?php echo $item['Transport']['description']; ?></td>
                    <td><div class="btn-group">
                            <?php
                            echo $this->Html->link('Edit', array('action' => 'edit', $item['Transport']['id']), array('class' => 'control btn'));
                            echo $this->Html->link('Delete', array('action' => 'delete', $item['Transport']['id']), array('class' => 'btn'), 'Are you sure you want to delete this?');
                            ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paging"><?php echo $this->element('paginator'); ?></div>
    <script type="text/javascript">
        $(function() {
            $('#TransportsAdminListTable th a, #TransportsAdminControlPage div.paging a').click(function() {
                $('#TransportsAdminControlPage').load(this.href);
                return false;
            });
            $('#TransportsAdminControlPage a.control').click(function() {
                dialogFull(this);
                return false;
            });
        });
    </script>
</div>