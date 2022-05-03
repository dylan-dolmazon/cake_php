<?php
/** @var TYPE_NAME $files */
?>
    <div>
        <table class="tftable" border="1">
            <tr>
<?php
    $cpt =0;
    foreach ($files as $file){

        if($cpt != 0 && $cpt % 5 == 0){
            echo '</tr><tr>';
            //$cpt = 0;
        }

        echo '<th>'.$this->Html->image($file['name'],['alt'=>$file['description'],'url'=>['controller' => 'Photos','action'=>'description','?'=>[ 'id' => $file['name']]]]).'</th>';
        $cpt++;
    }

    ?>
            </tr>
        </table>
    </div>
