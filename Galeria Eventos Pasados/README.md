# Galería Eventos Pasados - Plugin de WordPress

Plugin de WordPress con widget de Elementor para mostrar una galería de eventos **pasados** con efecto parallax y texto sticky.

## 🔗 Integración con Carrusel de Eventos

Este plugin se integra perfectamente con el plugin **"Carrusel de Eventos"**:
- Ambos usan el mismo **Custom Post Type: `eventos`**
- Ambos usan la misma **Taxonomía: `ubicacion_evento`**
- **Carrusel de Eventos**: Muestra eventos **futuros** (incluyendo hoy)
- **Galería Eventos Pasados**: Muestra eventos **pasados** (anteriores a hoy)

## 📋 Características

- ✅ Widget de Elementor personalizado
- ✅ Filtrado automático de eventos pasados
- ✅ Selección de eventos por ubicación
- ✅ Efecto parallax en 3 columnas
- ✅ Texto sticky responsive
- ✅ Controles de personalización completos
- ✅ Optimizado para pantallas de 13 pulgadas
- ✅ Animaciones GSAP suaves

## 🚀 Instalación

1. **Instala primero** el plugin "Carrusel de Eventos" (crea el CPT)
2. Sube la carpeta `Galeria Eventos Pasados` a `/wp-content/plugins/`
3. Activa el plugin desde el panel de WordPress
4. Asegúrate de tener Elementor instalado y activado

## 📦 Requisitos

- WordPress 5.0 o superior
- Elementor 3.0.0 o superior
- PHP 7.4 o superior
- **Plugin "Carrusel de Eventos"** instalado y activado

## 🎨 Uso

1. Crea eventos desde **Eventos** en el menú de WordPress
2. **Importante**: Asigna una **fecha** a cada evento
3. Edita una página con Elementor
4. Busca el widget **"Galería Eventos Parallax"**
5. Arrastra el widget a tu página
6. Configura las opciones

## ⚙️ Controles Disponibles

### Contenido
- **Título**: Texto principal (usa `<br>` para saltos de línea)
- **Subtítulo**: Texto descriptivo

### Selección de Eventos
- **Ubicación**: Filtra por una o más ubicaciones
- **Número de Eventos**: Cantidad de eventos a mostrar (1-50)
- **Ordenar por**: Fecha, título, aleatorio, orden del menú
- **Orden**: Ascendente o descendente
- **Mostrar Todos los Eventos**: Switch para desactivar el filtro de fecha
  - **Desactivado** (por defecto): Solo muestra eventos pasados
  - **Activado**: Muestra todos los eventos sin importar la fecha

### Estilos
- **Color del Título**: Personaliza el color del título
- **Color de la Línea**: Color de la línea divisoria
- **Color del Subtítulo**: Color del texto descriptivo
- **Color de Fondo**: Fondo de la sección

## 📱 Responsive

El widget está optimizado para:
- 📱 Móviles (< 768px)
- 📱 Tablets (768px - 1024px)
- 💻 Laptops pequeñas (1024px - 1280px) - Optimizado para MacBook Air 13"
- 🖥️ Laptops grandes (1280px - 1536px)
- 🖥️ Pantallas grandes (> 1536px)

## 🎯 Características Técnicas

- **CPT**: `eventos` (compartido con Carrusel de Eventos)
- **Taxonomía**: `ubicacion_evento`
- **Meta Field**: `_evento_fecha` (fecha del evento)
- **Filtro**: Muestra solo eventos con fecha < hoy
- **Fuentes**: Bodoni Moda (títulos) + Open Sans (subtítulos)
- **Animaciones**: GSAP 3.12.2 + ScrollTrigger
- **CSS Framework**: Tailwind CSS (CDN)
- **Lazy Loading**: Carga diferida de imágenes

## 📝 Notas Importantes

- **Debes asignar una fecha** a cada evento para que aparezca correctamente
- Los eventos sin fecha **no se mostrarán**
- Las imágenes se distribuyen automáticamente en 3 columnas
- La columna 3 solo se muestra en desktop
- El efecto parallax se ajusta según el dispositivo
- El texto sticky solo funciona en desktop

## 🔄 Flujo de Trabajo

1. **Crea un evento** en WordPress
2. **Asigna una fecha** al evento
3. **Asigna una imagen destacada**
4. **Asigna una ubicación** (opcional)
5. El evento aparecerá:
   - En el **Carrusel** si la fecha es hoy o futura
   - En la **Galería** si la fecha ya pasó

## 👨‍💻 Autor

**Baltazar Dev**
- Website: https://baltazarg.xyz
- Plugin para: Fernader

## 📄 Licencia

Este plugin es propiedad de Fernader y Baltazar Dev.

## 🔄 Versión

**1.0.0** - Versión inicial con integración al plugin Carrusel de Eventos

