<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investor Dashboard - StartupPlatform</title>
    <!-- Modern lightweight icons -->
    <link href="https://unpkg.com/lucide@latest/dist/lucide.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/investor.css') }}">
    <!-- Load Theme Automatically Before Rendering DOM -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</head>
<body>

    <!-- Sidebar Architecture -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            InvestHub
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('investor.panel') }}" class="{{ request()->routeIs('investor.panel') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" style="margin-right: 12px; width: 18px;"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('investor.startups.index') }}" class="{{ request()->routeIs('investor.startups.index') ? 'active' : '' }}">
                    <i data-lucide="search" style="margin-right: 12px; width: 18px;"></i>
                    Browse Startups
                </a>
            </li>
            <li>
                <a href="{{ route('investor.portfolio.index') }}" class="{{ request()->routeIs('investor.portfolio.index') ? 'active' : '' }}">
                    <i data-lucide="pie-chart" style="margin-right: 12px; width: 18px;"></i>
                    My Investments
                </a>
            </li>
            <li>
                <a href="{{ route('investor.bookmarks.index') }}" class="{{ request()->routeIs('investor.bookmarks.index') ? 'active' : '' }}">
                    <i data-lucide="bookmark" style="margin-right: 12px; width: 18px;"></i>
                    Saved Startups
                </a>
            </li>
            <li>
                <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.index') ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <i data-lucide="bell" style="margin-right: 12px; width: 18px;"></i>
                        Notifications
                    </div>
                    @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                        <span style="background-color: #ef4444; color: white; font-size: 0.7rem; font-weight: 700; padding: 2px 6px; border-radius: 10px;">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i data-lucide="user" style="margin-right: 12px; width: 18px;"></i>
                    Profile
                </a>
            </li>
            
            <li style="margin-top: 2rem;">
                <!-- Logout connects to standard AuthController flow -->
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                </form>
                <a href="#" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-lucide="log-out" style="margin-right: 12px; width: 18px;"></i>
                    Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Working Area -->
    <div class="main-wrapper">
        
        <!-- Top Navigation -->
        <header class="topbar">
            <button class="mobile-toggle" id="mobileToggle">
                <i data-lucide="menu"></i>
            </button>
            <div style="flex-grow: 1;"></div> <!-- Spacer -->
            <div class="topbar-right">
                <!-- Theme Toggle Button -->
                <button id="themeToggle" style="background:none; border:none; color: var(--text-color); cursor:pointer; padding: 0.5rem; display:flex; align-items:center; opacity: 0.7; transition: opacity 0.2s;">
                    <i data-lucide="moon" id="themeIcon"></i>
                </button>
                
                <div class="user-profile">
                    <div class="avatar">
                        @auth
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @else
                            I
                        @endauth
                    </div>
                    <span>
                        @auth
                            {{ Auth::user()->name }}
                        @else
                            Investor
                        @endauth
                    </span>
                </div>
            </div>
        </header>

        <!-- Dynamic Page Content -->
        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- Inject Lucide Icons JS -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        // Dark Mode Logic
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
        function updateThemeIcon() {
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                themeIcon.setAttribute('data-lucide', 'sun');
            } else {
                themeIcon.setAttribute('data-lucide', 'moon');
            }
            lucide.createIcons(); 
        }
        
        updateThemeIcon(); // Initial Setup

        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            if (currentTheme === 'dark') {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcon();
        });

        // Responsive sidebar logic
        const toggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Interactive Intersection Observer for Premium UI Animations
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        // Stagger the animation timing based on DOM index
                        setTimeout(() => {
                            entry.target.classList.add('animate-reveal');
                        }, index * 100); // 100ms stagger between cards
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const cards = document.querySelectorAll('.card, .table-container');
            cards.forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</body>
</html>
