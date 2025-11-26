<aside class="sidebar">
    <div class="sidebar-header">
        <h4>Administración</h4>
    </div>
    
    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Panel</span>
        </a>
        
        <a href="{{ route('admin.usuarios.index') }}" class="nav-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Gestión de usuarios</span>
        </a>
        
        <a href="{{ route('admin.eventos.index') }}" class="nav-item {{ request()->routeIs('admin.eventos.*') ? 'active' : '' }}">
            <i class="far fa-calendar"></i>
            <span>Gestión de Eventos</span>
        </a>
        
        <a href="{{ route('admin.proyectos.index') }}" class="nav-item {{ request()->routeIs('admin.proyectos.*') ? 'active' : '' }}">
            <i class="far fa-folder"></i>
            <span>Proyectos</span>
        </a>
        
        <a href="{{ route('admin.evaluaciones.index') }}" class="nav-item {{ request()->routeIs('admin.evaluaciones.*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span>Evaluaciones</span>
        </a>
        
        <a href="{{ route('admin.constancias.index') }}" class="nav-item {{ request()->routeIs('admin.constancias.*') ? 'active' : '' }}">
            <i class="fas fa-award"></i>
            <span>Constancias</span>
        </a>
        
        <a href="{{ route('admin.informes.index') }}" class="nav-item {{ request()->routeIs('admin.informes.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Informes</span>
        </a>
        
        <a href="{{ route('admin.configuracion.index') }}" class="nav-item {{ request()->routeIs('admin.configuracion.*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Configuración</span>
        </a>
    </nav>
</aside>