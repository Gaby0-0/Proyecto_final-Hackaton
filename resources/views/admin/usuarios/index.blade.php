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
        <span>Usuarios</span>
    </div>

    <!-- Page Header -->
    <div class="page-header-section">
        <h2 class="page-main-title">Gestión de usuarios</h2>
        <a href="{{ route('admin.usuarios.create') }}" class="btn-crear">
            <i class="fas fa-plus"></i> Crear usuario
        </a>
    </div>

    <!-- Filtros -->
    <div class="filters-section">
        <div class="filter-group">
            <label>Buscar</label>
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Nombre o correo electrónico" id="buscar">
            </div>
        </div>

        <div class="filter-group">
            <label>Rol</label>
            <select id="rol" class="filter-select">
                <option value="todos">Todos los roles</option>
                <option value="estudiante">Estudiante</option>
                <option value="juez">Juez</option>
                <option value="administrador">Administrador</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Estado</label>
            <select id="estado" class="filter-select">
                <option value="todos">Todos los estados</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>

        <button class="btn-filtrar">
            <i class="fas fa-filter"></i> Filtrar
        </button>
    </div>

    <!-- Tabla de usuarios -->
    <div class="table-section">
        <h3 class="table-title">Lista de usuarios</h3>
        
        <div class="table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th class="checkbox-col">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Último acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td class="checkbox-col">
                            <input type="checkbox" class="user-checkbox">
                        </td>
                        <td>
                            <div class="user-name-cell">{{ $usuario->nombre }}</div>
                        </td>
                        <td>
                            <div class="email-cell">{{ $usuario->email }}</div>
                        </td>
                        <td>
                            <span class="badge-rol {{ strtolower(str_replace(' ', '-', $usuario->rol)) }}">
                                {{ $usuario->rol }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-estado {{ strtolower($usuario->estado) }}">
                                {{ $usuario->estado }}
                            </span>
                        </td>
                        <td>
                            <div class="date-cell">{{ $usuario->ultimo_acceso }}</div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn view" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pagination-section">
            <div class="pagination-info">
                Mostrando 4 de 4 usuarios
            </div>
            <div class="pagination-controls">
                <button class="pagination-btn" disabled>Anterior</button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">Siguiente</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
@endpush