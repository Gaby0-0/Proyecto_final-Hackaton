<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Informe General - ConcursITO</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 8px 10px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            border-left: 4px solid #3b82f6;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stats-row {
            display: table-row;
        }
        .stat-box {
            display: table-cell;
            width: 50%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 3px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-info { background-color: #dbeafe; color: #1e40af; }
        .badge-purple { background-color: #e9d5ff; color: #6b21a8; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Informe General - ConcursITO</h1>
        <p>Sistema de Gestión de Hackathons</p>
        <p>{{ $datos['fechaGeneracion'] }}</p>
    </div>

    <!-- Eventos -->
    <div class="section">
        <div class="section-title">EVENTOS</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Total de Eventos</div>
                    <div class="stat-value">{{ $datos['totalEventos'] }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Eventos Activos</div>
                    <div class="stat-value" style="color: #10b981;">{{ $datos['eventosActivos'] }}</div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Eventos Finalizados</div>
                    <div class="stat-value" style="color: #6b7280;">{{ $datos['eventosFinalizados'] }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Total de Equipos</div>
                    <div class="stat-value">{{ $datos['totalEquipos'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuarios -->
    <div class="section">
        <div class="section-title">USUARIOS</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Estudiantes</div>
                    <div class="stat-value" style="color: #3b82f6;">{{ $datos['totalEstudiantes'] }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Jueces</div>
                    <div class="stat-value" style="color: #8b5cf6;">{{ $datos['totalJueces'] }}</div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Administradores</div>
                    <div class="stat-value" style="color: #ef4444;">{{ $datos['totalAdmins'] }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Total de Usuarios</div>
                    <div class="stat-value">{{ $datos['totalEstudiantes'] + $datos['totalJueces'] + $datos['totalAdmins'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluaciones -->
    <div class="section">
        <div class="section-title">EVALUACIONES</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Total de Evaluaciones</div>
                    <div class="stat-value">{{ $datos['totalEvaluaciones'] }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Promedio General</div>
                    <div class="stat-value" style="color: #f59e0b;">{{ $datos['promedioEvaluacionesGeneral'] ?? 'N/A' }}/100</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Constancias -->
    <div class="section">
        <div class="section-title">CONSTANCIAS EMITIDAS</div>
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th style="text-align: right;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-warning">Ganadores</span></td>
                    <td style="text-align: right; font-weight: bold;">{{ $datos['constanciasGanadores'] }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-success">Participantes</span></td>
                    <td style="text-align: right; font-weight: bold;">{{ $datos['constanciasParticipantes'] }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-purple">Jueces</span></td>
                    <td style="text-align: right; font-weight: bold;">{{ $datos['constanciasJueces'] }}</td>
                </tr>
                <tr style="background-color: #f3f4f6;">
                    <td style="font-weight: bold;">TOTAL</td>
                    <td style="text-align: right; font-weight: bold; font-size: 12px;">{{ $datos['totalConstancias'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Informe generado por:</strong> {{ $datos['generadoPor'] }}</p>
        <p><strong>Fecha de generación:</strong> {{ $datos['fechaGeneracion'] }}</p>
        <p>© {{ date('Y') }} ConcursITO - Sistema de Gestión de Hackathons</p>
    </div>
</body>
</html>
