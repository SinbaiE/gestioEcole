<div class="vertical-nav">
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>{{ __('Tableau de bord') }}</span>
    </a>
    <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i>
        <span>{{ __('Réservations') }}</span>
    </a>
    <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
        <i class="fas fa-bed"></i>
        <span>{{ __('Chambres') }}</span>
    </a>
    <a href="{{ route('guests.index') }}" class="nav-link {{ request()->routeIs('guests.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>{{ __('Clients') }}</span>
    </a>
    <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
        <i class="fas fa-concierge-bell"></i>
        <span>{{ __('Services') }}</span>
    </a>
    <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i>
        <span>{{ __('Factures') }}</span>
    </a>
    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i>
        <span>{{ __('Rapports') }}</span>
    </a>
</div>
