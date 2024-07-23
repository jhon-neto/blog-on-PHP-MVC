<?php

$render('header');
$render('sidebar');
$render('topbar');

?>

    <div class="content-wrapper">
        <div class="container-fluid">

            <!--Start Dashboard Content-->
            <form method="POST" action="<?= $base ?>/user-update">
                <div class="row">
                    <div class="col-lg">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edite os dados do usuário.</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-lg">
                                            <div class="form-label-group">
                                                <label for="nome">Nome</label>
                                                <input type="text" id="nome" name="login"
                                                       class="form-control form-control-rounded"
                                                       value="<?= $data['login'] ?>">
                                                <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg">
                                            <div class="form-label-group">
                                                <label for="senha">Digite uma senha para alterar</label>
                                                <input type="password" id="senha" name="pass"
                                                       class="form-control form-control-rounded">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col">Tipo de usuario
                                            <div class="form-label-group">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <div class="switch__container">
                                                                    <input id="check1" class="switch switch--shadow" type="radio" name="permission" value="Gerencia" <?= $data['permission'] == 'Gerencia' ? 'checked' : ''?>>
                                                                    <label for="check1"></label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h4 class="mt-2"> - Gerencia</h4>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <div class="switch__container">
                                                                    <input id="check2" class="switch switch--shadow" type="radio" name="permission" value="Jornalista" <?= $data['permission'] == 'Jornalista' ? 'checked' : ''?>>
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
                                                    <?php foreach ($states as $st) : ?>
                                                        <option value="<?=$st['id']?>" <?= $data['state'] == $st['name'] ? 'selected' : '' ?>><?=$st['name']?></option>
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
                                    <div class="col-lg-6">
                                        <div class="form-group">
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
