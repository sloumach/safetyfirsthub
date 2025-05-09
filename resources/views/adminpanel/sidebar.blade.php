<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    {{-- <li class="nav-item active">
        <a class="nav-link" href="{{ route('adminindex') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li> --}}

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Management
    </div>
    <!-- Nav Item - Courses Dropdown -->
    <li class="nav-item active">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#coursesDropdown"
            aria-expanded="true" aria-controls="coursesDropdown" style="color: white;">
            <i class="fas fa-fw fa-book"></i>
            <span>Courses</span>
        </a>
        <div id="coursesDropdown" class="collapse" aria-labelledby="headingCourses" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Course Management:</h6>
                <a class="collapse-item" href="{{ route('admincourses') }}">
                    <i class="fas fa-plus fa-sm fa-fw mr-2"></i>Add Course
                </a>
                <a class="collapse-item" href="{{ route('removecourses') }}">
                    <i class="fas fa-list fa-sm fa-fw mr-2"></i>List Courses
                </a>
            </div>
        </div>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('adminfinanceindex') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Finances</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.coupons.index') }}">
            <i class="fas fa-tags"></i> <!-- Icône des coupons -->
            <span>Coupons Management</span>
        </a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.quizzes.index') }}">
            <i class="fas fa-question-circle"></i> <!-- Icône de quiz -->
            <span>Quiz Management</span>
        </a>
    </li>
    <!-- <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="quizDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-list-ul"></i>
            <span>Quiz Questions</span>
        </a>
    </li> -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('usersManagement') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Users Management</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.exams') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Exams</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.contacts.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Messages</span></a>
    </li>



 {{--    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Login Screens:</h6>
                <a class="collapse-item" href="login.html">Login</a>
                <a class="collapse-item" href="register.html">Register</a>
                <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
 --}}

</ul>
