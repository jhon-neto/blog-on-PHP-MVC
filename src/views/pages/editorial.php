<?php

$render('header');
$render('sidebar');
$render('topbar');
?>

<div class="content-wrapper">
    <div class="container-fluid">

        <!--Start Dashboard Content-->
        <form method="POST" action="<?= $base ?>/editorial-create">
            <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header">
                            <h4>Adicione um novo Editorial.</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg">
                                        <div class="form-label-group">
                                            <label for="nome">Nome</label>
                                            <input type="text" name="name"
                                                   class="form-control form-control-rounded" required>
                                        </div>
                                    </div>
                                    <div class="col-lg">
                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn btn-block btn-light btn-round">
                                                <h5><i class="zmdi zmdi-accounts-add"></i>  Salvar</h5>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Relatório de Editoriais
                            <?php if ($_SESSION['result']): ?>
                                <span class="<?= $_SESSION['result']['class'] ?>"
                                      style="margin-left: 10%;text-align: center;">
                                    <?= $_SESSION['result']['msg'] ?>
                                </span>
                                <?php $_SESSION['result'] = false; endif; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush table-borderless  table-hover" id="dataTable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>name</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data as $dt) : ?>
                                    <tr>
                                        <td><?= $dt['id']; ?></td>
                                        <td><?= $dt['name']; ?></td>
                                        <td>
                                            <a href="<?= $base; ?>/editorial-update/<?= $dt['id']; ?>"
                                               class="btn btn-primary" data-toggle="tooltip"
                                               title="Editar username"><i class="fa fa-edit"></i></a>
                                            <a href="<?= $base ?>/editorial-delete/<?= $dt['id'] ?>"
                                               onclick="return confirm('Tem certeza que deseja EXCLUIR?')"
                                               class="btn btn-danger" data-toggle="tooltip"
                                               title="Excluir username"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
        <!--End Row-->

        <!--End Dashboard Content-->

        <!--start overlay-->
        <div class="overlay toggle-menu"></div>
        <!--end overlay-->
    </div>
    <!-- End container-fluid-->
</div>
<!--End content-wrapper-->

<?php $render('footer'); ?>