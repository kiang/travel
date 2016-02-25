<?php
foreach ($feedItems AS $key => $feedItem) {
    ?><h4><?php echo $feedItem['FeedItem']['title']; ?></h4>
    <p><?php echo $feedItem['FeedItem']['summary']; ?></p>
    <div class="float-r badge badge-success"><i class="icon-calendar">&nbsp;</i><?php echo $feedItem['FeedItem']['the_date']; ?></div>
    <div class="btn-group">
        <a href="<?php echo $feedItem['FeedItem']['url']; ?>" target="_blank" class="btn">網址</a>
        <a href="#" class="btn btnImport" data-key="<?php echo $key; ?>">匯入</a>
    </div>
    <div class="clearfix formContainer<?php echo $key; ?>"></div>
    <?php
}
?>
<script type="text/javascript">
    var feedItems = <?php echo $this->JqueryEngine->value($feedItems); ?>;
    $(function() {
        $('a.btnImport').click(function() {
            var key = $(this).attr('data-key');
            $('div.formContainer' + key).load('<?php echo $this->Html->url('/admin/channels/add'); ?>', function() {
                var obj = $(this);
                obj.find('input.cTitle').val(feedItems[key]['FeedItem']['title']);
                obj.find('input.cUrl').val(feedItems[key]['FeedItem']['url']);
                obj.find('textarea.cSummary').val(feedItems[key]['FeedItem']['summary']);
                obj.find('input.cDate').val(feedItems[key]['FeedItem']['the_date']);
                $.post('<?php echo $this->Html->url('/admin/channels/get_url'); ?>', {url: feedItems[key]['FeedItem']['url']}, function(result) {
                    obj.find('div.frameSpool').html(result);
                });
            });
            return false;
        });
    });
</script>