<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - StartupPlatform</title>
    <!-- Modern lightweight icons (Lucide) -->
    <link href="https://unpkg.com/lucide@latest/dist/lucide.css" rel="stylesheet">
    <!-- Load custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- Load Theme Automatically Before Rendering DOM to prevent screen flicker -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</head>
<body>

    <!-- Sidebar Responsive Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Responsive Sidebar Drawer Architecture -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <i data-lucide="shield-check" style="width: 22px; height: 22px;"></i>
            </div>
            <span class="sidebar-title">FintechAdmin</span>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.panel') }}" class="{{ request()->routeIs('admin.panel') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.startups.index') }}" class="{{ request()->is('admin/startups*') && !request()->routeIs('admin.panel') ? 'active' : '' }}">
                    <i data-lucide="rocket"></i>
                    <span>Startups</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.investments.index') }}" class="{{ request()->is('admin/investments*') ? 'active' : '' }}">
                    <i data-lucide="wallet"></i>
                    <span>Investments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.analytics.index') }}" class="{{ request()->is('admin/analytics*') ? 'active' : '' }}">
                    <i data-lucide="pie-chart"></i>
                    <span>Analytics</span>
                </a>
            </li>
            
            <div class="sidebar-divider"></div>
            
            <li>
                <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.index') ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                        <i data-lucide="bell"></i>
                        <span>Notifications</span>
                    </div>
                    @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                        <span style="background-color: var(--danger-color); color: white; font-size: 0.7rem; font-weight: 700; padding: 2px 6px; border-radius: 10px; line-height: 1;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i data-lucide="settings"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
            </form>
            <a href="#" class="sidebar-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i data-lucide="log-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Working Wrapper -->
    <div class="main-wrapper" id="mainWrapper">
        
        <!-- Premium Top Navbar Header -->
        <header class="topbar">
            <div class="topbar-left">
                <!-- Hamburger drawer toggle button for handheld screen sizes -->
                <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Navigation">
                    <i data-lucide="menu" style="width: 20px; height: 20px;"></i>
                </button>
                
                <!-- Quick search bar widget -->
                <div class="search-bar">
                    <i data-lucide="search"></i>
                    <input type="text" placeholder="Search startups, transactions..." id="adminSearch">
                </div>
            </div>
            
            <div class="topbar-right">
                <!-- Smooth Theme Selector Switch -->
                <button id="themeToggle" class="nav-action-btn" aria-label="Toggle Theme">
                    <i data-lucide="moon" id="themeIcon" style="width: 20px; height: 20px;"></i>
                </button>
                
                <!-- Dynamic Database Notifications Quick Dropdown -->
                <div class="dropdown-container" style="display: flex; align-items: center;">
                    <button class="nav-action-btn" aria-label="Notifications" id="notificationBell" style="background: none; border: none; cursor: pointer; position: relative;">
                        <i data-lucide="bell" style="width: 20px; height: 20px; color: var(--text-color);"></i>
                        @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                            <span class="nav-badge" style="top: 6px; right: 6px; width: 9px; height: 9px; background-color: var(--danger-color); border: 2px solid var(--card-bg); border-radius: 50%; position: absolute;"></span>
                        @endif
                    </button>
                    
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="dropdown-header">
                            <h3>Notifications</h3>
                            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.readAll') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-header-link">Mark all as read</button>
                                </form>
                            @endif
                        </div>
                        
                        <div class="dropdown-body">
                            @if(auth()->check() && auth()->user()->notifications->count() > 0)
                                @foreach(auth()->user()->notifications->take(5) as $noti)
                                    @php
                                        $iconName = $noti->data['icon'] ?? 'bell';
                                        $iconBg = 'var(--primary-glow)';
                                        $iconColor = 'var(--primary-color)';
                                        if ($iconName === 'check-circle') {
                                            $iconBg = 'var(--success-bg)';
                                            $iconColor = 'var(--success-color)';
                                        } elseif ($iconName === 'x-circle') {
                                            $iconBg = 'var(--danger-bg)';
                                            $iconColor = 'var(--danger-color)';
                                        } elseif ($iconName === 'trending-up') {
                                            $iconBg = 'var(--success-bg)';
                                            $iconColor = 'var(--success-color)';
                                        } elseif ($iconName === 'rocket') {
                                            $iconBg = 'rgba(139, 92, 246, 0.1)';
                                            $iconColor = 'rgba(139, 92, 246, 1)';
                                        }
                                    @endphp
                                    <div class="dropdown-item" style="opacity: {{ $noti->read_at ? '0.6' : '1' }}; text-align: left;">
                                        <div class="dropdown-item-icon" style="background-color: {{ $iconBg }}; color: {{ $iconColor }};">
                                            <i data-lucide="{{ $iconName }}" style="width: 16px; height: 16px;"></i>
                                        </div>
                                        <div class="dropdown-item-content">
                                            <div class="dropdown-item-title">{{ $noti->data['title'] ?? 'Platform Update' }}</div>
                                            <div class="dropdown-item-message" style="word-break: break-word;">{{ $noti->data['message'] ?? '' }}</div>
                                            <div class="dropdown-item-time">
                                                {{ $noti->created_at ? $noti->created_at->diffForHumans() : 'Just now' }}
                                            </div>
                                            @if(!$noti->read_at)
                                                <form action="{{ route('notifications.read', $noti->id) }}" method="POST" style="margin-top: 0.25rem;">
                                                    @csrf
                                                    <button type="submit" style="background: none; border: none; font-size: 0.725rem; color: var(--primary-color); font-weight: 600; cursor: pointer; padding: 0;">
                                                        Mark read
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div style="text-align: center; padding: 2rem 1rem; color: var(--text-muted); font-size: 0.825rem;">
                                    <i data-lucide="bell-off" style="width: 24px; height: 24px; margin-bottom: 0.5rem; opacity: 0.5; display: inline-block;"></i>
                                    <div>All caught up!</div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="dropdown-footer">
                            <a href="{{ route('notifications.index') }}">View All Notifications</a>
                        </div>
                    </div>
                </div>
                
                <!-- Compact Profile display -->
                <div class="user-profile">
                    <div class="user-avatar">
                        @auth
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @else
                            A
                        @endauth
                        <div class="user-avatar-indicator"></div>
                    </div>
                    <div class="user-info" style="display: none; display: md-flex;">
                        <span class="user-name">
                            @auth
                                {{ Auth::user()->name }}
                            @else
                                Admin User
                            @endauth
                        </span>
                        <span class="user-role">Platform Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Dashboard Page Content Frame -->
        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- Inject Lucide Icons Javascript dependencies -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        // Dark/Light Mode retaining scripts
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
        
        updateThemeIcon(); // Run immediately

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

        // Sidebar Responsive mobile drawer controller logic
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        }

        mobileToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Bell Quick Dropdown Toggle Logic
        const bellBtn = document.getElementById('notificationBell');
        const dropdownMenu = document.getElementById('notificationDropdown');

        if (bellBtn && dropdownMenu) {
            bellBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });

            document.addEventListener('click', (e) => {
                if (!dropdownMenu.contains(e.target) && !bellBtn.contains(e.target)) {
                    dropdownMenu.classList.remove('active');
                }
            });
        }

        // Interactive reveal observer for clean loading micro-animations
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.05
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('animate-reveal');
                        }, index * 80);
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const animatableItems = document.querySelectorAll('.stat-card, .panel-card, .dashboard-header');
            animatableItems.forEach(item => {
                observer.observe(item);
            });
        });
    </script>
</body>
</html>
