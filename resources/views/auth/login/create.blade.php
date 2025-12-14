<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="{{ asset('css/auth/create.css') }}">

</head>
<body>
    <div class="binary-background"></div>

    <div class="center-background">
            <svg class="wave-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill=" #1e4a5a" fill-opacity="1" d="M0,160L48,165.3C96,171,192,181,288,197.3C384,213,480,235,576,229.3C672,224,768,192,864,181.3C960,171,1056,181,1152,197.3C1248,213,1344,235,1392,245.3L1440,256L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
            </svg>
        </div>
    
    
    <div class="right-background"></div>
    
    <div class="container">
        
        <div class="registro-form">
            <h1>Registro</h1>
            <form id="registroForm" action="{{ route('registro.store') }}" method="POST">
                   @csrf
                <div class="form-group" >
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="name" placeholder="Tu nombre completo" required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="email" id="correo" name="email" placeholder="correo@ejemplo.com" required>
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="password" placeholder="Tu contraseña" required>
                </div>

                <div class="form-group">
                    <label for="confirmarContrasena">Confirmar Contraseña</label>
                    <input type="password" id="confirmarContrasena" name="password_confirmation" placeholder="Confirma tu contraseña" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terminos" name="terminos" required>
                    <label for="terminos">
                        Acepto los
                        <a href="#" onclick="return false;">términos y condiciones</a>
                    </label>
                </div>

                <button type="submit" class="btn-registrar">Registrarme</button>
            </form>
        </div>
    </div>

    <script>
        // Manejar envío del formulario
       document.getElementById('registroForm').addEventListener('submit', function(e) {

    const password = document.getElementById('contrasena').value;
    const confirmar = document.getElementById('confirmarContrasena').value;

    if (password !== confirmar) {
        e.preventDefault();
        alert('Las contraseñas no coinciden');
        return;
    }

    if (password.length < 6) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 6 caracteres');
        return;
    }
  

});
    </script>
</body>
</html>