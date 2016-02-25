<div id="CartList1" class="fillet_all shadow-box2">
    <div class="dbtn dbtn1 fillet_top">My itineraries</div>
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
        echo '<ol class="fillet_all" style="list-style-type:none;"><li>There is no itinerary now</li></ol>';
    }
    echo $this->Html->link('Create an itinerary >>', '/schedules/add', array(
        'class' => 'dbtn dbtn1 fillet_bottom',
        'title' => 'Create a new itinerary',
    ));
    ?>
    <div class="clearB"></div>
</div>