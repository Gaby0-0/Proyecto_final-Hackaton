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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Inicio</a>
        <i class="fas fa-chevron-right"></i>
        <span>Eventos</span>
    </div>

    <!-- Page Header -->
    <div class="page-header-eventos">
        <div>
            <h2 class="page-main-title">Gestión de Eventos</h2>
            <p class="page-subtitle">Administra y organiza los eventos de competencias de programación.</p>
        </div>
        <a href="{{ route('admin.eventos.create') }}" class="btn-crear">
            <i class="fas fa-plus"></i> Crear evento
        </a>
    </div>

    <!-- Filtros -->
    <div class="filters-section-eventos">
        <div class="search-filter-group">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar eventos..." id="buscar">
            </div>
        </div>

        <div class="select-filters-group">
            <select id="estado" class="filter-select">
                <option value="todos">Todos los</option>
                <option value="activo">Activo</option>
                <option value="programado">Programado</option>
                <option value="finalizado">Finalizado</option>
                <option value="cancelado">Cancelado</option>
            </select>

            <select id="modalidad" class="filter-select">
                <option value="todas">Todas las</option>
                <option value="presencial">Presencial</option>
                <option value="virtual">Virtual</option>
                <option value="hibrida">Híbrida</option>
            </select>

            <button class="btn-filter-icon">
                <i class="fas fa-sliders-h"></i>
            </button>
        </div>
    </div>

    <!-- Tabla de eventos -->
    <div class="table-section">
        <div class="table-container">
            <table class="eventos-table">
                <thead>
                    <tr>
                        <th class="checkbox-col">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>Nombre</th>
                        <th>Proyecto</th>
                        <th>Fechas</th>
                        <th>Estado</th>
                        <th>Modalidad</th>
                        <th>Participantes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventos as $evento)
                    <tr>
                        <td class="checkbox-col">
                            <input type="checkbox" class="evento-checkbox">
                        </td>
                        <td>
                            <div class="evento-nombre">{{ $evento->nombre }}</div>
                        </td>
                        <td>
                            <div class="proyecto-cell">{{ $evento->proyecto }}</div>
                        </td>
                        <td>
                            <div class="fechas-cell">
                                {{ $evento->fecha_inicio }} -<br>
                                {{ $evento->fecha_fin }}
                            </div>
                        </td>
                        <td>
                            <span class="badge-estado-evento {{ strtolower($evento->estado) }}">
                                {{ $evento->estado }}
                            </span>
                        </td>
                        <td>
                            <div class="modalidad-cell">{{ $evento->modalidad }}</div>
                        </td>
                        <td>
                            <div class="participantes-cell">
                                {{ $evento->participantes_actuales }} / {{ $evento->participantes_max }}
                            </div>
                        </td>
                        <td>
                            <button class="btn-more-options">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pagination-section">
            <div class="pagination-info">
                Mostrando 1 a 5 de 5 eventos
            </div>
            <div class="pagination-controls">
                <button class="pagination-btn" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/eventos.css') }}">
@endpush