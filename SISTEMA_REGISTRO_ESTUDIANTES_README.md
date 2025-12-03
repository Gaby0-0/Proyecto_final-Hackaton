# Sistema de Registro Extendido para Estudiantes

## Resumen General

Se ha implementado un sistema completo de registro en 3 pasos para estudiantes, que incluye:
1. Registro básico (email, password, nombre)
2. Datos específicos del estudiante (número de control, carrera, etc.)
3. Gestión de equipos (crear o unirse con código)

Además, se ha creado un panel completo para estudiantes con menú de navegación y sistema de equipos funcional.

---

## Características Implementadas

### 1. Registro en 3 Pasos

#### Paso 1: Registro Básico
- Nombre completo
- Email (único)
- Contraseña (mínimo 6 caracteres con confirmación)
- Ruta: `/registro`

#### Paso 2: Datos de Estudiante
- **Campos obligatorios:**
  - Número de Control (único)
  - Carrera (selección entre opciones)
- **Campos opcionales:**
  - Semestre
  - Teléfono
  - Fecha de nacimiento
  - Dirección
- Indicador de progreso visual (3 pasos)
- Ruta: `/registro/datos-estudiante`

#### Paso 3: Gestión de Equipos
- **Opción A: Unirse a Equipo Existente**
  - Ingresar código de 8 caracteres
  - Validación automática del código
  - Verificación de espacios disponibles

- **Opción B: Crear Nuevo Equipo**
  - Nombre del equipo (único)
  - Descripción (opcional)
  - Máximo de integrantes (1-10)
  - El creador se convierte automáticamente en líder
  - Se genera código único automáticamente

- Opción de omitir (puede hacerse después)
- Ruta: `/registro/equipos`

### 2. Sistema de Equipos Mejorado

#### Códigos Únicos
- Cada equipo tiene un código de 8 caracteres alfanuméricos
- Generación automática al crear equipo
- Código único y visible para compartir

#### Gestión de Integrantes
- Mínimo: 1 integrante (el líder)
- Máximo: Configurable por equipo (1-10 para estudiantes, 1-20 para admin)
- Validación automática de capacidad

#### Roles de Equipo
- **Líder:** Creador del equipo, puede eliminarlo
- **Miembro:** Puede unirse y salir libremente

### 3. Panel de Estudiantes

#### Layout Completo
- Sidebar con menú de navegación
- Navbar superior con usuario y notificaciones
- Diseño responsivo (móvil y desktop)
- Colores distintivos (azul) vs admin (gris)

#### Menú de Navegación
- Dashboard
- Mis Equipos
- Proyectos (preparado)
- Eventos (preparado)
- Evaluaciones (preparado)
- Constancias (preparado)
- Mi Perfil
- Cerrar Sesión

#### Dashboard Mejorado
- Saludo personalizado con datos del estudiante
- Estadísticas en tiempo real:
  - Total de equipos
  - Equipos donde es líder
  - Eventos (preparado)
  - Evaluaciones (preparado)
- Visualización de equipos con:
  - Nombre
  - Rol (Líder/Miembro)
  - Código del equipo
  - Número de integrantes
- Call-to-action si no tiene equipos
- Guía rápida de uso
- Información del perfil

---

## Base de Datos

### Nuevas Tablas

#### `datos_estudiante`
```sql
- id
- user_id (FK a users)
- numero_control (único)
- carrera
- semestre (nullable)
- telefono (nullable)
- fecha_nacimiento (nullable)
- direccion (nullable)
- datos_completos (boolean)
- timestamps
```

#### `configuracion_global`
```sql
- id
- clave (único)
- valor
- descripcion (nullable)
- timestamps

Valores por defecto:
- max_equipos_por_estudiante: 3
- min_integrantes_equipo: 1
```

### Tablas Modificadas

#### `equipos`
Columnas agregadas:
- `codigo` (string 8 caracteres, único)
- `descripcion` (text, nullable)
- `max_integrantes` (integer, default 5)
- `activo` (boolean, default true)

---

## Modelos

### Nuevos Modelos

#### `DatosEstudiante`
- Relación con `User`
- Métodos helper para datos completos
- Validaciones de campos únicos

#### `ConfiguracionGlobal`
- Métodos estáticos:
  - `obtener($clave, $default)`: Obtener valor de configuración
  - `establecer($clave, $valor, $descripcion)`: Guardar configuración

### Modelos Actualizados

#### `User`
- Relación `datosEstudiante()`
- Relación `equipos()`
- Relación `equiposComoLider()`

