<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StartupPlatform - Premium Venture Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/lucide@latest/dist/lucide.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="landing-body" style="background-color: var(--bg-color); color: var(--text-color); margin: 0; padding: 0;">

    <!-- Minimalist Transparent Navbar -->
    <nav class="landing-nav">
        <div class="nav-container" style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 3rem; max-width: 1400px; margin: 0 auto;">
            <div class="logo" style="font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.5rem; letter-spacing: -0.5px;">
                <span style="color: var(--primary-color);">Startup</span>Platform
            </div>
            <div class="nav-links" style="display: flex; gap: 2rem; align-items: center;">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('founder.panel') }}" class="nav-link">Enter Platform</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link" style="color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s;">Sign in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary" style="padding: 0.6rem 1.5rem; background-color: var(--text-color); color: var(--bg-color); border-radius: 999px; text-decoration: none; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 14px rgba(0,0,0,0.1);">Apply as Founder</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Luxury Hero Section -->
    <section class="hero-section" style="min-height: 80vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 4rem 1.5rem; position: relative; overflow: hidden;">
        
        <!-- Abstract glowing background element -->
        <div style="position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, rgba(0,0,0,0) 70%); top: -10%; left: 50%; transform: translateX(-50%); z-index: -1;"></div>

        <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 999px; background-color: var(--input-bg); border: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.875rem; font-weight: 500; margin-bottom: 2rem;">
            <i data-lucide="sparkles" style="width: 16px; color: #10b981;"></i>
            <span>The premier ecosystem for raising capital</span>
        </div>

        <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: clamp(3rem, 5vw, 4.5rem); font-weight: 800; line-height: 1.1; letter-spacing: -1.5px; color: var(--text-color); max-width: 900px; margin-bottom: 1.5rem;">
            Fund your future. <br>
            <span style="color: var(--primary-color);">Accelerate growth.</span>
        </h1>
        
        <p style="font-size: 1.125rem; color: var(--text-muted); max-width: 600px; line-height: 1.6; margin-bottom: 3rem;">
            Connect with top-tier accredited investors, manage your cap table, and scale your operations through one unified platform built exclusively for high-growth startups.
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('register') }}" class="btn" style="background-color: var(--primary-color); color: white; border-radius: 999px; padding: 1rem 2.5rem; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1.1rem; box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4); transition: transform 0.3s, box-shadow 0.3s;">
                Submit Pitch Deck <i data-lucide="arrow-right" style="width: 18px;"></i>
            </a>
            <a href="#how" style="background-color: transparent; color: var(--text-color); border: 1px solid var(--border-color); border-radius: 999px; padding: 1rem 2.5rem; text-decoration: none; font-weight: 600; transition: all 0.3s; display: inline-flex; align-items: center; font-size: 1.1rem;">
                Explore Portfolios
            </a>
        </div>

        <!-- Dashboard mockup abstract preview -->
        <div style="margin-top: 4rem; width: 100%; max-width: 1000px; height: 300px; background: linear-gradient(180deg, rgba(248, 250, 252, 0) 0%, var(--bg-color) 100%); position: relative;">
            <div style="width: 100%; height: 100%; background-color: var(--card-bg); border: 1px solid var(--border-color); border-bottom: none; border-radius: 1rem 1rem 0 0; box-shadow: 0 -20px 40px -10px rgba(0,0,0,0.05); overflow: hidden; position: relative;">
                <!-- UI Fake header -->
                <div style="height: 48px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; padding: 0 1.5rem; gap: 0.5rem;">
                    <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #ef4444;"></div>
                    <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #f59e0b;"></div>
                    <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #10b981;"></div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
