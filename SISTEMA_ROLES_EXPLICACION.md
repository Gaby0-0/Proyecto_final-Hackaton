# 📋 Sistema de Roles - ConcursITO

## ✅ Usuarios de Prueba Creados

Ahora tienes 3 usuarios de prueba con diferentes roles:

| Rol | Email | Contraseña | Redirige a |
|-----|-------|------------|------------|
| **Administrador** | admin@concursito.com | 12345678 | `/admin` |
| **Juez** | juez@concursito.com | 12345678 | `/juez` |
| **Alumno** | alumno@concursito.com | 12345678 | `/dashboard` |

---

## 🔑 ¿Cómo Funciona el Sistema de Roles?

### 1. **Tabla `users` - Aquí se asigna el rol**

El rol de cada usuario se guarda **directamente en la tabla `users`** de la base de datos:

```sql
users
├── id
├── name
├── email
├── password
├── admin (0 o 1)           ← Campo legacy para compatibilidad
└── role (texto)            ← 'admin', 'juez', o 'estudiante'
```

### 2. **Campos Importantes**

- **`admin`**: Campo numérico (0 o 1)
  - `1` = Usuario administrador
  - `0` = Usuario regular

- **`role`**: Campo de texto (valores posibles)
  - `'admin'` = Administrador
  - `'juez'` = Juez evaluador
  - `'estudiante'` = Alumno participante

---

## 🛠️ ¿Dónde se Cambia el Tipo de Usuario?

### **Opción 1: Desde el Panel de Administración** ⭐ (Recomendado)

Cuando un administrador edita un usuario, puede cambiar su rol:

**Archivo:** `resources/views/admin/usuarios/edit.blade.php` (líneas 48-60)

```blade
<select name="role" required>
    <option value="admin" {{ $usuario->role == 'admin' ? 'selected' : '' }}>Administrador</option>
    <option value="juez" {{ $usuario->role == 'juez' ? 'selected' : '' }}>Juez</option>
    <option value="estudiante" {{ $usuario->role == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
</select>
```

**Controlador:** `app/Http/Controllers/Admin/UsuarioController.php` (línea 106)

```php
public function update(Request $request, User $usuario)
{
    $validated = $request->validate([
        'role' => 'required|in:admin,juez,estudiante'  // ← Valida el rol
    ]);

    // Si el rol es 'admin', también actualiza el campo 'admin'
    if ($validated['role'] === 'admin') {
        $validated['admin'] = 1;
    } else {
        $validated['admin'] = 0;
    }

    $usuario->update($validated);
}
```

### **Opción 2: Desde la Base de Datos** (MySQL/phpMyAdmin)

Puedes cambiar el rol directamente ejecutando SQL:

```sql
-- Cambiar usuario a administrador
UPDATE users SET role = 'admin', admin = 1 WHERE email = 'usuario@ejemplo.com';

-- Cambiar usuario a juez
UPDATE users SET role = 'juez', admin = 0 WHERE email = 'usuario@ejemplo.com';

-- Cambiar usuario a estudiante
UPDATE users SET role = 'estudiante', admin = 0 WHERE email = 'usuario@ejemplo.com';
```

### **Opción 3: Usando Tinker** (Artisan CLI)

```bash
php artisan tinker

# Buscar usuario por email
$user = User::where('email', 'usuario@ejemplo.com')->first();

# Cambiar a admin
$user->role = 'admin';
$user->admin = 1;
$user->save();

# Cambiar a juez
$user->role = 'juez';
$user->admin = 0;
$user->save();

# Cambiar a estudiante
$user->role = 'estudiante';
$user->admin = 0;
$user->save();
```

---

## 🔐 ¿Cómo Funciona la Protección por Roles?

### 1. **Middleware (Guardias de Ruta)**

Cada tipo de usuario tiene su middleware que verifica el acceso:

**AdminMiddleware** - `app/Http/Middleware/AdminMiddleware.php`
```php
if ($user->admin == 1 || $user->role === 'admin') {
    return $next($request);  // ✅ Permite acceso
}
abort(403);  // ❌ Bloquea acceso
```

