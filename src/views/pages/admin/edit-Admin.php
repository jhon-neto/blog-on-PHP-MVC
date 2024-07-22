<?php

$render('header');
$render('sidebar-Admin');
$render('topbar');


if ($_SESSION['edit']) {
    $class = "btn btn-primary btn-lg";
    $msg = "Dados EDITADOS com sucesso!";
    $_SESSION['edit'] = false;
}
?>

<div class="content-wrapper">
  <div class="container-fluid">

    <!--Start Dashboard Content-->

    <form method="POST" action="<?=$base?>/editUser">
        <div class="row">
            <div class="col-lg">
                <div class="card">
                    <div class="card-header">
                        <h4 >Meus dados | Atendimento  <span class="<?= $class ?>" style="margin-left: 10%;text-align: center;"><?= $msg ?></span></h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-lg">
                                    <label for="nome_completo">Nome Completo</label>
                                    <input type="text" id="nome" name="nome_completo" class="form-control form-control-rounded" value="<?=$data['nome_completo'];?>" required>
                                </div>
                                <div class="col-lg">
                                    <label for="nome">Email</label>
                                    <input type="text" id="nome" name="email" class="form-control form-control-rounded" value="<?=$data['email'];?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-lg">
                                    <label for="senha">Senha:</label>
                                    <input type="password" id="senha" name="senha" class="form-control form-control-rounded">
                                </div>
                                <div class="col-lg">
                                    <label for="senha2">Confirme a senha:</label>
                                    <input type="password" id="senha2" name="senha2" class="form-control form-control-rounded">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">  
                            <div class="form-row">
                                <div class="col-lg-4 m-4">          
                                    <div class="form-group">
                                        <input type="hidden" name="id" value="<?=$data['id'];?>">
                                        <button type="submit" class="btn btn-block btn-light btn-round"><i class="zmdi zmdi-accounts-add"></i> Salvar</button>
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
    
</div><!--End content-wrapper-->

<?php $render('footer'); ?>
