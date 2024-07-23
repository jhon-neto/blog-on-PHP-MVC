<?php

$render('header');
$render('sidebar');
$render('topbar');
?>

<div class="content-wrapper">
    <div class="container-fluid">

        <!--Start Dashboard Content-->
        <form method="POST" action="<?= $base ?>/news-create">
            <div class="row">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header">
                            <h4>Adicione uma nova Notícia.</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-lg">
                                        <div class="form-label-group">
                                            <label for="nome">Título</label>
                                            <input type="text" name="title"
                                                   class="form-control form-control-rounded" required>
                                        </div>
                                    </div>
                                    <div class="col-lg">
                                        <div class="form-label-group">
                                            <label for="nome">Autor</label>
                                            <input type="text" name="author"
                                                   class="form-control form-control-rounded" required>
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
                                                    <option value="<?=$ed['id']?>"><?=$ed['name']?></option>
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
                                                    <option value="<?=$us['id']?>"><?=$us['login']?></option>
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
                                            ></textarea>
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

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Relatório de Notícias
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
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th>Preview</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data as $dt) : ?>
                                    <tr>
                                        <td><?= $dt['id']; ?></td>
                                        <td><?= $dt['title']; ?></td>
                                        <td><?= $dt['author']; ?></td>
                                        <td>
                                            <?= $dt['intro']; ?>
                                            <!-- Button trigger modal -->
                                            <button type="button" onclick="modal('<?= $dt['id']; ?>')" class="btn btn-sm btn-light">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <a href="<?= $base; ?>/news-update/<?= $dt['id']; ?>"
                                               class="btn btn-primary" data-toggle="tooltip"
                                               title="Editar username"><i class="fa fa-edit"></i></a>
                                            <a href="<?= $base ?>/news-delete/<?= $dt['id'] ?>"
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

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="modalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-dark" id="modalId"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $render('footer'); ?>

<script>
    function modal(id) {
        let url = '<?=$base?>/get-news/' + id;
        $.ajax({
            url:url,
            type:'GET',
            dataType:'json',
            success:function(json) {
                $('#modalLabel').text(json.title);
                $('#modalId').html(json.text);
                $('#modal').modal('show');
            }
        });

    }
</script>
