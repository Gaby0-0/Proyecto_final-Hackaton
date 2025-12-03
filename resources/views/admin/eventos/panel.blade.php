@extends('layouts.paneleventos')

@section('title', 'Eventos de Programación')

@section('content')
<style>
    /* Reset y estilos base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f3f4f6;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
    }

    /* Layout principal */
    .main-layout {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
        width: 260px;
        background: white;
        border-right: 1px solid #e5e7eb;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background: #0f766e;
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 20px;
    }

    .logo-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .logo-subtitle {
        font-size: 11px;
        color: #6b7280;
    }

    .sidebar-nav {
        padding: 12px;
    }

    .nav-section-title {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px 8px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #4b5563;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 4px;
        transition: all 0.2s;
        font-size: 14px;
    }

    .nav-item:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .nav-item.active {
        background: #d1fae5;
        color: #065f46;
        font-weight: 500;
    }

    .nav-icon {
        font-size: 18px;
    }

    /* Contenido principal */
    .main-content {
        flex: 1;
        margin-left: 260px;
        background: #f9fafb;
    }

    /* Header superior */
    .top-header {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 16px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #6b7280;
    }

    .breadcrumb a {
        color: #0f766e;
        text-decoration: none;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .icon-btn {
        position: relative;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
    }

    .badge {
        position: absolute;
        top: 4px;
        right: 4px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 10px;
        font-weight: bold;
    }

    .user-menu {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .user-menu:hover {
        background: #f3f4f6;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #0f766e;
    }

    .user-name {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
    }

    .user-role {
        font-size: 12px;
        color: #6b7280;
        background: #f0fdfa;
        padding: 2px 8px;
        border-radius: 4px;
    }

    /* Contenedor de contenido */
    .content-wrapper {
        padding: 32px;
        max-width: 1400px;
    }

    /* Header de página */
    .page-header {
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6b7280;
    }

    /* Filtros */
    .filters-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .filters-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 16px;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .search-input {
        position: relative;
    }

    .search-input input {
        width: 100%;
        padding: 10px 12px 10px 38px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 12px center;
    }

    select {
        padding: 10px 32px 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none;
    }

    .btn-clear {
        padding: 10px 20px;
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-clear:hover {
        background: #f9fafb;
    }

    /* Tarjetas de eventos */
    .events-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .event-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.2s;
        position: relative;
    }

    .event-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .event-title-section {
        flex: 1;
    }

    .event-title {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .event-status {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-registration {
        background: #fef3c7;
        color: #92400e;
    }

    .status-upcoming {
        background: #dbeafe;
        color: #1e40af;
    }

    .event-description {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .event-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }

    .tag {
        background: #f3f4f6;
        color: #374151;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .event-meta {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .meta-icon {
        font-size: 18px;
    }

    .meta-content {
        display: flex;
        flex-direction: column;
    }

    .meta-label {
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 14px;
        color: #111827;
        font-weight: 500;
    }

    .btn-details {
        padding: 10px 20px;
        background: white;
        border: 1px solid #d1d5db;
        color: #374151;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        font-weight: 500;
    }

    .btn-details:hover {
        background: #f9fafb;
        border-color: #0f766e;
        color: #0f766e;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .filters-grid {
            grid-template-columns: 1fr 1fr;
        }

        .event-meta {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 70px;
        }

        .main-content {
            margin-left: 70px;
        }

        .logo-title,
        .logo-subtitle,
        .nav-item span:not(.nav-icon) {
            display: none;
        }

        .content-wrapper {
            padding: 20px;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }

        .event-meta {
            grid-template-columns: 1fr;
        }

        .event-header {
            flex-direction: column;
            gap: 12px;
        }
    }
</style>

<div class="main-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">C</div>
                <div>
                    <div class="logo-title">Panel Eventos</div>
                    <div class="logo-subtitle">Sistema de Gestión de Competencias</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Principal</div>
            <a href="#" class="nav-item">
                <span class="nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ url('/equipos') }}" class="nav-item">
                <span class="nav-icon">👥</span>
                <span>Mis Equipos</span>
            </a>
            <a href="{{ url('/eventos/panel') }}" class="nav-item active">
                <span class="nav-icon">📅</span>
                <span>Eventos</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">🏆</span>
                <span>Mis Participaciones</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">📄</span>
                <span>Documentos</span>
            </a>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
        <!-- Header -->
        <header class="top-header">
            <div class="breadcrumb">
                <a href="#">Inicio</a>
                <span>/</span>
                <span>Eventos</span>
            </div>
            <div class="header-actions">
                <button class="icon-btn">
                    🔔
                    <span class="badge">5</span>
                </button>
                <div class="user-menu">
                    <img src="https://ui-avatars.com/api/?name=Ana+Perez&background=0f766e&color=fff" 
                         alt="Ana Perez Martinez" class="user-avatar">
                    <div>
                        <div class="user-name">Ana Pérez Martínez</div>
                        <span class="user-role">Estudiante</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            <!-- Título -->
            <div class="page-header">
                <h1 class="page-title">Eventos de Programación</h1>
                <p class="page-subtitle">Descubre y participa en competencias de programación organizadas por la universidad</p>
            </div>

            <!-- Filtros -->
            <div class="filters-section">
                <h2 class="filters-title">Filtrar Eventos</h2>
                <div class="filters-grid">
                    <div class="filter-group">
                        <div class="search-input">
                            <input type="text" placeholder="Buscar eventos...">
                        </div>
                    </div>
                    <div class="filter-group">
                        <select>
                            <option>Todos los estados</option>
                            <option>Activo</option>
                            <option>Registración</option>
                            <option>Próximamente</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select>
                            <option>Todas las modalidades</option>
                            <option>Presencial</option>
                            <option>Virtual</option>
                            <option>Híbrido</option>
                        </select>
                    </div>
                    <button class="btn-clear">
                        🗑️ Limpiar Filtros
                    </button>
                </div>
            </div>

            <!-- Lista de eventos -->
            <div class="events-list">
                <!-- Evento 1 -->
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-title-section">
                            <h3 class="event-title">
                                Hackathon Universitario 2025
                                <span class="event-status status-active">Activo</span>
                                <span class="event-status status-registration">Registración</span>
                            </h3>
                            <p class="event-description">
                                Desarrollo de soluciones tecnológicas innovadoras para resolver problemas sociales y ambientales. Los participantes trabajarán en equipos multidisciplinarios.
                            </p>
                            <div class="event-tags">
                                <span class="tag">Web Development</span>
                                <span class="tag">Mobile Apps</span>
                                <span class="tag">Social Impact</span>
                            </div>
                        </div>
                        <button class="btn-details">👁️ Ver Detalles</button>
                    </div>
                    <div class="event-meta">
                        <div class="meta-item">
                            <span class="meta-icon">📅</span>
                            <div class="meta-content">
                                <span class="meta-label">Fecha de Inicio</span>
                                <span class="meta-value">15/3/2025</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🏢</span>
                            <div class="meta-content">
                                <span class="meta-label">Modalidad</span>
                                <span class="meta-value">Presencial</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">👥</span>
                            <div class="meta-content">
                                <span class="meta-label">Participantes</span>
                                <span class="meta-value">45/80</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">📆</span>
                            <div class="meta-content">
                                <span class="meta-label">Registro hasta</span>
                                <span class="meta-value">10/3/2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evento 2 -->
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-title-section">
                            <h3 class="event-title">
                                Concurso de Algoritmos ACM-ICPC
                                <span class="event-status status-upcoming">Próximamente</span>
                            </h3>
                            <p class="event-description">
                                Competencia de programación competitiva estilo ACM-ICPC. Problemas de algoritmos y estructuras de datos con tiempo límite.
                            </p>
                            <div class="event-tags">
                                <span class="tag">Algorithms</span>
                                <span class="tag">Data Structures</span>
                                <span class="tag">Competitive Programming</span>
                            </div>
                        </div>
                        <button class="btn-details">👁️ Ver Detalles</button>
                    </div>
                    <div class="event-meta">
                        <div class="meta-item">
                            <span class="meta-icon">📅</span>
                            <div class="meta-content">
                                <span class="meta-label">Fecha de Inicio</span>
                                <span class="meta-value">10/4/2025</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🏢</span>
                            <div class="meta-content">
                                <span class="meta-label">Modalidad</span>
                                <span class="meta-value">Presencial</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">👥</span>
                            <div class="meta-content">
                                <span class="meta-label">Participantes</span>
                                <span class="meta-value">32/100</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">📆</span>
                            <div class="meta-content">
                                <span class="meta-label">Registro hasta</span>
                                <span class="meta-value">5/4/2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evento 3 -->
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-title-section">
                            <h3 class="event-title">
                                Desarrollo Web Challenge
                                <span class="event-status status-upcoming">Próximamente</span>
                            </h3>
                            <p class="event-description">
                                Competencia de desarrollo web con fullstack. Los equipos crearán aplicaciones web completas con frontend y backend en 48 horas.
                            </p>
                            <div class="event-tags">
                                <span class="tag">Frontend</span>
                                <span class="tag">Backend</span>
                                <span class="tag">Full Stack</span>
                                <span class="tag">Web Development</span>
                            </div>
                        </div>
                        <button class="btn-details">👁️ Ver Detalles</button>
                    </div>
                    <div class="event-meta">
                        <div class="meta-item">
                            <span class="meta-icon">📅</span>
                            <div class="meta-content">
                                <span class="meta-label">Fecha de Inicio</span>
                                <span class="meta-value">25/4/2025</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🏢</span>
                            <div class="meta-content">
                                <span class="meta-label">Modalidad</span>
                                <span class="meta-value">Virtual</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">👥</span>
                            <div class="meta-content">
                                <span class="meta-label">Participantes</span>
                                <span class="meta-value">18/60</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">📆</span>
                            <div class="meta-content">
                                <span class="meta-label">Registro hasta</span>
                                <span class="meta-value">20/4/2025</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection