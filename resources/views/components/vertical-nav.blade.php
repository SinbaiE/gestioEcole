<div class="vertical-nav">
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt text-blue-500"></i>
        <span>{{ __('Tableau de bord') }}</span>
    </a>
    <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt text-green-500"></i>
        <span>{{ __('Réservations') }}</span>
    </a>
    <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
        <i class="fas fa-bed text-yellow-500"></i>
        <span>{{ __('Chambres') }}</span>
    </a>
    <a href="{{ route('guests.index') }}" class="nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}">
        <i class="fas fa-users text-purple-500"></i>
        <span>{{ __('Clients') }}</span>
    </a>
    <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
        <i class="fas fa-concierge-bell text-red-500"></i>
        <span>{{ __('Services') }}</span>
    </a>
    <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar text-indigo-500"></i>
        <span>{{ __('Factures') }}</span>
    </a>
    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar text-pink-500"></i>
        <span>{{ __('Rapports') }}</span>
    </a>
</div>
