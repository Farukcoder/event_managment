<?php
$page = explode('/', $_SERVER['PHP_SELF']);
$page = end($page);
// echo $page;
// exit();

?>
<!-- LEFT SIDEBAR -->
<div id="sidebar-nav" class="sidebar">
    <div class="sidebar-scroll">
        <nav>
            <ul class="nav">
                <li><a <?= $page == 'index.php' ? 'class="active"' : '' ?> href="index.php"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>

                <li>
                    <a class="<?= $page == 'create_event.php' ? 'active' : 'collapsed' ?> <?= $page == 'manage_event.php' ? 'active' : 'collapsed' ?> <?= $page == 'edit_event.php' ? 'active' : 'collapsed' ?> <?= $page == 'attendees.php' ? 'active' : 'collapsed' ?>" href="#subPages1" data-toggle="collapse" class="collapsed">
                        <i class="lnr lnr lnr-map"></i>
                        <span>Event</span> <i class="icon-submenu lnr lnr-chevron-left"></i>
                    </a>
                    <div id="subPages1" class="<?= $page == 'create_event.php' || $page == 'manage_event.php' || $page == 'edit_event.php' || $page == 'attendees.php' ? 'collapsed collapse in' : 'collapsed collapse' ?>">
                        <ul class="nav">
                            <li><a <?= $page == 'create_event.php' ? 'class="active"' : '' ?> href="create_event.php" class="">Create Event</a></li>
                            <li><a <?= $page == 'manage_event.php' || $page == 'edit_event.php' || $page == 'attendees.php' ? 'class="active"' : '' ?> href="manage_event.php" class="">Manage Event</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- END LEFT SIDEBAR -->