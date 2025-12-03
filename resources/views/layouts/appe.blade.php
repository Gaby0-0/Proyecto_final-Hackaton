<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eventos')</title>

    <style>
        /* Estilos generales */
        body {
            background: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 420px;
            margin: 40px auto;
        }

        /* Tarjeta modal */
        .card {
            background: white;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.15);
            position: relative;
        }

        /* Header */
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
        }

        /* Contenido */
        .modal-body {
            padding: 24px;
        }

        /* Secciones */
        .section {
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
        }

        /* Inputs */
        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 13px;
            color: #374151;
            margin-bottom: 6px;
            font-weight: 500;
        }

        label .required {
            color: #ef4444;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: white;
            font-size: 14px;
            box-sizing: border-box;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        /* Grids */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* Radio buttons */
        .radio-group {
            display: flex;
            gap: 16px;
            margin-top: 8px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .radio-option input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .radio-option label {
            margin: 0;
            font-weight: 400;
            cursor: pointer;
        }

        /* Footer */
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: #f9fafb;
            border-radius: 0 0 8px 8px;
        }

        /* Botones */
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-secondary {
            background: transparent;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #f3f4f6;
        }

        .btn-primary {
            background: #0f766e;
            color: white;
        }

        .btn-primary:hover {
            background: #0d9488;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <div class="modal-header">
                <h2 class="title">Crear evento</h2>
                <button class="close-btn">×</button>
            </div>

            <div class="modal-body">
                <form>
                    <!-- Información básica -->
                    <div class="section">
                        <div class="section-title">Información básica:</div>
                        
                        <div class="form-group">
                            <label><span class="required">*</span> Nombre:</label>
                            <input type="text" placeholder="">
                        </div>

                        <div class="form-group">
                            <label><span class="required">*</span> Proyecto:</label>
                            <select>
                                <option>Seleccionar Proyecto</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><span class="required">*</span> Descripción:</label>
                            <textarea placeholder=""></textarea>
                        </div>
                    </div>

                    <!-- Fechas y ubicación -->
                    <div class="section">
                        <div class="section-title">Fechas y ubicación:</div>
                        
                        <div class="grid-2">
                            <div class="form-group">
                                <label><span class="required">*</span> Inicio:</label>
                                <input type="date" placeholder="dd/mm/aaaa">
                            </div>
                            <div class="form-group">
                                <label><span class="required">*</span> Alerta:</label>
                                <input type="date" placeholder="dd/mm/aaaa">
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <input type="time" value="--:--:--">
                            </div>
                            <div class="form-group">
                                <input type="time" value="--:--:--">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><span class="required">*</span> Ubicación:</label>
                            <input type="text" placeholder="">
                        </div>

                        <div class="form-group">
                            <label><span class="required">*</span> Modalidad:</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" name="modalidad" id="presencial" checked>
                                    <label for="presencial">Presencial</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="modalidad" id="virtual">
                                    <label for="virtual">Virtual</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="modalidad" id="hibrida">
                                    <label for="hibrida">Híbrida</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración -->
                    <div class="section">
                        <div class="section-title">Configuración:</div>
                        
                        <div class="grid-2">
                            <div class="form-group">
                                <label><span class="required">*</span> Máximo de participantes:</label>
                                <input type="number" value="100">
                            </div>
                            <div class="form-group">
                                <label><span class="required">*</span> Estado:</label>
                                <select>
                                    <option>Programado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary">Cancelar</button>
                <button class="btn btn-primary">Guardar evento</button>
            </div>
        </div>
    </div>

</body>
</html>