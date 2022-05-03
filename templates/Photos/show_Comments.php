<div>
    <?php /** @var TYPE_NAME $photo */?>
    <h1><?=$photo['name'] ?></h1>
    <?= $this->html->image($photo['name'],['alt'=>$photo['description']]);?>
</div>

<p>Commentaire</p>
<?php foreach ($photo['comments'] as $comment){

    $this->assign('title', 'Supp comments');
    echo $this->Form->create(null,[
        'url' => ['controller'=> 'Comments', 'action'=> 'supp', $comment['id']]
    ]);
    ?>
    <div>
        <h2><?= "by ".$comment['author']." id n° ". $comment['id'] ?></h2>
        <p><?= $comment['content']. " Fait le " . $comment['created']?></p>
    </div>
    <?=
     $this->Form->button('DELETE');
     $this->Form->end();
     ?>
<?php } ?>
