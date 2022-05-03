<?php
$this->assign('title','Add img');
echo $this->Form->create(null,['type'=>'file']);
echo $this->Form->control('name');
echo $this->Form->control('description',['type'=>'textarea']);
echo $this->Form->control('width',['type'=>'number']);
echo $this->Form->control('height',['type'=>'number']);
echo $this->Form->control('img',['type'=>'file']);
echo $this->Form->button('ADD');
echo $this->Form->end();
