<!DOCTYPE html>
<html>

<head>
    <?= $this->element('gabarit/head').
        $this->html->css('foundation');
    ?>
</head>
<body>
    <?= $this->element('gabarit/navbar').
        $this->fetch('content');
        ?>
</body>
    <?=
        $this->html->script(['foundation','jquery-2.1.4.min']);
    ?>
</html>
