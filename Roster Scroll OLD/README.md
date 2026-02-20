# Plugin Roster Scroll - WordPress/Elementor

Plugin dinámico de WordPress que muestra talentos con efecto parallax scroll, completamente personalizable desde el editor de Elementor.

## 🎯 Características

- ✅ **Dinámico**: Muestra contenido desde el custom post type "Talento"
- ✅ **Categoría Invisible**: Usa la taxonomía "categoria_talento" con categoría invisible "talentos"
- ✅ **Fondo Transparente**: Fondo configurable, transparente por defecto
- ✅ **Totalmente Personalizable**: Todos los estilos editables desde Elementor:
  - Tipografía (familia, tamaño, peso, espaciado)
  - Colores (texto, etiquetas, fondos)
  - Tamaños de imagen
  - Bordes y efectos
- ✅ **Efecto Parallax**: Scroll horizontal del texto + movimiento vertical de imágenes
- ✅ **Responsive**: Adaptado para móvil, tablet y escritorio

## 📦 Instalación

### Opción 1: Instalación Manual
1. Copia la carpeta `Roster Scroll` completa a `wp-content/plugins/`
2. Ve a WordPress Admin → Plugins
3. Activa "Plugin Roster Scroll"

### Opción 2: Instalación por ZIP
1. Comprime la carpeta `Roster Scroll` en un archivo .zip
2. Ve a WordPress Admin → Plugins → Añadir nuevo → Subir plugin
3. Sube el archivo .zip y activa el plugin

## 🚀 Uso

### 1. Crear Talentos

1. En WordPress Admin, ve a **Talentos** → **Añadir nuevo**
2. Agrega:
   - **Título**: Nombre del talento
   - **Imagen destacada**: Foto del talento (recomendado: 400x600px, ratio 3:4)
3. En **Categorías de Talento**, selecciona **"Talentos"**
4. Publica el post

### 2. Agregar Widget en Elementor

1. Edita una página con Elementor
2. Busca el widget **"Roster Scroll Parallax"** en el panel izquierdo
3. Arrastra el widget a tu página
4. El widget mostrará automáticamente los talentos de la categoría "Talentos"

### 3. Personalizar el Widget

#### Pestaña Contenido
- **Cantidad de Talentos**: Cuántos talentos mostrar (1-20)
- **Texto Marquesina**: Texto grande que se mueve horizontalmente
- **Altura de Scroll**: Altura del contenedor en viewport height (200-800vh)

#### Pestaña Estilo → Fondo
- **Color de Fondo**: Color del contenedor (transparente por defecto)

#### Pestaña Estilo → Texto Marquesina
- **Tipografía**: Familia, tamaño, peso, estilo, decoración, espaciado
- **Color**: Color del texto marquesina
- **Modo de Mezcla**: Efecto de mezcla (difference, multiply, screen, etc.)

#### Pestaña Estilo → Imágenes
- **Ancho de Imagen**: Tamaño de las tarjetas (responsive)
- **Radio del Borde**: Redondeo de esquinas
- **Escala de Grises**: Activar/desactivar filtro blanco y negro

#### Pestaña Estilo → Etiquetas (Badges)
- **Tipografía**: Estilo del texto de las etiquetas
- **Color de Texto**: Color del texto de la etiqueta
- **Color de Fondo**: Color de fondo de la etiqueta
- **Padding**: Espaciado interno de la etiqueta

## 🎨 Cómo Funciona el Efecto Parallax

El widget crea un contenedor de altura extendida (400vh por defecto = 4 veces la altura de la pantalla). Mientras el usuario hace scroll:

1. **Texto Marquesina**: Se mueve horizontalmente de derecha a izquierda
2. **Imágenes**: Se mueven verticalmente de abajo hacia arriba a diferentes velocidades
3. **Efecto Sticky**: El contenido visual permanece fijo mientras el scroll avanza

Las posiciones y velocidades de las imágenes están predefinidas para crear un efecto visual atractivo.

## 📁 Estructura de Archivos

```
Roster Scroll/
├── plugin-roster-scroll.php          # Archivo principal del plugin
├── widgets/
│   └── roster-scroll-widget.php      # Widget de Elementor
└── README.md                          # Este archivo
```

## 🔧 Requisitos

- WordPress 5.0 o superior
- Elementor 3.0 o superior
- PHP 7.0 o superior

## 💡 Consejos

1. **Imágenes**: Usa imágenes con ratio 3:4 (ej: 400x600px) para mejor resultado
2. **Cantidad**: 6 talentos es la cantidad óptima para el efecto visual
3. **Altura de Scroll**: 400vh es un buen balance entre efecto y usabilidad
4. **Categoría Invisible**: La categoría "talentos" no aparece en el frontend, solo en admin

## 🐛 Solución de Problemas

**No aparecen talentos:**
- Verifica que los posts estén publicados
- Asegúrate de que tengan la categoría "Talentos" asignada
- Revisa que el custom post type "talento" esté registrado

**El efecto parallax no funciona:**
- Verifica que JavaScript esté habilitado en el navegador
- Comprueba que no haya conflictos con otros plugins
- Revisa la consola del navegador para errores

**Las imágenes no se ven:**
- Asegúrate de que cada talento tenga una imagen destacada
- Verifica los permisos de la carpeta de uploads

## 📝 Notas Técnicas

- El plugin usa JavaScript vanilla (sin dependencias)
- Los estilos son inline para evitar conflictos
- Cada instancia del widget tiene un ID único
- Compatible con el modo de edición de Elementor

## 🎓 Créditos

Desarrollado por Antigravity
Basado en el diseño de Roster Scroll con efecto parallax
