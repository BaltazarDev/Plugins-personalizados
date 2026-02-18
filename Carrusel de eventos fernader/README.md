# Carrusel de Eventos - Plugin de WordPress

Plugin de WordPress con widget de Elementor para mostrar un carrusel de eventos **próximos** con diseño moderno y animaciones Swiper.

## 🔗 Integración con Galería Eventos Pasados

Este plugin se integra perfectamente con el plugin **"Galería Eventos Pasados"**:
- Ambos usan el mismo **Custom Post Type: `eventos`**
- Ambos usan la misma **Taxonomía: `ubicacion_evento`**
- **Carrusel de Eventos**: Muestra eventos **futuros** (incluyendo hoy)
- **Galería Eventos Pasados**: Muestra eventos **pasados** (anteriores a hoy)

## 📋 Características

- ✅ Widget de Elementor personalizado
- ✅ Filtrado automático de eventos futuros
- ✅ Selección de eventos por ubicación
- ✅ Carrusel con Swiper.js
- ✅ Navegación personalizada
- ✅ Controles de personalización completos
- ✅ Responsive design
- ✅ Custom Post Type "Eventos"

## 🚀 Instalación

1. Sube la carpeta `Carrusel de eventos fernader` a `/wp-content/plugins/`
2. Activa el plugin desde el panel de WordPress
3. Asegúrate de tener Elementor instalado y activado

## 📦 Requisitos

- WordPress 5.0 o superior
- Elementor 3.0.0 o superior
- PHP 7.4 o superior

## 🎨 Uso

1. Crea eventos desde **Eventos** en el menú de WordPress
2. **Importante**: Asigna una **fecha** a cada evento
3. Asigna una **ubicación** (opcional)
4. Edita una página con Elementor
5. Busca el widget **"Carrusel de Eventos"**
6. Arrastra el widget a tu página
7. Configura las opciones

## ⚙️ Controles Disponibles

### Contenido
- **Ubicación**: Filtra eventos por ubicación específica
- **Límite de posts**: Cantidad de eventos a mostrar (-1 para todos)
- **Ordenar por**: Fecha, título, aleatorio, orden del menú
- **Orden**: Ascendente o descendente
- **Mostrar Todos los Eventos**: Switch para desactivar el filtro de fecha
  - **Desactivado** (por defecto): Solo muestra eventos futuros
  - **Activado**: Muestra todos los eventos sin importar la fecha

### Estilos - Imagen
- **Ancho de Imagen**: Control responsive del ancho de las tarjetas
- **Altura de Imagen**: Control responsive de la altura de las tarjetas

### Estilos - Ubicación
- **Tipografía**: Personaliza la fuente del texto de ubicación
- **Color de Texto**: Color del texto de ubicación
- **Color del Pin**: Color del ícono de ubicación

### Estilos - Título
- **Tipografía**: Personaliza la fuente del título
- **Color**: Color del título del evento

### Estilos - Botón RSVP
- **Tipografía**: Personaliza la fuente del botón
- **Estados**: Normal y Hover
  - Color de texto
  - Color de fondo
  - Color de borde
- **Ancho de Borde**: Grosor del borde del botón
- **Radio de Borde**: Redondeo de esquinas
- **Padding**: Espaciado interno del botón

## 📱 Responsive

El widget está optimizado para todos los dispositivos:
- 📱 Móviles
- 📱 Tablets
- 💻 Laptops
- 🖥️ Pantallas grandes

## 🎯 Características Técnicas

- **CPT**: `eventos` (compartido con Galería Eventos Pasados)
- **Taxonomía**: `ubicacion_evento`
- **Meta Field**: `_evento_fecha` (fecha del evento)
- **Filtro**: Por defecto muestra solo eventos con fecha >= hoy
- **Carrusel**: Swiper.js 8.4.5
- **Navegación**: Botones personalizados < >
- **Shortcode**: `[carrusel_eventos]`

## 📝 Notas Importantes

- **Debes asignar una fecha** a cada evento para que aparezca correctamente
- Los eventos sin fecha **no se mostrarán** (a menos que actives "Mostrar Todos")
- Las imágenes destacadas son obligatorias para el carrusel
- El botón RSVP es decorativo (puedes personalizarlo con JavaScript)

## 🔄 Flujo de Trabajo

1. **Crea un evento** en WordPress
2. **Asigna una fecha** al evento
3. **Asigna una imagen destacada**
4. **Asigna una ubicación** (opcional)
5. El evento aparecerá:
   - En el **Carrusel** si la fecha es hoy o futura
   - En la **Galería** si la fecha ya pasó

## 🔧 Shortcode Manual

Puedes usar el shortcode directamente en cualquier lugar:

```php
[carrusel_eventos ubicacion="ciudad-mexico" posts_per_page="6" orderby="date" order="ASC"]
```

### Parámetros del Shortcode:
- `ubicacion`: Slug de la ubicación (opcional)
- `posts_per_page`: Número de eventos (default: 6)
- `orderby`: date, title, rand, menu_order (default: date)
- `order`: ASC, DESC (default: DESC)
- `mostrar_todos`: yes, no (default: no)

## 👨‍💻 Autor

**BGDEVSOFT**
- Plugin para: Fernader

## 📄 Licencia

Este plugin es propiedad de Fernader y BGDEVSOFT.

## 🔄 Versión

**1.0.4** - Versión con integración al plugin Galería Eventos Pasados y switch de mostrar todos
