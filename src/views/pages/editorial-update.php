<?php

$render('header');
$render('sidebar');
$render('topbar');

?>

    <div class="content-wrapper">
        <div class="container-fluid">

            <!--Start Dashboard Content-->
            <form method="POST" action="<?= $base ?>/editorial-update">
                <div class="row">
                    <div class="col-lg">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edite um editorial.</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-lg">
                                            <div class="form-label-group">
                                                <label for="nome">Nome</label>
                                                <input type="text" name="name"
                                                       class="form-control form-control-rounded"
                                                       value="<?= $data['name'] ?>">
                                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg">
                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-block btn-light btn-round">
                                                    <h5><i class="zmdi zmdi-plus-circle"></i> Salvar</h5>
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

            <!--End Dashboard Content-->

            <!--start overlay-->
            <div class="overlay toggle-menu"></div>
            <!--end overlay-->
        </div>
        <!-- End container-fluid-->
    </div>
    <!--End content-wrapper-->

<?php $render('footer'); ?>