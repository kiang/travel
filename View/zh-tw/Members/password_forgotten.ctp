<div class="prepend-1">
    <div class="box span-16" style="height: 200px;">
        <div style="margin: 30px;"><h3>忘記密碼</h3><?php
echo $this->Form->create('Member', array('action' => 'passwordForgotten'));
echo '<p class="clear">請輸入您註冊時使用的信箱，我們會將您的密碼重設後寄到您的信箱</p>';
echo '<div class="span-3">信箱</div>' . $this->Form->input('Member.email', array(
    'type' => 'text',
    'label' => false,
    'div' => 'span-8',
    'class' => 'span-8',
));
echo '<div class="clear">&nbsp;</div>';
echo '<div align="right">';
echo $this->Form->submit('送出', array('div' => false));
echo '</div>';
echo $this->Form->end();
?></div></div>
</div>