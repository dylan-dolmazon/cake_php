<div>
    <?php /** @var TYPE_NAME $photo */?>
    <h1><?=$photo['name'] ?></h1>
    <?= $this->html->image($photo['name'],['alt'=>$photo['description']]);?>
    <p><?= $photo['description']?></p>
    <p><?= $photo['width']?></p>
    <p><?= $photo['heigth']?></p>
    <?php
    if($this->getRequest()->getSession()->read('Auth.email') != null){
        echo '<p>' . $this->Html->link("Télécharger","/img/".$photo["name"],["download"=>$photo["name"]]) .'</p>';
    }?>
</div>

<p>Commentaire</p>
<?php foreach ($photo['comments'] as $comment){ ?>

    <div>
        <h2><?= "by ".$comment['author']." id n° ". $comment['id'] ?></h2>
        <p><?= $comment['content']. " Fait le " . $comment['created']?></p>
    </div>
<?php } ?>
<?php

$this->assign('title', 'deleteMyImage');
echo $this->Form->create($photo);
echo $this->Form->control('reason');
echo $this->Form->button('Delete image');
echo $this->Form->end();

?>
