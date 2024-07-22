<?php

$render('header');
$render('sidebar');
$render('topbar');

if ($_SESSION['edit']) {
    $class = "btn btn-primary btn-lg";
    $msg = "Informações EDITADAS com sucesso!";
    $_SESSION['edit'] = false;
}
?>
<div class="content-wrapper">
    <div class="container-fluid">

        <!--Start Dashboard Content -->

        <div class="card mt-3">
            <div class="card-content">
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-6 col-xl-3 border-light">
                        <div class="card-body">
                            <h5 class="text-white mb-0">Total de Notícias <span class="float-right"><i class="fas fa-user-tie"></i></span></h5>
                            <div class="progress my-3" style="height:3px;">
                                <div class="progress-bar" style="width:55%"></div>
                            </div>
                            <h5> <?= $count['account']['tt'] ?>150</h5>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-3 border-light">
                        <div class="card-body">
                            <h5 class="text-white mb-0">Total de Usuários <span class="float-right"><i class="fas fa-chalkboard"></i></span></h5>
                            <div class="progress my-3" style="height:3px;">
                                <div class="progress-bar" style="width:55%"></div>
                            </div>
                            <h5> <?= $count['course']['tt'] ?>230</h5>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-3 border-light">
                        <div class="card-body">
                            <h5 class="text-white mb-0">Total de Posts <span class="float-right"><?= $count['student']['tt'] ?>54</span></h5>
                            <div class="progress my-3" style="height:3px;">
                                <div class="progress-bar" style="width:55%"></div>
                            </div>
                            <h5 class="text-white mb-0">Total de Posts <span class="float-right"><?= $count['teacher'] ?>124</span></h5>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-3 border-light">
                        <div class="card-body">
                            <h5 class="text-white mb-0"><?= $_SESSION['prev']['results']['temp'] . 'º ' . $_SESSION['prev']['results']['description']; ?><span class="float-right"><img src="<?= $base ?>/assets/images/weather/<?= $_SESSION['prev']['results']['img_id']; ?>.png" width="50"></span></h5>
                            <div class="progress my-3" style="height:3px;">
                                <div class="progress-bar" style="width:55%"></div>
                            </div>
                            <p class="mb-0 text-white small-font">
                                <i class="fas fa-sun"></i> <?= $_SESSION['prev']['results']['sunrise']; ?>
                                <span class="float-right">
                                    <i class="fas fa-moon"></i> <?= $_SESSION['prev']['results']['sunset']; ?>
                                </span><br>
                                <i class="fas fa-wind"></i> ventos <?= $_SESSION['prev']['results']['wind_speedy']; ?>
                                <span class="float-right"><?= $_SESSION['prev']['results']['city']; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHART -->
        <?php if($_SESSION['permission'] != 'Empresa' && $_SESSION['permission'] != 'Aluno'): ?>
            <div class="row">
            <div class="col-lg">
                <div class="card">
                    <div class="card-header">
                        Frequencia.
                    </div>
                    <div class="card-body">
                        <ul class="list-inline">
                            <?php foreach ($chart as $year => $str) : ?>
                                <li class="list-inline-item"><i class="fa fa-circle mr-2" style="color: <?= $chart_color[$year] ?>"></i><?= $year ?></li>
                            <?php endforeach ?>
                        </ul>
                        <div class="chart-container-1">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>

                    <div class="row m-0 row-group text-center border-top border-light-3">
                        <div class="col-12 col-lg-6">
                            <div class="p-3">
                                <small class="mb-0">Posts <span> <i class="fa fa-arrow-up"></i></span></small>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="p-3">
                                <small class="mb-0">Usuários <span> <i class="fa fa-arrow-up"></i></span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!--End Row-->

        <?php if($_SESSION['permission'] != 'Admin'): ?>
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Últimas notícias <span class="<?= $class ?>" style="margin-left: 10%;text-align: center;"><?= $msg ?></span></h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush table-borderless table-hover" id="dataTable" data-order='[[ 2, "asc" ]]'>
                                <thead>
                                <tr>
                                    <?php if($_SESSION['permission'] == 'Professor'): ?>
                                        <th>Empresa</th>
                                        <th>Aluno</th>
                                    <?php endif; ?>
                                    <th>Curso</th>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <?php if($_SESSION['permission'] == 'Aluno'): ?>
                                        <th>Tipo</th>
                                    <?php endif; ?>
                                    <?php if($_SESSION['permission'] == 'Empresa'): ?>
                                        <th>Ações</th>
                                    <?php endif; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($count['classes'] as $cl) :  ?>
                                <?php if($cl['data_aula_inicio'] >= date('Y-m-d H:i:s') && $cl['course_name'] != ''): ?>
                                    <tr>
                                        <?php if($_SESSION['permission'] == 'Professor'): ?>
                                            <td><?= $cl['account'] ?></td>
                                            <td><?= $cl['std_name'] ?></td>
                                        <?php endif; ?>
                                        <td><?= $cl['course_name'] ?></td>
                                        <td><?=date('d/m/Y', strtotime($cl['data_aula_inicio']))?></td>
                                        <td><?=date('H:i', strtotime($cl['data_aula_inicio'])) . ' às ' . date('H:i', strtotime($cl['data_aula_termino']))?></td>
                                        <?php if($_SESSION['permission'] == 'Aluno'): ?>
                                            <td><span class="btn btn-<?= $cl['course_type'] == 'Online' ? 'success' : 'danger' ?>"><?= $cl['course_type']?></span></td>
                                        <?php endif; ?>
                                        <?php if($_SESSION['permission'] == 'Empresa'): ?>
                                            <td>
                                                <a href="<?= $base . '/Detalhes-curso/' . $cl['curso_id'] ?>" class="btn btn-outline-dark text-white">
                                                    <i class="fas fa-eye"></i> Detalhes
                                                </a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endif; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">Atualizado em
                        <?php setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                        date_default_timezone_set('America/Sao_Paulo');
                        echo strftime('%d de %B de %Y', strtotime('today')); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!--End Row-->

    </div>
    <!--End Row-->

    <!--End Dashboard Content-->

    <!--start overlay-->
    <div class="overlay toggle-menu"></div>
    <!--end overlay-->

</div><!-- End container-fluid-->

</div>
<!--End content-wrapper-->

<?php $render('footer'); ?>
<script>
    $(function() {
        "use strict";

        // chart 1

        var ctx = document.getElementById('chart1').getContext('2d');

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                datasets: [
                    <?php foreach ($chart as $year => $str) : ?> {
                            label: '<?= $year ?>',
                            data: [<?= rtrim($str, ',') ?>],
                            borderColor: "<?= $chart_color[$year] ?>",
                            pointRadius: "0",
                            borderWidth: 3
                        },
                    <?php endforeach; ?>
                ]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                    labels: {
                        fontColor: '#ddd',
                        boxWidth: 40
                    }
                },
                tooltips: {
                    displayColors: false
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: '#ddd'
                        },
                        gridLines: {
                            display: true,
                            color: "rgba(221, 221, 221, 0.08)"
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: '#ddd'
                        },
                        gridLines: {
                            display: true,
                            color: "rgba(221, 221, 221, 0.08)"
                        },
                    }]
                }

            }
        });

    });
</script>