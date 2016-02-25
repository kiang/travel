<div class="prepend-1">
    <div class="box span-16" style="height: 200px;">
        <div style="margin: 30px;"><h3>Forgot your password</h3><?php
echo $this->Form->create('Member', array('action' => 'passwordForgotten'));
echo '<p class="clear">Please provide the email address, we will try to reset the password for you and send the new one to your email box.</p>';
echo '<div class="span-3">Email</div>' . $this->Form->input('Member.email', array(
    'type' => 'text',
    'label' => false,
    'div' => 'span-8',
    'class' => 'span-8',
));
echo '<div class="clear">&nbsp;</div>';
echo '<div align="right">';
echo $this->Form->submit('Submit', array('div' => false));
echo '</div>';
echo $this->Form->end();
?></div></div>
</div>