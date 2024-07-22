<?php

$render('header');
$render('sidebar-Admin');
$render('topbar');

if ($_SESSION['add_user']) {
  $class = "btn btn-success btn-lg";
  $msg = "Usuário foi ADICIONADO com sucesso!";
  $_SESSION['add_user'] = false;
}
if ($_SESSION['del_user']) {
  $class = "btn btn-danger btn-lg";
  $msg = "Usuário foi EXCLUÍDO com sucesso!";
  $_SESSION['del_user'] = false;
}
if ($_SESSION['edit_user']) {
  $class = "btn btn-warning btn-lg";
  $msg = "Usuário foi EDITADO com sucesso!";
  $_SESSION['edit_user'] = false;
}
if ($_SESSION['exist']) {
  $class = "btn btn-warning btn-lg";
  $msg = "Usuário já cadastrado, escolha outro login!";
  $_SESSION['exist'] = false;
}

function getDateFormat($date)
{
  if ($date == '0000-00-00' || $date == '') {
    return false;
  } else {
    $arr = explode('-', $date);
    return $arr[2] . '/' . $arr[1] . '/' . $arr[0];
  }
}
?>


<div class="content-wrapper">
  <div class="container-fluid">

    <!--Start Dashboard Content-->

    <?php if($_SESSION['perm'] == 'Gerencia'): ?>
    <form method="POST" action="<?= $base ?>/addUser">
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
                      <input type="text" id="nome" name="login" class="form-control form-control-rounded" placeholder="Será usado como login do sistema." required>
                    </div>
                  </div>
                  <div class="col-lg">
                    <div class="form-label-group">
                      <label for="senha">Senha</label>
                      <input type="text" id="senha" name="pass" class="form-control form-control-rounded" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="form-row">
                  <div class="col">Tipo de usuario
                    <div class="form-label-group">
                      <div class="icheck-inline icheck-material-success">
                        <input type="radio" id="tipo1" name="perm" value="Gerencia">
                        <label for="tipo1">Gerencial</label>
                      </div>
                      <div class="icheck-inline icheck-material-success">
                        <input type="radio" id="tipo2" name="perm" value="Visitante" checked>
                        <label for="tipo2">Visitante</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg">Áreas de visualização.
                    <div class="form-label-group">
                      <div class="icheck-inline icheck-material-success">
                        <input type="checkbox" id="tipo3" name="view[]" value="1" checked>
                        <label for="tipo3">1 - Vitória seguidas</label>
                      </div>
                      <div class="icheck-inline icheck-material-success">
                        <input type="checkbox" id="tipo4" name="view[]" value="2" checked>
                        <label for="tipo4">2 - Apenas 1 marcou</label>
                      </div>
                      <div class="icheck-inline icheck-material-success">
                        <input type="checkbox" id="tipo5" name="view[]" value="3" checked>
                        <label for="tipo5">3 - Maior sequência mais gols</label>
                      </div>
                      <div class="icheck-inline icheck-material-success">
                        <input type="checkbox" id="tipo6" name="view[]" value="4" checked>
                        <label for="tipo6">4 - Maior sequência menos gols</label>
                      </div>
                      <div class="icheck-inline icheck-material-success">
                        <input type="checkbox" id="tipo7" name="view[]" value="5" checked>
                        <label for="tipo7">5 - Sequência PAR ou IMPAR</label>
                      </div>
                      <div class="icheck-inline icheck-material-success">
                        <input type="checkbox" id="tipo8" name="view[]" value="6" checked>
                        <label for="tipo8">6 - Sequência gols de ambos SOMADOS</label>
                      </div>
                      <div class="icheck-inline icheck-material-success">
                        <input type="checkbox" id="tipo9" name="view[]" value="7" checked>
                        <label for="tipo9">7 - Lista com as 300 últimas partidas</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="form-row">
                  <div class="col-lg-6">
                    <div class="form-label-group">
                      <label for="nome">Data</label>
                      <input type="date" id="data" name="data" class="form-control form-control-rounded">
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-6">
                  <div class="form-group">
                    <button type="submit" class="btn btn-block btn-light btn-round"><i class="zmdi zmdi-accounts-add"></i> Salvar</button>
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
            <h3>Relatório de Usuários <span class="<?= $class ?>" style="margin-left: 10%;text-align: center;"><?= $msg ?></span></h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table align-items-center table-flush table-borderless  table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Acesso ao sistema</th>
                    <th>Views</th>
                    <th>Data</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($cadastro as $cadastro) :  ?>
                    <?php if($_SESSION['perm'] == 'Gerencia' || $_SESSION['logou'] == $cadastro['login']): ?>
                    <tr>
                      <td><?= $cadastro['id']; ?></td>
                      <td><?= $cadastro['login']; ?></td>
                      <th><?= $cadastro['permission']; ?></th>
                      <td><?= $cadastro['view']; ?></td>
                      <td><span class="<?= (date('m-d', strtotime($cadastro['created'])) == date('m-d')) ? 'text-success' : ''?>"><?= getDateFormat($cadastro['created']); ?></span></td>
                      <td>
                        <a href="<?= $base; ?>/Editar-usuario/<?= $cadastro['id']; ?>" class="btn btn-primary" data-toggle="tooltip" title="Editar cadastro"><i class="fa fa-edit"></i></a>
                        <a href="<?= $base ?>/delUser/<?= $cadastro['id'] ?>" onclick="return confirm('<?= $_SESSION['logou'] == $cadastro['login'] ? 'Tem certeza que deseja EXCLUIR seu usuário?\n Você NÃO terá mais acesso ao sistema!' : 'Tem certeza que deseja EXCLUIR?' ?>')" class="btn btn-danger" data-toggle="tooltip" title="Excluir cadastro"><i class="fa fa-trash"></i></a>
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