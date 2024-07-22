<body class="bg-theme bg-theme<?= $_SESSION['theme'] ?>">

    <!-- Start wrapper-->
    <div id="wrapper" class="">
        <!--Start sidebar-wrapper-->
        <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
            <div class="brand-logo">
                <a href="<?= $base ?>/Sistema">
                    <img src="<?= $base ?>/assets/images/logos/light-blue.png" class="logo-icon" alt="logo icon">
                    <h5 class="logo-text">Meu Sistema</h5>
                </a>
            </div>
            <ul class="sidebar-menu do-nicescrol">
                <li><h5 class="text-center mt-2"><?=$_SESSION['logged']?></h5></li>
                <li class="sidebar-header">ATENDIMENTO</li>
                <li>
                    <a href="<?=$base?>/Sistema">
                        <i class="zmdi zmdi-view-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?=$base?>/Usuarios">
                        <i class="fas fa-users"></i> <span>Usuários</span>
                    </a>
                </li>
                <li>
                    <a href="/Contas">
                        <i class="fas fa-user-tie"></i> <span>Editorias</span>
                    </a>
                </li>
                <li>
                    <a href="/Alunos">
                        <i class="fas fa-users"></i> <span>Users-Ins</span>
                    </a>
                </li>
                <li>
                    <a href="/Alunos">
                        <i class="fas fa-users"></i> <span>Fale Conosco</span>
                    </a>
                </li>
                <li>
                    <a href="/Contas">
                        <i class="fas fa-user-tie"></i> <span>Notícias</span>
                    </a>
                </li>
                <hr>
                <li>
                    <a href="<?= $base ?>/logout">
                        <i class="icon-power"></i> <span>Sair</span>
                    </a>
                </li>
            </ul>

        </div>
        <!--End sidebar-wrapper-->