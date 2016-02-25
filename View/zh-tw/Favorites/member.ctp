<?php if ($offset === 0) { ?>
    <div id="favoriteMemberMain">
        <div class="clearfix"></div>
        <ul class="list2">
            <li class="dTable">
                <div class="table-cell_center table-cell_middle table_td_10p bg_gary1">類型</div>
                <div class="table-cell_center table-cell_middle bg_gary1">標題</div>
                <div class="table-cell_center table-cell_middle table_td_15p bg_gary1">建立時間</div>
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
                echo '行程';
                break;
            case 'Point':
                echo '地點';
                break;
            case 'Member':
                echo '會員';
                break;
        }
                ?></div>
                    <div class="table-cell_middle"><?php
                echo $this->Html->link($item['Favorite']['foreignTitle'], '/' . $foreignControllers[$item['Favorite']['model']] . '/view/' . $item['Favorite']['foreign_key']);
                ?></div>
                    <div class="table-cell_center table-cell_middle table_td_15p"><span class="txt_S color1b"><?php echo $item['Favorite']['created']; ?></span></div>
                    <?php if ($memberId == $loginMember['id']) { ?>
                        <div class="table-cell_center table-cell_middle table_td_5p"><?php
            echo $this->Html->link('刪除', '/favorites/delete/' . $item['Favorite']['id'], array(
                'class' => 'dbtn dbtn_delete',
                'title' => '刪除資料',
                    ), '確定要刪除？');
                        ?></div>
                    <?php } ?>
                </li><?php
        }
    } else {
                ?><li class="dTable">(目前沒有資料)</li><?php
    }
            ?>
    </ul>
    <?php if ($offset === 0) { ?>
    </div>
    <div class="clearfix"></div>
    <p><a class="dbtn dbtn3 fillet_all" href="#" id="favoriteMemberMore">瀏覽更多內容 &gt;&gt;</a></p>
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