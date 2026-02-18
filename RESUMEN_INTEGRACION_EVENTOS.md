# Resumen de Cambios - Integración de Plugins de Eventos

## 📁 Cambios Realizados

### 1. **Renombrado de Carpeta**
- ✅ `Nuevos eventos galeria` → `Galeria Eventos Pasados`

### 2. **Plugin: Carrusel de Eventos** 
**Archivo**: `includes/post-type.php`

#### Cambios:
- ✅ Agregado soporte para `custom-fields` en el CPT
- ✅ Agregado **Metabox de Fecha del Evento** (`_evento_fecha`)
- ✅ Funciones para guardar y mostrar la fecha

**Archivo**: `includes/carrusel-shortcode.php`

#### Cambios:
- ✅ Agregado filtro `meta_query` para mostrar **solo eventos futuros** (>= hoy)
- ✅ Ordenamiento automático por fecha del evento (ASC - próximos primero)

### 3. **Plugin: Galería Eventos Pasados**
**Archivo**: `galeria-eventos-parallax.php`

#### Cambios:
- ✅ Nombre actualizado: "Galería Eventos Pasados"
- ✅ Text domain actualizado: `galeria-eventos-pasados`
- ✅ Descripción actualizada

**Archivo**: `widgets/galeria-eventos-widget.php`

#### Cambios:
- ✅ Eliminado control de `post_type` (ahora usa solo 'eventos')
- ✅ Cambiado control de `categoria` por `ubicacion`
- ✅ Método `get_event_categories()` → `get_event_ubicaciones()`
- ✅ Query modificada para usar CPT `eventos`
- ✅ Agregado filtro `tax_query` para ubicaciones
- ✅ Agregado filtro `meta_query` para mostrar **solo eventos pasados** (< hoy)
- ✅ Ordenamiento automático por fecha del evento (DESC - recientes primero)

**Archivo**: `README.md`
- ✅ Documentación completamente actualizada

## 🎯 Funcionamiento Final

### Custom Post Type Compartido: `eventos`
- **Taxonomía**: `ubicacion_evento`
- **Meta Field**: `_evento_fecha` (campo de fecha obligatorio)

### División Automática de Eventos:

#### **Carrusel de Eventos** (Eventos Futuros)
```php
// Por defecto: Muestra eventos con fecha >= hoy
'meta_query' => [
    [
        'key' => '_evento_fecha',
        'value' => date('Y-m-d'),
        'compare' => '>=',
        'type' => 'DATE'
    ]
]
```
- Ordenamiento: ASC (próximos primero)
- Incluye eventos de hoy
- **Switch "Mostrar Todos"**: Desactiva el filtro de fecha

#### **Galería Eventos Pasados** (Eventos Pasados)
```php
// Por defecto: Muestra eventos con fecha < hoy
'meta_query' => [
    [
        'key' => '_evento_fecha',
        'value' => date('Y-m-d'),
        'compare' => '<',
        'type' => 'DATE'
    ]
]
```
- Ordenamiento: DESC (recientes primero)
- Solo eventos anteriores a hoy
- **Switch "Mostrar Todos"**: Desactiva el filtro de fecha

### 🔘 Switch "Mostrar Todos los Eventos"

Ambos widgets incluyen un **switch opcional** que permite mostrar todos los eventos sin importar la fecha:

- **Ubicación**: Sección "Selección de Eventos" en Elementor
- **Estado por defecto**: Desactivado
- **Cuando está activado**:
  - **Carrusel**: Muestra eventos pasados, presentes y futuros
  - **Galería**: Muestra eventos pasados, presentes y futuros
- **Uso recomendado**: Para eventos especiales que quieres mantener visibles permanentemente

## 📝 Flujo de Trabajo para el Usuario

1. **Crear un Evento**
   - Ir a: WordPress Admin → Eventos → Agregar Nuevo

2. **Configurar el Evento**
   - Título del evento
   - Descripción
   - Imagen destacada (obligatoria para galería)
   - **Fecha del evento** (campo obligatorio en sidebar)
   - Ubicación (opcional)

3. **Publicar**
   - El evento aparecerá automáticamente:
     - En **Carrusel de Eventos** si la fecha es hoy o futura
     - En **Galería Eventos Pasados** si la fecha ya pasó

## ⚠️ Notas Importantes

1. **Orden de Instalación**:
   - Primero: "Carrusel de Eventos" (crea el CPT)
   - Segundo: "Galería Eventos Pasados" (usa el CPT existente)

2. **Campo de Fecha Obligatorio**:
   - Sin fecha, el evento NO aparecerá en ningún widget
   - La fecha determina automáticamente dónde se muestra

3. **Actualización Automática**:
   - Los eventos cambian de widget automáticamente cuando pasa su fecha
   - No requiere intervención manual

## 🔄 Versiones

- **Carrusel de Eventos**: 1.0.3 → 1.0.4 (con filtro de fechas)
- **Galería Eventos Pasados**: 1.0.0 (nueva versión integrada)

## ✅ Testing Recomendado

1. Crear evento con fecha pasada → Debe aparecer en Galería
2. Crear evento con fecha de hoy → Debe aparecer en Carrusel
3. Crear evento con fecha futura → Debe aparecer en Carrusel
4. Verificar que eventos sin fecha no aparezcan
5. Probar filtros de ubicación en ambos widgets
