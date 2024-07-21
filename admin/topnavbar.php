<?php

session_start(); 

?>

<div id="main-wrapper">
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <!-- Logo -->
            <div class="navbar-header">
                <a class="navbar-brand" href="Dashboard.php">
                    <b>
                        <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                        <img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                    </b>
                    <span>
                        <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo" />
                        <img src="../assets/images/logo-light-text.png" class="light-logo" alt="homepage" />
                    </span>
                </a>
            </div>
            
            <div class="navbar-collapse">
                <!-- Navbar items -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark" href="javascript:void(0)">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                    <!-- Other nav items if any -->
                </ul>
                
                <!-- Right side items (Profile, Logout) -->
                <ul class="navbar-nav my-lg-0">
                    <li class="nav-item dropdown u-pro">
                        <!-- Profile dropdown -->
                        <!-- <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="profile.php"
                            id="navbarDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i>
                            <span class="hidden-md-down">Profile&nbsp;</span>
                        </a> -->
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <!-- Dropdown items -->
                        </ul>
                    </li>
                    
                    <!-- Logout link -->
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-dark" href="../logout.php">
                            <i class="fa fa-sign-out"></i>
                            <span class="hidden-md-down">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</div>
