<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fab fa-fantasy-flight-games"></i>
        </div>
        <div class="sidebar-brand-text mx-3">DN_Febri</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- QUERY MENU -->
    <!-- Looping Menu -->
    <?php 
    $menu = $this->User->getQueryMenu();
    foreach ($menu as $m) : ?>
        <!-- Heading -->
        <div class="sidebar-heading">
            <?= $m['menu']; ?>
        </div>

        <!-- Looping Sub Menu // Bingung carane Melbu nak CONTROLLER !!! -->
        <?php
        $menuId = $m['id'];
        $subMenu = $this->User->getQuerySubMenu($menuId);
        foreach ($subMenu as $sm) : ?>
            <!-- Nav Item - Dashboard -->
            <?php if ($title == $sm['title']) : ?>
                <li class="nav-item active">
            <?php else : ?>
                <li class="nav-item">
            <?php endif; ?>
                <a class="nav-link py-1" href="<?= base_url($sm['url']); ?>">
                    <i class="<?= $sm['icon']; ?>"></i>
                    <span><?= $sm['title']; ?></span></a>
            </li>
        <?php 
        endforeach; ?>

        <!-- Divider -->
        <hr class="sidebar-divider mt-3">

    <?php endforeach; ?>
    <!-- End Query Menu -->


    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('auth/logout'); ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->