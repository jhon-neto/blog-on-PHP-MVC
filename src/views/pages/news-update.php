<?php

$render('header');
$render('sidebar');
$render('topbar');
?>

<div class="content-wrapper">
    <div class="container-fluid">

        <!--Start Dashboard Content-->
        <form method="POST" action="<?= $base ?>/news-update">
            <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edite uma Notícia.</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg">
                                        <div class="form-label-group">
                                            <label for="nome">Título</label>
                                            <input type="text" name="title"
                                                    class="form-control form-control-rounded"
                                                    value="<?= $data['title']?>">
                                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg">
                                        <div class="form-label-group">
                                            <label for="nome">Autor</label>
                                            <input type="text" name="author"
                                                    class="form-control form-control-rounded"
                                                    value="<?= $data['author']?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg">
                                        <div class="form-label-group">
                                            <label for="nome">Editoria</label>
                                            <select name="editorial"
                                                    class="form-control form-control-rounded">
                                                <?php foreach ($editorial as $ed) : ?>
                                                    <option <?= $ed['id'] == $data['id'] ? 'selected' : '' ?> value="<?=$ed['id']?>"><?=$ed['name']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg">
                                        <div class="form-label-group">
                                            <label for="nome">Usuários relacionados</label>
                                            <select name="user[]"
                                                    class="form-control form-control-rounded" multiple>
                                                <?php foreach ($user as $us) : ?>
                                                    <option <?= in_array($us['id'],$ids) ? 'selected' : '' ?> value="<?=$us['id']?>"><?=$us['login']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg">
                                        <div class="form-label-group">
                                            <label for="text">Texto da notícia</label>
                                            <span class="text-muted">* máximo de 1500 carácteres</span>
                                            <textarea id="text"
                                                    name="text"
                                                    rows="5"
                                                    maxlength="1500"
                                                    class="form-control form-control-rounded text-white"
                                            ><?=$data['text']?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg-3">
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

        <!--End Dashboard Content-->

        <!--start overlay-->
        <div class="overlay toggle-menu"></div>
        <!--end overlay-->
    </div>
    <!-- End container-fluid-->
</div>
<!--End content-wrapper-->

<?php $render('footer'); ?>