<nav class="navbar">
    <div class="nav-container">
        <a href="<?php echo SITE_URL; ?>/index.php" class="nav-logo">
            Newsletter System
        </a>
        
        <ul class="nav-menu">
            <li><a href="<?php echo SITE_URL; ?>/index.php">Dashboard</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/assinantes.php">Assinantes</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/listas.php">Listas</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/campanhas.php">Campanhas</a></li>
            <li><a href="<?php echo SITE_URL; ?>/pages/relatorios.php">Relatórios</a></li>
            <li>
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                <a href="<?php echo SITE_URL; ?>/api/logout.php" class="btn-logout">Sair</a>
            </li>
        </ul>
    </div>
</nav>