#### `Equipo`
- Método `generarCodigoUnico()`: Genera código aleatorio único
- Método `estaLleno()`: Verifica si alcanzó capacidad máxima
- Método `puedeUnirse($user)`: Verifica si usuario puede unirse
- Boot method para asignar código automáticamente

---

## Controladores

### `RegistroController` (Actualizado)

Nuevos métodos:
1. `mostrarDatosEstudiante()`: Muestra formulario de datos
2. `guardarDatosEstudiante()`: Guarda datos del estudiante
3. `mostrarEquipos()`: Muestra opciones de equipo
4. `unirseEquipo()`: Une al estudiante a un equipo existente
5. `crearEquipoRegistro()`: Crea nuevo equipo desde registro

### `Estudiante\EquipoController` (Ya existía)
Controlador completo para gestión de equipos por estudiantes

---

## Rutas

### Rutas del Proceso de Registro
```php
// Requieren autenticación
GET  /registro/datos-estudiante
POST /registro/datos-estudiante
GET  /registro/equipos
POST /registro/equipos/unirse
POST /registro/equipos/crear
```

### Rutas de Estudiantes
```php
// Requieren autenticación y rol estudiante
GET    /dashboard
GET    /dashboard/equipos
GET    /dashboard/equipos/crear
POST   /dashboard/equipos
GET    /dashboard/equipos/{equipo}
POST   /dashboard/equipos/{equipo}/unirse
DELETE /dashboard/equipos/{equipo}/salir
DELETE /dashboard/equipos/{equipo}
```

---

## Vistas

### Proceso de Registro

1. **`auth/registro/datos-estudiante.blade.php`**
   - Formulario completo de datos
   - Validación en frontend
   - Indicador de progreso visual
   - Diseño moderno con Tailwind CSS

2. **`auth/registro/equipos.blade.php`**
   - Selector de opción (unirse/crear)
   - Formulario condicional con Alpine.js
   - Información contextual
   - Opción de omitir

### Layout de Estudiantes

1. **`layouts/estudiante.blade.php`**
   - Estructura completa con sidebar
   - Sistema de alertas
   - Navbar responsivo
   - Integración con Alpine.js

2. **`partials/estudiante/sidebar.blade.php`**
   - Menú de navegación completo
   - Iconos de Font Awesome
   - Indicador de página activa
   - Diseño con gradiente azul

3. **`partials/estudiante/navbar.blade.php`**
   - Información de usuario
   - Notificaciones (preparado)
   - Menú dropdown de perfil
   - Botón de logout

4. **`estudiante/dashboard/index.blade.php`**
   - Dashboard completo
   - Estadísticas en tiempo real
   - Visualización de equipos
   - Guía de uso
   - Información del perfil

### Vistas de Equipos (Ya existían, actualizadas con nuevo layout)

1. **`estudiante/equipos/index.blade.php`**
   - Lista de equipos del usuario
   - Equipos disponibles para unirse
   - Filtros y búsqueda

2. **`estudiante/equipos/create.blade.php`**
   - Formulario de creación
   - Validaciones

3. **`estudiante/equipos/show.blade.php`**
   - Detalles del equipo
   - Lista de miembros
   - Código para compartir

---

## Flujo de Usuario

### Registro Completo

1. **Usuario visita `/registro`**
   - Completa datos básicos
   - Crea cuenta

2. **Sistema redirige a `/registro/datos-estudiante`**
   - Usuario completa datos específicos
   - Se guardan en tabla `datos_estudiante`

3. **Sistema redirige a `/registro/equipos`**
   - **Opción A:** Ingresa código de equipo existente
     - Sistema valida código
     - Verifica espacios disponibles
     - Une al usuario como miembro
   - **Opción B:** Crea nuevo equipo
     - Define nombre y configuración
     - Sistema genera código único
     - Usuario se convierte en líder
   - **Opción C:** Omite y va al dashboard

4. **Acceso al Dashboard**
   - Ve sus estadísticas
   - Puede gestionar equipos
   - Accede a todas las funcionalidades

### Gestión de Equipos

1. **Crear Equipo:**
   - Completa formulario
   - Sistema genera código automático
   - Se convierte en líder

2. **Unirse a Equipo:**
   - Obtiene código del líder
   - Ingresa en formulario
   - Sistema valida y une

3. **Gestionar Equipo:**
   - Ver miembros
   - Ver código
   - Salir (si es miembro)
   - Eliminar (si es líder)