**JuezMiddleware** - `app/Http/Middleware/JuezMiddleware.php`
```php
if ($user->role === 'juez') {
    return $next($request);  // ✅ Permite acceso
}
abort(403);  // ❌ Bloquea acceso
```

**EstudianteMiddleware** - `app/Http/Middleware/EstudianteMiddleware.php`
```php
if ($user->admin != 1 && $user->role !== 'juez') {
    return $next($request);  // ✅ Permite acceso
}
abort(403);  // ❌ Bloquea acceso
```

### 2. **Rutas Protegidas**

**Archivo:** `routes/web.php`

```php
// Rutas de Administrador (solo admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('usuarios', UsuarioController::class);
    // ...
});

// Rutas de Juez (solo jueces)
Route::middleware(['auth', 'juez'])->prefix('juez')->name('juez.')->group(function() {
    Route::get('/', [JuezDashboardController::class, 'index']);
});

// Rutas de Estudiante (solo estudiantes)
Route::middleware(['auth', 'estudiante'])->prefix('dashboard')->name('estudiante.')->group(function() {
    Route::get('/', [EstudianteDashboardController::class, 'index']);
});
```

### 3. **Redirección en Login**

**Archivo:** `app/Http/Controllers/Auth/LoginController.php` (líneas 42-48)

```php
// Después de autenticar al usuario
if ($user->admin == 1 || $user->role === 'admin') {
    return redirect()->intended('/admin');           // → Panel Admin
} elseif ($user->role === 'juez') {
    return redirect()->intended('/juez');            // → Panel Juez
} else {
    return redirect()->intended('/dashboard');       // → Panel Estudiante
}
```

---

## 📊 Vista General del Sistema

```
┌─────────────────────────────────────────────────────────┐
│                    TABLA: users                         │
├─────────────────────────────────────────────────────────┤
│  id │ name          │ email              │ admin │ role │
├─────┼───────────────┼────────────────────┼───────┼──────┤
│  3  │ Admin Usuario │ admin@concur...    │   1   │admin │
│  4  │ Juez Usuario  │ juez@concur...     │   0   │juez  │
│  5  │ Alumno Usuario│ alumno@concur...   │   0   │estu..│
└─────────────────────────────────────────────────────────┘
                           ↓
                    Al hacer LOGIN
                           ↓
        ┌──────────────────┼──────────────────┐
        ↓                  ↓                  ↓
   role=admin         role=juez        role=estudiante
        ↓                  ↓                  ↓
   /admin (✓)         /juez (✓)         /dashboard (✓)
   /juez (✗)          /admin (✗)        /admin (✗)
   /dashboard (✗)     /dashboard (✗)    /juez (✗)
```

---

## 🎯 Resumen Rápido

1. **¿Dónde se guarda el rol?** → En la tabla `users`, campo `role`
2. **¿Qué valores puede tener?** → `'admin'`, `'juez'`, o `'estudiante'`
3. **¿Dónde se cambia?** → Panel admin > Usuarios > Editar
4. **¿Quién puede cambiar roles?** → Solo administradores
5. **¿Hay otra tabla de roles?** → No, todo está en la tabla `users`

---

## 🚀 Probando el Sistema

1. **Cierra sesión** si estás logueado
2. **Inicia sesión** con cada usuario:
   - `admin@concursito.com` / `12345678` → Verás el panel de admin
   - `juez@concursito.com` / `12345678` → Verás el panel de juez
   - `alumno@concursito.com` / `12345678` → Verás el panel de estudiante
3. **Intenta acceder** a rutas prohibidas (recibirás error 403)

---

## 📝 Notas Importantes

- El campo `admin` (0/1) es legacy pero se mantiene por compatibilidad
- El campo `role` es el que realmente controla el acceso
- Para usuarios admin, ambos campos deben estar configurados: `admin=1` y `role='admin'`
- Los middlewares verifican ambos campos para mayor seguridad
