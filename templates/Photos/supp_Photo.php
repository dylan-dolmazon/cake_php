<?php
$this->assign('title','Supp Photo');
echo $this->Form->create(null, [
    'type' => 'post'
]);
echo $this->Form->control('name');
echo $this->Form->button('SUPP');
echo $this->Form->end();
