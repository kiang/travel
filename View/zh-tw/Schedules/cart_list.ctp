<div id="CartList1" class="fillet_all shadow-box2">
    <div class="dbtn dbtn1 fillet_top">我的行程表</div>
    <?php
    if (!empty($items)) {
        echo '<ol class="fillet_all">';
        foreach ($items AS $item) {
            echo '<li>';
            echo $this->Html->link($item['Schedule']['title'], '/schedules/view/' . $item['Schedule']['id']);
            echo '</li>';
        }
        echo '</ol>';
    } else {
        echo '<ol class="fillet_all" style="list-style-type:none;"><li>目前沒有行程</li></ol>';
    }
    echo $this->Html->link('建立新行程>>', '/schedules/add', array(
        'class' => 'dbtn dbtn1 fillet_bottom',
        'title' => '建立一個新行程',
    ));
    ?>
    <div class="clearB"></div>
</div>