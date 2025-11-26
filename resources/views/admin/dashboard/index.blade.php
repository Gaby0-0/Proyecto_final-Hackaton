@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-left">
            <div class="logo-section">
                <div class="logo-circle">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="logo-text">
                    <h3>ConcursITO</h3>
                    <p>Sistema de Gestión de Competencias</p>
                </div>
            </div>
        </div>
        <div class="header-right">
            <div class="notification-bell">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </div>
            <div class="user-info">
                <span class="user-initials">MGS</span>
                <div class="user-details">
                    <span class="user-name">Prof. María García Sánchez</span>
                    <span class="user-role">Administrador</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Título de sección -->
    <div class="page-title">
        <h2>Inicio</h2>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Total de usuarios</p>
                <h3 class="stat-value">{{ $totalUsuarios }}</h3>
                <p class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +{{ $crecimientoUsuarios }}% desde el mes pasado
                </p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon teams">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Equipos Activos</p>
                <h3 class="stat-value">{{ $equiposActivos }}</h3>
                <p class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +{{ $crecimientoEquipos }}% desde el mes pasado
                </p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon events">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Eventos activos</p>
                <h3 class="stat-value">{{ $eventosActivos }}</h3>
                <p class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +{{ $crecimientoEventos }}% desde el mes pasado
                </p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon evaluations">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Evaluaciones Pendientes</p>
                <h3 class="stat-value">{{ $evaluacionesPendientes }}</h3>
                <p class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> +12% desde el mes pasado
                </p>
            </div>
        </div>
    </div>

    <!-- Secciones principales -->
    <div class="content-grid">
        <!-- Eventos recientes -->
        <div class="content-section">
            <h3 class="section-title">Eventos recientes</h3>
            <div class="events-list">
                @foreach($eventosRecientes as $evento)
                <div class="event-card">
                    <div class="event-info">
                        <h4 class="event-title">{{ $evento->nombre }}</h4>
                        <p class="event-meta">
                            {{ $evento->fecha_inicio->format('d-m') }} 
                            {{ $evento->fecha_fin ? ' - ' . $evento->fecha_fin->format('d-m') : '' }} 
                            {{ $evento->fecha_fin ? $evento->fecha_fin->format('M') : $evento->fecha_inicio->format('M') }} 
                            • {{ $evento->participantes->count() }} participantes
                        </p>
                    </div>
                    <div class="event-actions">
                        <span class="event-badge {{ $evento->estado }}">{{ ucfirst($evento->estado) }}</span>
                        <a href="{{ route('admin.eventos.show', $evento->id) }}" class="btn-ver">Ver</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Actividad del sistema -->
        <div class="content-section">
            <h3 class="section-title">Actividad del Sistema</h3>
            <div class="activity-list">
                @foreach($actividadSistema as $actividad)
                <div class="activity-item">
                    <div class="activity-icon {{ $actividad['icono'] }}">
                        <i class="fas fa-{{ $actividad['icono'] === 'success' ? 'check-circle' : ($actividad['icono'] === 'error' ? 'exclamation-circle' : 'info-circle') }}"></i>
                    </div>
                    <div class="activity-content">
                        <p class="activity-message">{{ $actividad['mensaje'] }}</p>
                        <p class="activity-time">{{ $actividad['tiempo'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush