<?php
/** @var yii\web\View $this */
/** @var common\models\search\ProductsSearch $searchModel */

/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Отчёты клиентов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-8">
        <h1><?= $this->title ?></h1>
    </div>
    <div class="col-md-4 text-end">
        <div class="btn-group">
            <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                    aria-expanded="false" aria-controls="collapseExample">
                <i class="bi bi-search"></i> Поиск
            </button>
            <button type="button" onclick="PrintElem('print-clients')" class="btn btn-primary"><i
                        class="bi bi-printer"></i> Распечатать
            </button>
        </div>
    </div>
    <div class="col-md-12">
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <?= $this->render('search/_client_search') ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2" id="print-clients">
        <table class="table table-sm table-bordered table-striped text-center" style="border-collapse: collapse">
            <thead>
            <tr>
                <th>#</th>
                <th>Клиент</th>
                <th>Номер телефона</th>
                <th>Баланс</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            foreach ($model as $c): ($c->balance >= 0) ? $class = '' : $class = 'table-danger' ?>
                <tr class="<?= $class ?>">
                    <td><?= $i++ ?></td>
                    <td><?= $c->fullname ?></td>
                    <td><?= $c->phone ?></td>
                    <td><?= Yii::$app->formatter->asDecimal($c->balance, 0) ?></td>
                    <td>
                        <a class="btn btn-sm btn-primary" href="<?= Url::to(['client-items', 'id' => $c->token]) ?>">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function PrintElem(elem) {
        var mywindow = window.open('', '<?=Html::encode($this->title)?>', 'height=1000,width=1000');

        mywindow.document.write('<html><head><title>' + document.title + '</title>');
        //  mywindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"></head><body >');
        mywindow.document.write(`<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap');

      body {
           font-family: 'Roboto Condensed', sans-serif;
font-size: 12px;
        }
        table tr td {
            padding:5px 7px;
            border: 1px black solid;
border-collapse: collapse;
text-align: center;
        }
        th{padding:5px 7px;
            border: 1px black solid;}
        .pagination{
        display: none;
        }

</style>`);
        mywindow.document.write('<h2 style="text-align:center"><?=Html::encode($this->title)?> на <?=date('d.m.Y H:i')?></h2>');
        mywindow.document.write(document.getElementById(elem).innerHTML);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/

        mywindow.print();

        return true;
    }
</script>