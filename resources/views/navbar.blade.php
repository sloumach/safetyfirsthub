
<div class="navbar-area">
    <!-- Menu For Mobile Device -->
    <div class="mobile-nav">
        <a href="/" class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" class="main-logo" alt="Logo">
            <img src="{{ asset('assets/img/logo-2.png') }}" class="white-logo" alt="Logo">
        </a>
    </div>

    <!-- Menu For Desktop Device -->
    <div class="main-nav">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-md">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('assets/img/logo.png') }}" class="main-logo" alt="Logo">
                    <img src="{{ asset('assets/img/logo-2.png') }}" class="white-logo" alt="Logo">
                </a>

                <div class="collapse navbar-collapse mean-menu">
                    <ul class="navbar-nav m-auto circleBehind">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                Home
                                {{-- <i class="bx bx-chevron-down"></i> --}}
                            </a>

                            {{-- <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a href="/" class="nav-link active">Home One</a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-2.html" class="nav-link">Home Two</a>
                                </li>
                                <li class="nav-item">
                                    <a href="index-3.html" class="nav-link">Home Three</a>
                                </li>
                            </ul> --}}
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('courses') }}" class="nav-link {{ request()->routeIs('courses*') ? 'active' : '' }}">
                                Certifications
                                {{-- <i class="bx bx-chevron-down"></i> --}}
                            </a>

                            {{-- <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a href="courses.html" class="nav-link">Courses</a>
                                </li>
                                <li class="nav-item">
                                    <a href="single-course.html" class="nav-link">Single Course</a>
                                </li>
                            </ul> --}}
                        </li>

                        {{-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                Pages
                                <i class="bx bx-chevron-down"></i>
                            </a>

                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a href="about.html" class="nav-link">About</a>
                                </li>
                                <li class="nav-item">
                                    <a href="feedback.html" class="nav-link">Feedback</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        Events
                                        <i class='bx bx-chevron-right'></i>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li class="nav-item">
                                            <a href="events.html" class="nav-link">Events</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="single-event.html" class="nav-link">Single Event</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="instructors.html" class="nav-link">Instructors</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        User
                                        <i class='bx bx-chevron-right'></i>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li class="nav-item">
                                            <a href="my-account.html" class="nav-link">My Account</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="log-in.html" class="nav-link">Log In</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="registration.html" class="nav-link">Registration</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="recover-password.html" class="nav-link">Recover Password</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="gallery.html" class="nav-link">Gallery</a>
                                </li>
                                <li class="nav-item">
                                    <a href="faq.html" class="nav-link">FAQ</a>
                                </li>
                                <li class="nav-item">
                                    <a href="privacy-policy.html" class="nav-link">Privacy Policy</a>
                                </li>
                                <li class="nav-item">
                                    <a href="terms-conditions.html" class="nav-link">Terms & Conditions</a>
                                </li>
                                <li class="nav-item">
                                    <a href="coming-soon.html" class="nav-link">Coming Soon</a>
                                </li>
                                <li class="nav-item">
                                    <a href="404.html" class="nav-link">404 Error Page</a>
                                </li>
                            </ul>
                        </li> --}}

                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->routeIs('shop*', 'cart*', 'wishlist*') ? 'active' : '' }}">
                                Shop
                                <i class="bx bx-chevron-down"></i>
                            </a>

                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('cart') }}" class="nav-link {{ request()->routeIs('cart') ? 'active' : '' }}">Cart</a>
                                </li>
                               {{--  <li class="nav-item">
                                    <a href="checkout.html" class="nav-link">Checkout</a>
                                </li>
                                <li class="nav-item">
                                    <a href="single-product.html" class="nav-link">Single Product</a>
                                </li> --}}
                                <li class="nav-item">
                                    <a href="{{ route('wishlist') }}" class="nav-link {{ request()->routeIs('wishlist') ? 'active' : '' }}">Wishlist</a>
                                </li>
                            </ul>
                        </li>

                        {{-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                Blog
                                <i class="bx bx-chevron-down"></i>
                            </a>

                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a href="blog-column-one.html" class="nav-link">Blog Column One</a>
                                </li>
                                <li class="nav-item">
                                    <a href="blog-column-two.html" class="nav-link">Blog Column Two</a>
                                </li>
                                <li class="nav-item">
                                    <a href="blog-column-three.html" class="nav-link">Blog Column Three</a>
                                </li>
                                <li class="nav-item">
                                    <a href="blog-left-sidebar.html" class="nav-link">Blog Left Sidebar</a>
                                </li>
                                <li class="nav-item">
                                    <a href="single-blog.html" class="nav-link">Single Blog</a>
                                </li>
                            </ul>
                        </li> --}}
                        @if(Auth::check() && Auth::user()->hasRole('student'))
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                                Dashboard
                                <i class="bx bx-chevron-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a href="{{ url('/dashboard/courses') }}" class="nav-link {{ request()->is('dashboard/courses*') ? 'active' : '' }}">Courses</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/dashboard/exams') }}" class="nav-link {{ request()->is('dashboard/exams*') ? 'active' : '' }}">Exams</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/dashboard/certificate') }}" class="nav-link {{ request()->is('dashboard/certificate*') ? 'active' : '' }}">Certificates</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                        </li>
                    </ul>

                    <!-- Start Other Option -->
                    <div class="others-option">
                        <div class="option-item">
                            <i class="search-btn bx bx-search"></i>
                            <i class="close-btn bx bx-x"></i>

                            <div class="search-overlay search-popup">
                                <div class='search-box'>
                                    <form class="search-form">
                                        <input class="search-input" name="search" placeholder="Search" type="text">

                                        <button class="search-button" type="submit"><i class="bx bx-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="cart-icon">
                            <a href="{{ route('cart') }}">
                                <i class="flaticon-shopping-cart"></i>
                                <span>{{ $cartCount }}</span>
                            </a>
                        </div>
                        @guest
                        <div class="register">
                            <a href="{{ route('login') }}" class="default-btn">
                                Login
                            </a>

                            <a href="{{ route('register') }}" class="default-btn">
                                register
                            </a>
                        </div>
                        @else
                        <div class="register">
                            <div class="nav-profile-dropdown">
                                <a href="#" onclick="toggleProfileMenu(event)" style="text-decoration: none;">
                                    <div class="nav-profile-icon">
                                        <img src="{{ asset('assets/img/man.png') }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </a>
                                <div class="profile-dropdown-menu" id="profileDropdown">
                                    <a href="{{ route('profile') }}" class="dropdown-item">
                                        <i class='bx bx-user-circle'></i>
                                        Profile
                                    </a>
                                    @if (Auth::user()->hasRole('admin'))
                                    <a href="{{ route('admincourses') }}" class="dropdown-item">
                                        <i class='bx bx-user-circle'></i>
                                        Admin Panel
                                    </a>
                                    @endif

                                    <a href="{{ route('logout') }}" class="dropdown-item">
                                        <i class='bx bx-log-out'></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endguest

                    </div>
                    <!-- End Other Option -->
                </div>
            </nav>
        </div>
    </div>

    <!-- Start Others Option For Responsive -->
    <div class="others-option-for-responsive">
        <div class="container">
            <div class="dot-menu">
                <div class="inner">
                    <div class="circle circle-one"></div>
                    <div class="circle circle-two"></div>
                    <div class="circle circle-three"></div>
                </div>
            </div>

            <div class="container">
                <div class="option-inner">
                    <div class="others-option justify-content-center d-flex align-items-center">
                        <div class="option-item">
                            <i class="search-btn bx bx-search"></i>
                            <i class="close-btn bx bx-x"></i>

                            <div class="search-overlay search-popup">
                                <div class='search-box'>
                                    <form class="search-form">
                                        <input class="search-input" name="search" placeholder="Search" type="text">

                                        <button class="search-button" type="submit"><i class="bx bx-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="cart-icon">
                            <a href="{{ route('cart') }}">
                                <i class="flaticon-shopping-cart"></i>
                                <span class="mob">0</span>
                            </a>
                        </div>
                        @guest
                        <div class="register">
                            <a href="{{ route('login') }}" class="default-btn">
                                Login
                            </a>

                            <a href="{{ route('register') }}" class="default-btn">
                                register
                            </a>
                        </div>
                        @else
                        <div class="register">
                            <div class="nav-profile-dropdown">
                                <a href="#" onclick="toggleProfileMenu(event)" style="text-decoration: none;">
                                    <div class="nav-profile-icon">
                                        <img src="{{ asset('assets/img/man.png') }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </a>
                                <div class="profile-dropdown-menu" id="profileDropdown">
                                    <a href="{{ route('profile') }}" class="dropdown-item">
                                        <i class='bx bx-user-circle'></i>
                                        Profile
                                    </a>
                                    <a href="{{ route('logout') }}" class="dropdown-item">
                                        <i class='bx bx-log-out'></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endguest

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleProfileMenu(event) {
    event.preventDefault();
    // Get both desktop and mobile dropdowns
    const dropdowns = document.querySelectorAll('.profile-dropdown-menu');

    // Toggle both dropdowns
    dropdowns.forEach(dropdown => {
        dropdown.classList.toggle('show');
    });

    // Close when clicking outside
    document.addEventListener('click', function closeDropdown(e) {
        if (!e.target.closest('.nav-profile-dropdown')) {
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
            document.removeEventListener('click', closeDropdown);
        }
    });

    // Stop event propagation
    event.stopPropagation();
}

// Add this to handle mobile menu container clicks
document.addEventListener('DOMContentLoaded', function() {
    const mobileOptionInner = document.querySelector('.option-inner');
    if (mobileOptionInner) {
        mobileOptionInner.addEventListener('click', function(e) {
            // Prevent clicks inside the mobile menu from closing it
            e.stopPropagation();
        });
    }
});
</script>

