<?php

$render('header');
$render('sidebar');
$render('topbar');
?>

<div class="content-wrapper">
    <div class="container-fluid">

        <!--Start Dashboard Content-->
        <?php if ($authenticated): ?>
            <form method="POST" action="<?= $base ?>/user-create">
                <div class="row">
                    <div class="col-lg">
                        <div class="card">
                            <div class="card-header">
                                <h4>Adicione um novo Usuário.</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-lg">
                                            <div class="form-label-group">
                                                <label for="nome">Nome</label>
                                                <input type="text" id="nome" name="login"
                                                       class="form-control form-control-rounded"
                                                       placeholder="Será usado como login do sistema." required>
                                            </div>
                                        </div>
                                        <div class="col-lg">
                                            <div class="form-label-group">
                                                <label for="senha">Senha</label>
                                                <input type="password" id="senha" name="pass"
                                                       class="form-control form-control-rounded" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col">Tipo de usuario
                                            <div class="form-label-group">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <div class="switch__container">
                                                                    <input id="check1" class="switch switch--shadow" type="radio" name="permission" value="Gerencia">
                                                                    <label for="check1"></label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h4 class="mt-2"> - Gerencia</h4>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <div class="switch__container">
                                                                    <input id="check2" class="switch switch--shadow" type="radio" name="permission" value="Jornalista" checked>
                                                                    <label for="check2"></label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h4 class="mt-2"> - Jornalista</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-lg">
                                            <div class="form-label-group">
                                                <label for="nome">Estado</label>
                                                <select
                                                        name="state"
                                                        class="form-control form-control-rounded"
                                                        onchange="cityes(this.value)">
                                                    <option value="">Escolha...</option>
                                                    <?php foreach ($states as $st) : ?>
                                                        <option value="<?=$st['id']?>"><?=$st['name']?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg">
                                            <div class="form-label-group">
                                                <label for="senha">Cidade</label>
                                                <select id="city" name="city" class="form-control form-control-rounded" required>
                                                    <option value="">Escolha uma cidade</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-3 mt-5">
                                        <div class="form-group">
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
            </form>
        <?php endif; ?>

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Relatório de Usuários
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
                                    <th>Nome</th>
                                    <th>Local</th>
                                    <th>Acesso ao sistema</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data as $dt) : ?>
                                    <?php if ($authenticated || $_SESSION['logou'] == $dt['login']): ?>
                                        <tr>
                                            <td><?= $dt['id']; ?></td>
                                            <td><?= $dt['login']; ?></td>
                                            <th><?= $dt['state'] . '<br>' . $dt['city']; ?></th>
                                            <th><?= $dt['permission']; ?></th>
                                            <td>
                                                <a href="<?= $base; ?>/user-update/<?= $dt['id']; ?>"
                                                   class="btn btn-primary" data-toggle="tooltip"
                                                   title="Editar cadastro"><i class="fa fa-edit"></i></a>
                                                <a href="<?= $base ?>/user-delete/<?= $dt['id'] ?>"
                                                   onclick="return confirm('<?= $_SESSION['logou'] == $dt['login'] ? 'Tem certeza que deseja EXCLUIR seu usuário?\n Você NÃO terá mais acesso ao sistema!' : 'Tem certeza que deseja EXCLUIR?' ?>')"
                                                   class="btn btn-danger" data-toggle="tooltip"
                                                   title="Excluir cadastro"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
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

<script>
    function cityes(uf) {
        let url = '<?=$base?>/get-cityes/' + uf;
        $.ajax({
            url:url,
            type:'GET',
            dataType:'json',
            success:function(json) {
                let html = '';

                for(const i in json) {
                    html += '<option value="'+json[i].name+'">'+json[i].name+'</option>';
                }

                $("#city").html(html);
            }
        });
    }
</script>
