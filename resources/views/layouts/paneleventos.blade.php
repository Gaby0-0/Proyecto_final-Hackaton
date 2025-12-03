<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Eventos')</title>
</head>
<body>
    @yield('content')
</body>
</html>
```

## Verifica la estructura de carpetas:
```
tu-proyecto/
  resources/
    views/
      layouts/           ← ¿Existe esta carpeta?
        paneleventos.blade.php    ← Crea este archivo aquí
      eventos/
        panel.blade.php