---

## Validaciones Implementadas

### Registro
- Email único
- Password mínimo 6 caracteres
- Confirmación de password
- Número de control único
- Carrera requerida

### Equipos
- Nombre único de equipo
- Código único de equipo (8 caracteres)
- Máximo de integrantes (1-10 estudiantes, 1-20 admin)
- Usuario no puede estar dos veces en mismo equipo
- Validación de espacios disponibles
- Solo líder puede eliminar equipo
- Líder no puede salir si hay otros miembros

---

## Características de Seguridad

- Middleware de autenticación en todas las rutas protegidas
- Middleware de rol (`estudiante`) en panel de estudiantes
- Validación de permisos en controladores
- Tokens CSRF en todos los formularios
- Passwords hasheados con bcrypt
- Sanitización de inputs

---

## Configuración

### Variables de Entorno
No requiere cambios adicionales en `.env`

### Configuración Global
Administradores pueden modificar:
- Máximo de equipos por estudiante
- Mínimo de integrantes por equipo

Modificar en tabla `configuracion_global` o usar modelo:
```php
ConfiguracionGlobal::establecer('max_equipos_por_estudiante', 5);
```

---

## Próximos Pasos Sugeridos

1. **Sistema de Proyectos**
   - Vincular proyectos a equipos
   - Subida de archivos
   - Gestión de entregas

2. **Sistema de Eventos**
   - Inscripción de equipos a eventos
   - Fechas y plazos
   - Notificaciones

3. **Sistema de Evaluaciones**
   - Evaluación por jueces
   - Retroalimentación
   - Puntuaciones

4. **Notificaciones**
   - Notificaciones en tiempo real
   - Emails de confirmación
   - Recordatorios

5. **Perfil de Usuario**
   - Editar información personal
   - Cambiar contraseña
   - Foto de perfil

6. **Constancias**
   - Generación automática
   - Descarga en PDF
   - Verificación con código

---

## Archivos Creados/Modificados

### Migraciones
- `2025_12_03_182002_create_datos_estudiante_table.php`
- `2025_12_03_182003_create_configuracion_global_table.php`
- `2025_12_03_180055_add_columns_to_equipos_table.php`

### Modelos
- `app/Models/DatosEstudiante.php` (nuevo)
- `app/Models/ConfiguracionGlobal.php` (nuevo)
- `app/Models/User.php` (actualizado)
- `app/Models/Equipo.php` (actualizado)

### Controladores
- `app/Http/Controllers/Auth/RegistroController.php` (actualizado)
- `app/Http/Controllers/Estudiante/EquipoController.php` (ya existía)

### Vistas - Layouts
- `resources/views/layouts/estudiante.blade.php` (nuevo)
- `resources/views/partials/estudiante/sidebar.blade.php` (nuevo)
- `resources/views/partials/estudiante/navbar.blade.php` (nuevo)

### Vistas - Registro
- `resources/views/auth/registro/datos-estudiante.blade.php` (nuevo)
- `resources/views/auth/registro/equipos.blade.php` (nuevo)

### Vistas - Dashboard
- `resources/views/estudiante/dashboard/index.blade.php` (actualizado)

### Rutas
- `routes/web.php` (actualizado)

---

## Tecnologías Utilizadas

- **Backend:** Laravel 10+
- **Frontend:** Tailwind CSS 3+
- **JavaScript:** Alpine.js 3+
- **Iconos:** Font Awesome 6+
- **Base de Datos:** MySQL

---

## Notas Técnicas

1. Los códigos de equipo se generan automáticamente al crear un equipo
2. Los códigos existentes fueron retroactivamente asignados a equipos creados antes
3. El sistema valida automáticamente capacidades de equipos
4. Los roles de equipo se manejan en la tabla pivote `equipo_user`
5. La configuración global permite ajustar límites sin modificar código
6. El layout de estudiantes es completamente independiente del de administradores
7. Todas las vistas usan el mismo sistema de componentes de Tailwind CSS

---

## Pruebas Recomendadas

1. Registrar nuevo estudiante completo (3 pasos)
2. Crear equipo y verificar código generado
3. Unirse a equipo con código
4. Intentar unirse a equipo lleno (debe fallar)
5. Verificar límites de integrantes
6. Líder intenta salir con miembros (debe fallar)
7. Miembro sale del equipo (debe funcionar)
8. Ver dashboard con y sin equipos
9. Verificar menú de navegación
10. Probar responsividad en móvil
