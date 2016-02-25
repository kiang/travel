<div class="span-8"><div class="box">
        Hello, 歡迎來到<strong>就愛玩</strong>！雖然這兒以旅遊為題，我們還沒想說要開發上傳照片的功能，因為放照片的地方很多，
        所以我們提供你可以放一堆相簿連結的功能。有很多人覺得我們留下的資訊很不正式，也許吧，我們只是想要用輕鬆的心情面對這兒的發展，
        因為旅遊本來就是一種該放鬆自己的事情。但請相信，我們是認真的。 ;)
    </div></div>
<div class="span-8" id="blockHomeNewSchedules">最新行程</div>
<div class="span-8 last" id="blockHomeNewPoints">最新地點</div>
<div class="clear"></div>
<div class="span-8" id="blockHomeNewScheduleComments">最新行程評論</div>
<div class="span-8 last" id="blockHomeNewPointComments">最新地點評論</div>
<div class="clear"></div>
<div class="span-8"><div style="height: 100px; padding: 20px;"><span class="olc-icon ui-icon-alert"></span>其實這裡想放些熱門的東西，但是你又不幫忙推一下，我們又不愛作票，就先空著好了。</div></div>
<div class="span-8 last"><div class="box" style="height: 100px;"><span class="olc-icon ui-icon-alert"></span>同左</div></div>
<div class="clear"></div>
<script type="text/javascript">
    $(function() {
        $('div#blockHomeNewSchedules').load('<?php echo $this->Html->url('/schedules/block_new'); ?>');
        $('div#blockHomeNewPoints').load('<?php echo $this->Html->url('/points/block_new'); ?>');
        $('div#blockHomeNewScheduleComments').load('<?php echo $this->Html->url('/comments/block_new/Schedule'); ?>');
        $('div#blockHomeNewPointComments').load('<?php echo $this->Html->url('/comments/block_new/Point'); ?>');
    });
</script>