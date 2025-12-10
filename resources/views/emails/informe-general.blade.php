<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe General - ConcursITO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-title {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .stat-value {
            color: #1f2937;
            font-size: 28px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        .section-title {
            color: #374151;
            font-size: 18px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Informe General - ConcursITO</h1>
        <p>{{ $datos['fechaGeneracion'] }}</p>
    </div>

    <div class="content">
        <p>Estimado administrador,</p>
        <p>A continuación se presenta el informe general del sistema ConcursITO:</p>

        <div class="section-title">📅 Eventos</div>
        <div class="stat-card">
            <div class="stat-title">Total de Eventos</div>
            <div class="stat-value">{{ $datos['totalEventos'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Eventos Activos</div>
            <div class="stat-value" style="color: #10b981;">{{ $datos['eventosActivos'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Eventos Finalizados</div>
            <div class="stat-value" style="color: #6b7280;">{{ $datos['eventosFinalizados'] }}</div>
        </div>

        <div class="section-title">👥 Usuarios</div>
        <div class="stat-card">
            <div class="stat-title">Total de Estudiantes</div>
            <div class="stat-value" style="color: #3b82f6;">{{ $datos['totalEstudiantes'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Total de Jueces</div>
            <div class="stat-value" style="color: #8b5cf6;">{{ $datos['totalJueces'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Total de Administradores</div>
            <div class="stat-value" style="color: #ef4444;">{{ $datos['totalAdmins'] }}</div>
        </div>

        <div class="section-title">🏆 Equipos</div>
        <div class="stat-card">
            <div class="stat-title">Total de Equipos</div>
            <div class="stat-value">{{ $datos['totalEquipos'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Equipos Activos</div>
            <div class="stat-value" style="color: #10b981;">{{ $datos['equiposActivos'] }}</div>
        </div>

        <div class="section-title">⭐ Evaluaciones</div>
        <div class="stat-card">
            <div class="stat-title">Total de Evaluaciones</div>
            <div class="stat-value">{{ $datos['totalEvaluaciones'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Promedio General</div>
            <div class="stat-value" style="color: #f59e0b;">{{ $datos['promedioEvaluacionesGeneral'] ?? 'N/A' }} / 100</div>
        </div>

        <div class="section-title">📜 Constancias</div>
        <div class="stat-card">
            <div class="stat-title">Total de Constancias</div>
            <div class="stat-value">{{ $datos['totalConstancias'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Ganadores</div>
            <div class="stat-value" style="color: #fbbf24;">{{ $datos['constanciasGanadores'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Participantes</div>
            <div class="stat-value" style="color: #10b981;">{{ $datos['constanciasParticipantes'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Jueces</div>
            <div class="stat-value" style="color: #8b5cf6;">{{ $datos['constanciasJueces'] }}</div>
        </div>

        <div class="footer">
            <p><strong>Informe generado por:</strong> {{ $datos['generadoPor'] }}</p>
            <p><strong>Fecha de generación:</strong> {{ $datos['fechaGeneracion'] }}</p>
            <p>© {{ date('Y') }} ConcursITO - Sistema de Gestión de Hackathons</p>
        </div>
    </div>
</body>
</html>
