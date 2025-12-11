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
            line-height: 1.6;
            color: #1A1A1A;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #FF6B35 0%, #ea580c 100%);
            color: white;
            padding: 25px 20px;
            text-align: center;
            margin-bottom: 25px;
            border-radius: 8px;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: bold;
            letter-spacing: -0.5px;
        }
        .header p {
            font-size: 12px;
            opacity: 0.95;
            margin-bottom: 3px;
        }
        .header .subtitle {
            font-size: 11px;
            opacity: 0.85;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background: linear-gradient(135deg, #1A1A1A 0%, #343a40 100%);
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 12px;
            border-left: 5px solid #FF6B35;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
            border-spacing: 8px;
        }
        .stats-row {
            display: table-row;
        }
        .stat-box {
            display: table-cell;
            width: 50%;
            padding: 15px;
            border: 2px solid #f0f0f0;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border-radius: 6px;
        }
        .stat-box-primary {
            border-color: #FF6B35;
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
        }
        .stat-box-secondary {
            border-color: #0EA5E9;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        .stat-label {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1A1A1A;
            line-height: 1.2;
        }
        .stat-value-primary {
            color: #FF6B35;
        }
        .stat-value-secondary {
            color: #0EA5E9;
        }
        .footer {
            margin-top: 35px;
            padding-top: 15px;
            border-top: 3px solid #FF6B35;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
        .footer p {
            margin-bottom: 4px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
            border-radius: 6px;
            overflow: hidden;
            border: 2px solid #f0f0f0;
        }
        th, td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        th {
            background: linear-gradient(135deg, #1A1A1A 0%, #343a40 100%);
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            font-size: 11px;
            background-color: #ffffff;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-warning {
            background-color: #fed7aa;
            color: #c2410c;
            border: 1px solid #fdba74;
        }
        .badge-success {
            background-color: #bae6fd;
            color: #075985;
            border: 1px solid #7dd3fc;
        }
        .badge-purple {
            background-color: #e9d5ff;
            color: #6b21a8;
            border: 1px solid #d8b4fe;
        }
        .total-row {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            font-weight: bold;
            border-top: 2px solid #FF6B35;
        }
        .divider {
            height: 3px;
            background: linear-gradient(90deg, #FF6B35 0%, #0EA5E9 100%);
            margin: 20px 0;
            border-radius: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Informe General - ConcursITO</h1>
        <p>Sistema de Gestión de Hackathons</p>
        <p class="subtitle">{{ $datos['fechaGeneracion'] }}</p>
    </div>

    <!-- Eventos -->
    <div class="section">
        <div class="section-title">📅 EVENTOS</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box stat-box-primary">
                    <div class="stat-label">Total de Eventos</div>
                    <div class="stat-value stat-value-primary">{{ $datos['totalEventos'] }}</div>
                </div>
                <div class="stat-box stat-box-secondary">
                    <div class="stat-label">Eventos Activos</div>
                    <div class="stat-value stat-value-secondary">{{ $datos['eventosActivos'] }}</div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Eventos Finalizados</div>
                    <div class="stat-value">{{ $datos['eventosFinalizados'] }}</div>
                </div>
                <div class="stat-box stat-box-primary">
                    <div class="stat-label">Total de Equipos</div>
                    <div class="stat-value stat-value-primary">{{ $datos['totalEquipos'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Usuarios -->
    <div class="section">
        <div class="section-title">👥 USUARIOS</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box stat-box-secondary">
                    <div class="stat-label">Estudiantes</div>
                    <div class="stat-value stat-value-secondary">{{ $datos['totalEstudiantes'] }}</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Jueces</div>
                    <div class="stat-value">{{ $datos['totalJueces'] }}</div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Administradores</div>
                    <div class="stat-value">{{ $datos['totalAdmins'] }}</div>
                </div>
                <div class="stat-box stat-box-primary">
                    <div class="stat-label">Total de Usuarios</div>
                    <div class="stat-value stat-value-primary">{{ $datos['totalEstudiantes'] + $datos['totalJueces'] + $datos['totalAdmins'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Evaluaciones -->
    <div class="section">
        <div class="section-title">⭐ EVALUACIONES</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box stat-box-primary">
                    <div class="stat-label">Total de Evaluaciones</div>
                    <div class="stat-value stat-value-primary">{{ $datos['totalEvaluaciones'] }}</div>
                </div>
                <div class="stat-box stat-box-secondary">
                    <div class="stat-label">Promedio General</div>
                    <div class="stat-value stat-value-secondary">{{ $datos['promedioEvaluacionesGeneral'] ?? 'N/A' }}/100</div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Constancias -->
    <div class="section">
        <div class="section-title">🏆 CONSTANCIAS EMITIDAS</div>
        <table>
            <thead>
                <tr>
                    <th>Tipo de Constancia</th>
                    <th style="text-align: right;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-warning">🥇 Ganadores</span></td>
                    <td style="text-align: right; font-weight: bold; color: #c2410c;">{{ $datos['constanciasGanadores'] }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-success">👥 Participantes</span></td>
                    <td style="text-align: right; font-weight: bold; color: #075985;">{{ $datos['constanciasParticipantes'] }}</td>
                </tr>
                <tr>
                    <td><span class="badge badge-purple">⚖️ Jueces</span></td>
                    <td style="text-align: right; font-weight: bold; color: #6b21a8;">{{ $datos['constanciasJueces'] }}</td>
                </tr>
                <tr class="total-row">
                    <td style="font-weight: bold; color: #FF6B35;">TOTAL</td>
                    <td style="text-align: right; font-weight: bold; font-size: 13px; color: #FF6B35;">{{ $datos['totalConstancias'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Informe generado por:</strong> {{ $datos['generadoPor'] }}</p>
        <p><strong>Fecha de generación:</strong> {{ $datos['fechaGeneracion'] }}</p>
        <p style="margin-top: 8px; color: #FF6B35; font-weight: bold;">© {{ date('Y') }} ConcursITO - Sistema de Gestión de Hackathons</p>
    </div>
</body>
</html>
