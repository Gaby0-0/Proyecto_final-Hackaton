# Sistema de Gestión de Equipos

## Resumen de la Implementación

Se ha implementado un sistema completo de gestión de equipos con las siguientes funcionalidades:

### Características Principales

#### Para Estudiantes:
1. **Crear equipos**: Los estudiantes pueden crear sus propios equipos y convertirse en líderes
2. **Unirse a equipos**: Los estudiantes pueden unirse a equipos existentes que tengan espacios disponibles
3. **Salir de equipos**: Los miembros pueden salir de equipos (excepto los líderes con otros miembros)
4. **Gestionar equipos**: Los líderes pueden eliminar sus equipos

#### Para Administradores:
1. **Gestión completa de equipos**: Crear, editar, ver y eliminar equipos
2. **Configurar máximo de integrantes**: Establecer el límite de miembros por equipo (2-20)
3. **Asignar miembros y líderes**: Gestionar la composición de cada equipo
4. **Ver estadísticas**: Visualizar información detallada de cada equipo

### Estructura de la Base de Datos

#### Tabla `equipos`:
- `id`: Identificador único
- `nombre`: Nombre del equipo (único)
- `descripcion`: Descripción del equipo (opcional)
- `proyecto_id`: Proyecto asociado (opcional)
- `max_integrantes`: Máximo de integrantes permitidos (default: 5)
- `activo`: Estado del equipo (default: true)
- `created_at`, `updated_at`: Timestamps

#### Tabla `equipo_user` (tabla pivote):
- `id`: Identificador único
- `equipo_id`: Referencia al equipo
- `user_id`: Referencia al usuario
- `rol_equipo`: Rol del miembro ('lider' o 'miembro')
- `created_at`, `updated_at`: Timestamps
- **Constraint único**: Un usuario solo puede estar una vez en cada equipo

### Archivos Creados y Modificados

#### Modelos:
- `app/Models/Equipo.php` - Actualizado con métodos helper:
  - `estaLleno()`: Verifica si el equipo alcanzó su capacidad máxima
  - `puedeUnirse($user)`: Verifica si un usuario puede unirse al equipo

#### Controladores:
- `app/Http/Controllers/Estudiante/EquipoController.php` - Nuevo controlador para estudiantes
  - `index()`: Lista equipos del usuario y equipos disponibles
  - `create()`: Formulario para crear equipo
  - `store()`: Guardar nuevo equipo
  - `show()`: Ver detalles de un equipo
  - `unirse()`: Unirse a un equipo
  - `salir()`: Salir de un equipo
  - `destroy()`: Eliminar equipo (solo líder)

- `app/Http/Controllers/Admin/EquipoController.php` - Actualizado
  - Agregado soporte para campo `max_integrantes` en store() y update()

#### Vistas para Estudiantes:
- `resources/views/estudiante/equipos/index.blade.php` - Lista de equipos
- `resources/views/estudiante/equipos/create.blade.php` - Crear equipo
- `resources/views/estudiante/equipos/show.blade.php` - Ver detalles del equipo

#### Vistas para Administradores:
- `resources/views/admin/equipos/create.blade.php` - Crear equipo
- `resources/views/admin/equipos/edit.blade.php` - Editar equipo
- `resources/views/admin/equipos/show.blade.php` - Ver detalles del equipo
- `resources/views/admin/equipos/index.blade.php` - Ya existía

#### Rutas:
- `routes/web.php` - Agregadas rutas para estudiantes:
  - `GET /dashboard/equipos` - Lista de equipos
  - `GET /dashboard/equipos/crear` - Formulario crear equipo
  - `POST /dashboard/equipos` - Guardar equipo
  - `GET /dashboard/equipos/{equipo}` - Ver detalles
  - `POST /dashboard/equipos/{equipo}/unirse` - Unirse a equipo
  - `DELETE /dashboard/equipos/{equipo}/salir` - Salir del equipo
  - `DELETE /dashboard/equipos/{equipo}` - Eliminar equipo

#### Migraciones:
- `database/migrations/2025_12_03_180055_add_columns_to_equipos_table.php` - Agrega columnas:
  - `descripcion`
  - `max_integrantes`
  - `activo`

### Flujo de Trabajo

#### Estudiante creando un equipo:
1. Va a `/dashboard/equipos`
2. Hace clic en "Crear Equipo"
3. Completa el formulario con:
   - Nombre del equipo
   - Descripción (opcional)
   - Máximo de integrantes (2-10)
   - Proyecto asociado (opcional)
4. Al crear el equipo, automáticamente se convierte en líder

#### Estudiante uniéndose a un equipo:
1. Va a `/dashboard/equipos`
2. En la sección "Equipos Disponibles", ve los equipos con espacios
3. Hace clic en "Unirse"
4. Se agrega como miembro del equipo

#### Administrador gestionando equipos:
1. Va a `/admin/equipos`
2. Puede:
   - Ver todos los equipos con filtros
   - Crear nuevos equipos
   - Editar equipos existentes
   - Cambiar el máximo de integrantes
   - Asignar/reasignar líderes y miembros
   - Ver estadísticas detalladas
   - Eliminar equipos

### Validaciones Implementadas

1. **Nombre único**: No pueden existir dos equipos con el mismo nombre
2. **Máximo de integrantes**:
   - Estudiantes: 2-10 integrantes
   - Administradores: 2-20 integrantes
3. **No duplicados**: Un usuario no puede estar dos veces en el mismo equipo
4. **Límite de capacidad**: No se puede unir a un equipo lleno
5. **Líder protegido**: El líder no puede salir si hay otros miembros
6. **Solo líder elimina**: Solo el líder puede eliminar su equipo

### Próximos Pasos Recomendados

1. **Transferencia de liderazgo**: Implementar funcionalidad para que el líder pueda transferir su rol a otro miembro
2. **Invitaciones**: Sistema de invitaciones para unirse a equipos privados
3. **Notificaciones**: Alertas cuando alguien se une o sale del equipo
4. **Chat de equipo**: Sistema de mensajería interna para cada equipo
5. **Límite de equipos por usuario**: Configurar cuántos equipos puede crear/unirse cada estudiante

### Notas Técnicas

- Todas las vistas utilizan el sistema de layouts existente (`layouts.estudiante` y `layouts.admin`)
- Se utilizan componentes blade como `x-admin.card` para mantener consistencia visual
- Los permisos están controlados mediante middleware (`auth`, `estudiante`)
- Se incluye validación tanto en el frontend como en el backend
- Las relaciones de base de datos están optimizadas con eager loading
