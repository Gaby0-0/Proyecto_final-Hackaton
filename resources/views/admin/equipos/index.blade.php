@extends('layouts.equipos')

@section('title', 'Gestión de Equipos')

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
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #0f766e;
    }

    /* Contenedor de contenido */
    .content-wrapper {
        padding: 32px;
        max-width: 1400px;
    }

    /* Header de página */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6b7280;
    }

    /* Botones */
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #0f766e;
        color: white;
    }

    .btn-primary:hover {
        background: #0d9488;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #f9fafb;
    }

    .btn-icon {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-icon:hover {
        background: #f3f4f6;
        color: #111827;
    }

    /* Secciones */
    .section {
        margin-bottom: 32px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .section-header h2 {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 16px;
    }

    /* Tarjeta de equipo */
    .team-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .team-header {
        margin-bottom: 24px;
    }

    .team-name {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .team-description {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .team-meta {
        display: flex;
        gap: 20px;
        font-size: 13px;
        color: #6b7280;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Sección de equipo */
    .team-section {
        border-top: 1px solid #e5e7eb;
        padding-top: 20px;
        margin-top: 20px;
    }

    .team-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 16px;
    }

    /* Tarjeta de miembro */
    .member-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .member-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #0f766e;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }

    .member-name {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .member-badge {
        font-size: 16px;
    }

    .member-email {
        font-size: 13px;
        color: #6b7280;
    }

    .member-role {
        font-size: 13px;
        color: #6b7280;
        background: white;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    /* Evento */
    .event-item {
        padding: 12px 16px;
        background: #f0fdfa;
        border-left: 3px solid #0f766e;
        border-radius: 6px;
        font-size: 14px;
        color: #111827;
        margin-bottom: 8px;
    }

    /* Búsqueda */
    .search-box {
        margin-bottom: 20px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 16px 12px 40px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 12px center;
    }

    /* Tarjeta de equipo disponible */
    .available-team-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }

    .available-team-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .available-team-info {
        flex: 1;
    }

    .available-team-name {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .available-team-description {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .available-team-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .team-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tag {
        background: #f3f4f6;
        color: #374151;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .available-team-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 12px;
    }

    .visibility-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }

    .close-btn:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: #f9fafb;
        border-radius: 0 0 12px 12px;
    }

    /* Formularios */
    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        color: #374151;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .required {
        color: #ef4444;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        background: white;
        font-size: 14px;
        font-family: inherit;
    }

    .form-group textarea {
        resize: vertical;
    }

    .form-group select {
        cursor: pointer;
    }

    /* Responsive */
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

        .page-header {
            flex-direction: column;
            gap: 16px;
        }

        .available-team-card {
            flex-direction: column;
            gap: 16px;
        }

        .available-team-actions {
            align-items: stretch;
            width: 100%;
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
                    <div class="logo-title">ConcursITO</div>
                    <div class="logo-subtitle">Gestión de Competencias</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-item">
                <span class="nav-icon">☰</span>
                <span>Panel</span>
            </a>
            <a href="#" class="nav-item active">
                <span class="nav-icon">👥</span>
                <span>Mis equipos</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">📅</span>
                <span>Eventos</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">🏆</span>
                <span>Mis participaciones</span>
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
                <span>Mis equipos</span>
            </div>
            <div class="header-actions">
                <button class="icon-btn">
                    🔔
                    <span class="badge">5</span>
                </button>
                <div class="user-menu">
                    <img src="https://ui-avatars.com/api/?name=Ana+Perez&background=0f766e&color=fff" 
                         alt="Ana Perez Martinez" class="user-avatar">
                    <span>Ana Perez Martinez</span>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            <!-- Título y botón crear -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Gestión de Equipos</h1>
                    <p class="page-subtitle">Forma y administra equipos para participar en competencias</p>
                </div>
                <button class="btn btn-primary" onclick="openModal()">
                    + Crear equipo
                </button>
            </div>

            <!-- Mi equipo actual -->
            <div class="section">
                <div class="section-header">
                    <h2>Mi equipo actual</h2>
                    <button class="btn-icon">⚙️ Configurar</button>
                </div>

                <div class="team-card">
                    <div class="team-header">
                        <div>
                            <h3 class="team-name">
                                Los Programadores Alpha
                                <span class="badge-success">Activo</span>
                            </h3>
                            <p class="team-description">Equipo enfocado en desarrollo web y soluciones innovadoras</p>
                            <div class="team-meta">
                                <span>Creado: 14/2/2024</span>
                                <span>Integrantes: 3 / 5</span>
                            </div>
                        </div>
                    </div>

                    <div class="team-section">
                        <h4 class="team-section-title">Integrantes del equipo</h4>
                        
                        <div class="member-card">
                            <div class="member-info">
                                <div class="member-avatar">AP</div>
                                <div>
                                    <div class="member-name">
                                        Ana Pérez
                                        <span class="member-badge">👑</span>
                                    </div>
                                    <div class="member-email">ana.perez@universidad.edu</div>
                                </div>
                            </div>
                            <div class="member-role">Líder</div>
                        </div>

                        <div class="member-card">
                            <div class="member-info">
                                <div class="member-avatar">CR</div>
                                <div>
                                    <div class="member-name">Carlos Ruiz</div>
                                    <div class="member-email">carlos.ruiz@universidad.edu</div>
                                </div>
                            </div>
                            <div class="member-role">Desarrollador</div>
                        </div>

                        <div class="member-card">
                            <div class="member-info">
                                <div class="member-avatar">ML</div>
                                <div>
                                    <div class="member-name">María López</div>
                                    <div class="member-email">maria.lopez@universidad.edu</div>
                                </div>
                            </div>
                            <div class="member-role">Diseñadora</div>
                        </div>
                    </div>

                    <div class="team-section">
                        <h4 class="team-section-title">Eventos activos</h4>
                        <div class="event-item">Hackathon Universitaria 2025</div>
                    </div>
                </div>
            </div>

            <!-- Equipos disponibles -->
            <div class="section">
                <h2 class="section-title">Equipos Disponibles</h2>
                
                <div class="search-box">
                    <input type="text" placeholder="Buscar equipos por nombre, descripción o tecnologías...">
                </div>

                <div class="available-team-card">
                    <div class="available-team-info">
                        <h3 class="available-team-name">Guerreros del código</h3>
                        <p class="available-team-description">Especialistas en algoritmos y estructuras de datos.</p>
                        <div class="available-team-meta">
                            <span>📋 Líder: Pedro González</span>
                            <span>👥 3 / 5 integrantes</span>
                            <span>📅 Creado: 2/8/2024</span>
                        </div>
                        <div class="team-tags">
                            <span class="tag">Algoritmos</span>
                            <span class="tag">C++</span>
                            <span class="tag">Python</span>
                        </div>
                    </div>
                    <div class="available-team-actions">
                        <span class="visibility-badge">Público</span>
                        <button class="btn btn-primary">⭐ Solicitar Unión</button>
                    </div>
                </div>

                <div class="available-team-card">
                    <div class="available-team-info">
                        <h3 class="available-team-name">Maestros de pila completa</h3>
                        <p class="available-team-description">Desarrollo fullstack con tecnologías modernas</p>
                        <div class="available-team-meta">
                            <span>📋 Líder: Laura Martínez</span>
                            <span>👥 4 / 5 integrante</span>
                            <span>📅 Creado: 19/2/2024</span>
                        </div>
                        <div class="team-tags">
                            <span class="tag">React</span>
                            <span class="tag">Node.js</span>
                            <span class="tag">MongoDB</span>
                        </div>
                    </div>
                    <div class="available-team-actions">
                        <span class="visibility-badge">Público</span>
                        <button class="btn btn-primary">⭐ Solicitar Unión</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Crear Equipo -->
<div id="createTeamModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Crear Nuevo Equipo</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form>
            <div class="modal-body">
                <div class="form-group">
                    <label><span class="required">*</span> Nombre del equipo</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label><span class="required">*</span> Visibilidad</label>
                    <select name="visibilidad" required>
                        <option value="Público">Público</option>
                        <option value="Privado">Privado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear equipo</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('createTeamModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('createTeamModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('createTeamModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
@endsection