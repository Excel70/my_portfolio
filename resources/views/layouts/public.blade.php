<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portfolio of Aderinlewo Excel Olamilekan — software engineer, web developer, UI/UX designer, graphic designer and Web3 enthusiast.">
    <meta property="og:title" content="{{ ($settings['name'] ?? 'Aderinlewo Excel Olamilekan').' | '.($settings['title'] ?? 'Software Engineer & Digital Product Builder') }}">
    <meta property="og:description" content="A modern portfolio of digital products, design, and emerging technology exploration.">
    <title>@yield('title', ($settings['name'] ?? 'Aderinlewo Excel Olamilekan').' | '.($settings['title'] ?? 'Software Engineer & Digital Product Builder'))</title>
    <link rel="canonical" href="{{ url()->current() }}"><link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <header class="site-header"><div class="shell nav-wrap">
        <a class="brand" href="{{ route('home') }}" aria-label="Excel home">EXCEL<span>.</span></a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Menu <span aria-hidden="true">☰</span></button>
        <nav id="main-nav" class="main-nav" aria-label="Main navigation">
            <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a><a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>About</a><a href="{{ route('skills') }}" @class(['active' => request()->routeIs('skills')])>Skills</a><a href="{{ route('services') }}" @class(['active' => request()->routeIs('services')])>Services</a><a href="{{ route('projects.index') }}" @class(['active' => request()->routeIs('projects.*')])>Projects</a><a href="{{ route('articles.index') }}" @class(['active' => request()->routeIs('articles.*')])>Writing</a><a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>Contact</a>
            <a class="button button-small" href="{{ route('contact') }}">Let's connect <span aria-hidden="true">↗</span></a>
        </nav>
    </div></header>
    <main id="main">@if(session('success'))<div class="shell flash" role="status">{{ session('success') }}</div>@endif @yield('content')</main>
    <footer class="site-footer"><div class="shell footer-grid"><div><a class="brand" href="{{ route('home') }}">EXCEL<span>.</span></a><p>Building practical, thoughtful digital experiences.</p></div><div><p class="eyebrow">Navigate</p><a href="{{ route('projects.index') }}">Projects</a><a href="{{ route('articles.index') }}">Writing</a><a href="{{ route('contact') }}">Contact</a></div><div><p class="eyebrow">Connect</p>@if(!empty($settings['email']))<a href="mailto:{{ $settings['email'] }}">Email</a>@endif @if(!empty($settings['linkedin_url']))<a href="{{ $settings['linkedin_url'] }}" rel="noopener noreferrer" target="_blank">LinkedIn</a>@endif @if(!empty($settings['github_url']))<a href="{{ $settings['github_url'] }}" rel="noopener noreferrer" target="_blank">GitHub</a>@endif</div></div><div class="shell copyright">© {{ now()->year }} {{ $settings['name'] ?? 'Aderinlewo Excel Olamilekan' }}. Built with care.</div></footer>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
