<?php if ($offset === 0) { ?>
    <div id="favoriteMemberMain">
        <div class="clearfix"></div>
        <ul class="list2">
            <li class="dTable">
                <div class="table-cell_center table-cell_middle table_td_10p bg_gary1">Type</div>
                <div class="table-cell_center table-cell_middle bg_gary1">Title</div>
                <div class="table-cell_center table-cell_middle table_td_15p bg_gary1">Created</div>
                <?php if ($memberId == $loginMember['id']) { ?>
                    <div class="table-cell_center table-cell_middle table_td_5p bg_gary1"></div>
                <?php } ?>
            </li>
        </ul>
    <?php } ?>
    <ul class="list2">
        <?php
        if (!empty($items)) {
            foreach ($items AS $item) {
                ?><li class="dTable">
                    <div class="table-cell_center table-cell_middle table_td_10p"><?php
        switch ($item['Favorite']['model']) {
            case 'Schedule':
                echo 'Itineraries';
                break;
            case 'Point':
                echo 'Points';
                break;
            case 'Member':
                echo 'Members';
                break;
        }
                ?></div>
                    <div class="table-cell_middle"><?php
                echo $this->Html->link($item['Favorite']['foreignTitle'], '/' . $foreignControllers[$item['Favorite']['model']] . '/view/' . $item['Favorite']['foreign_key']);
                ?></div>
                    <div class="table-cell_center table-cell_middle table_td_15p"><span class="txt_S color1b"><?php echo $item['Favorite']['created']; ?></span></div>
                    <?php if ($memberId == $loginMember['id']) { ?>
                        <div class="table-cell_center table-cell_middle table_td_5p"><?php
            echo $this->Html->link('Delete', '/favorites/delete/' . $item['Favorite']['id'], array(
                'class' => 'dbtn dbtn_delete',
                'title' => 'Delete資料',
                    ), 'Are you sure you want to delete this?');
                        ?></div>
                    <?php } ?>
                </li><?php
        }
    } else {
                ?><li class="dTable">(There is no record currently)</li><?php
    }
            ?>
    </ul>
    <?php if ($offset === 0) { ?>
    </div>
    <div class="clearfix"></div>
    <p><a class="dbtn dbtn3 fillet_all" href="#" id="favoriteMemberMore">More &gt;&gt;</a></p>
    <script type="text/javascript">
        <!--
        $(function() {
            var favoriteMemberOffset = <?php echo $offset; ?>;
            var previousResult = '';
            $('a#favoriteMemberMore').click(function() {
                favoriteMemberOffset += 10;
                $.get('<?php echo $this->Html->url($url); ?>/' + favoriteMemberOffset, {}, function(result) {
                    if(previousResult === result) {
                        $('a#favoriteMemberMore').hide();
                    } else {
                        $('div#favoriteMemberMain').append(result);
                        previousResult = result;
                    }
                });
                return false;
            });
        });
        -->
    </script>
<?php } ?>