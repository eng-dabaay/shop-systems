<div id="main-wrapper">
    <header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
        <a class="navbar-brand" href="order.php">
            <!-- Logo icon --><b>
            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
            <!-- Dark Logo icon -->
            <img
                src="../assets/images/logo-icon.png"
                alt="homepage"
                class="dark-logo"
            />
            <!-- Light Logo icon -->
            <img
                src="../assets/images/logo-light-icon.png"
                alt="homepage"
                class="light-logo"
            />
            </b>
            <!--End Logo icon -->
            <!-- Logo text --><span>
            <!-- dark Logo text -->
            <img
                src="../assets/images/logo-text.png"
                alt="homepage"
                class="dark-logo" />
            <img
                src="../assets/images/logo-light-text.png"
                class="light-logo"
                alt="homepage"
            /></span>
        </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
        <!-- ============================================================== -->
        <!-- toggle and nav items -->
        <!-- ============================================================== -->
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
            <a
                class="
                nav-link nav-toggler
                hidden-md-up
                waves-effect waves-dark
                "
                href="javascript:void(0)"
                ><i class="fa fa-bars"></i
            ></a>
            </li>
            <!-- ============================================================== -->
            <!-- Search -->
            <!-- ============================================================== -->
            <!-- <li class="nav-item hidden-xs-down search-box">
            <a
                class="nav-link hidden-sm-down waves-effect waves-dark"
                href="javascript:void(0)"
                ><i class="fa fa-search"></i
            ></a>
            <form class="app-search">
                <input
                type="text"
                class="form-control"
                placeholder="Search & enter"
                />
                <a class="srh-btn"><i class="fa fa-times"></i></a>
            </form>
            </li> -->

        </ul>
        <!-- ============================================================== -->
        <!-- User profile and search -->
        <!-- ============================================================== -->
        <ul class="navbar-nav my-lg-0">
            <!-- ============================================================== -->
            <!-- Profile -->
            <!-- ============================================================== -->
            <li class="nav-item dropdown u-pro">
            <!-- <a
                class="
                nav-link
                dropdown-toggle
                waves-effect waves-dark
                profile-pic
                "
                href="profile.php"
                id="navbarDropdown"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                ><i class="fa fa-user"></i>
                <span class="hidden-md-down">Profile&nbsp;</span>
            </a> -->
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown"></ul>
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
