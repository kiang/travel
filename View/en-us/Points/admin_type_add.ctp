<?php

echo $this->Form->create('PointType', array('url' => array(
        'controller' => 'points', 'action' => 'type_add'
        )));
echo $this->Form->input('name', array(
    'label' => '名稱',
));
echo $this->Form->input('alias', array(
    'label' => '代稱',
));
echo $this->Form->end('Submit');