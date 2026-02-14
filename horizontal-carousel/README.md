# Horizontal Carousel - Plugin de WordPress v1.0.1

Plugin de carrusel horizontal con scroll para Elementor que usa **Custom Post Type "Servicios"**.

## 🎯 Características

- ✅ Carrusel horizontal controlado por scroll
- ✅ Widget de Elementor con editor visual
- ✅ **Custom Post Type "Servicios"** con categorías propias
- ✅ Diseño responsive y premium
- ✅ **JavaScript puro (sin jQuery)** - Mayor compatibilidad
- ✅ **Fix de scroll para contextos embebidos** (WordPress/Elementor)
- ✅ Navegación con flechas
- ✅ Carga dinámica de servicios con imágenes destacadas

## 🆕 Cambios en v1.0.1

- ✅ Reescrito JavaScript con vanilla JS (eliminada dependencia de jQuery)
- ✅ Agregado Custom Post Type "Servicio"
- ✅ Agregada Taxonomía "Categoría de Servicio"
-Mejor rendimiento y compatibilidad

## 📦 Instalación

### Método 1: Manual

1. Descarga o copia la carpeta `horizontal-carousel`
2. Sube la carpeta a `/wp-content/plugins/` en tu instalación de WordPress
3. Ve a **Plugins** en el panel de WordPress
4. Activa el plugin **"Horizontal Carousel"**

Al activar el plugin, se creará automáticamente:
- **Custom Post Type "Servicios"** → Aparecerá en el menú lateral de WordPress
- **Taxonomía "Categorías de Servicio"** → Para organizar tus servicios

## ⚙️ Requisitos

- WordPress 5.0 o superior
- Elementor 3.0.0 o superior
- PHP 7.4 o superior

## 🚀 Uso

### 1. Crear Servicios

**Primero debes crear servicios desde el panel de WordPress:**

1. Ve a **Servicios** → **Agregar Nuevo** (en el menú lateral de WordPress)
2. Llena los datos del servicio:
   - **Título**: Nombre del servicio (ej: "Public Relations", "Digital Strategy")
   - **Contenido**: Descripción del servicio (opcional)
   - **Imagen Destacada**: Imagen que se mostrará en el carrusel (IMPORTANTE - mínimo 1920x1080px)
   - **Categoría de Servicio**: Asigna una o más categorías

3. **Crear Categorías** (si no existen):
   - Ve a **Servicios** → **Categorías**
   - Crea categorías como: "Marketing", "Diseño", "Consultoría", etc.

4. Publica el servicio

5. Repite para crear más servicios (recomendado: 5-10 servicios)

### 2. Agregar el widget a una página

1. Edita una página con **Elementor**
2. Busca el widget **"Horizontal Carousel"** en el panel de widgets
3. Arrastra el widget a tu página

### 3. Configurar el widget

#### Pestaña Contenido:

- **Categoría de Servicio**: Selecciona la categoría de servicios a mostrar (o "Todas las categorías")
- **Número de Servicios**: Cuántos servicios mostrar (1-50)
- **Ordenar Por**: Fecha, Título, Aleatorio, Orden del Menú
- **Orden**: Ascendente o Descendente

#### Pestaña Configuración:

- **Altura de Sección (vh)**: Controla la "velocidad" del scroll (200-800vh)
  - Valor más alto = scroll más lento
  - Recomendado: 400vh

#### Pestaña Estilo:

- **Color del Título**: Color del texto del título
- **Color del Botón**: Color del botón "Consultar"

### 4. Publicar

Haz clic en **"Publicar"** o **"Actualizar"** y visualiza tu página.

## 🛠️ Solución del Problema de Scroll

Este plugin **soluciona el problema** donde el carrusel mostraba los slides 4-5 primero en lugar del slide 1 cuando se integraba en WordPress/Elementor.

### ¿Qué se arregló en v1.0.1?

- ✅ JavaScript reescrito con **vanilla JS** (sin jQuery)
- ✅ Cálculo de scroll usando posición relativa al viewport (`getBoundingClientRect()`)
- ✅ Eliminación de dependencia de `offsetTop` (que fallaba en contextos embebidos)
- ✅ El carrusel ahora **siempre empieza desde el slide 1**
- ✅ Funciona correctamente en bloques HTML de Elementor
- ✅ Mejor rendimiento con `requestAnimationFrame`

## 📝 Personalización

### Cambiar estilos

Edita `/assets/css/carousel.css` para personalizar:
- Colores
- Tipografía
- Tamaños
- Animaciones

### Cambiar comportamiento

Edita `/assets/js/carousel.js` para modificar:
- Velocidad de scroll
- Animaciones
- Navegación

## 🔍 Troubleshooting

### No aparece "Servicios" en el menú

**Solución**:
1. Desactiva y reactiva el plugin
2. Ve a **Ajustes** → **Enlaces Permanentes** y haz clic en "Guardar cambios"
3. Refresca la página y el menú "Servicios" debería aparecer

### El carrusel no se mueve

**Solución**: Asegúrate de que:
1. La sección tiene suficiente altura (mínimo 300vh)
2. Hay espacio suficiente para hacer scroll en la página
3. JavaScript está habilitado en el navegador
4. Abre la consola del navegador (F12) y verifica que no haya errores

### No se muestran servicios

**Solución**: Verifica que:
1. Has creado servicios en **Servicios** → **Agregar Nuevo**
2. Los servicios están en estado "Publicado"
3. La categoría seleccionada tiene servicios asignados
4. Aumenta el número de servicios en la configuración del widget

### Las imágenes no se muestran

**Solución**:
1. Asegúrate de que los servicios tengan **imagen destacada**
2. El plugin usa imagen de fallback si no hay imagen destacada
3. Verifica permisos de medios en WordPress

### El scroll sigue empezando mal

**Solución**:
1. Limpia el caché de WordPress/Elementor
2. Limpia el caché del navegador (Ctrl + Shift + R)
3. Verifica que estás usando la versión 1.0.1 del plugin
4. Desactiva otros plugins de JavaScript para verificar conflictos

## 📄 Estructura de Archivos

```
horizontal-carousel/
├── horizontal-carousel.php          # Archivo principal del plugin
├── widgets/
│   └── horizontal-carousel-widget.php  # Widget de Elementor
├── assets/
│   ├── css/
│   │   └── carousel.css             # Estilos del carrusel
│   └── js/
│       └── carousel.js              # JavaScript (vanilla JS)
├── carousel-fixed-demo.html         # Demo standalone
└── README.md                        # Este archivo
```

## 🎨 Recomendaciones

1. **Imágenes**: Usa imágenes de alta calidad (mínimo 1920x1080px)
2. **Títulos**: Mantén los títulos cortos para mejor visualización (2-3 palabras por línea)
3. **Cantidad**: 5-10 servicios es ideal para una buena experiencia
4. **Altura**: Empieza con 400vh y ajusta según necesites
5. **Categorías**: Organiza tus servicios en categorías para filtrar fácilmente

## 🆕 Diferencia entre Posts y Servicios

| Característica | Posts (WordPress) | Servicios (Plugin) |
|---|---|---|
| Propósito | Blog, noticias | Servicios de negocio |
| Taxonomía | Categorías normales | Categorías de Servicio |
| En el carrusel | ❌ No (v1.0.1+) | ✅ Sí |
| Menú WordPress | Posts → Entradas | Servicios |

## 💡 Próximas Mejoras

- [ ] Soporte para Custom Post Types adicionales
- [ ] Más opciones de estilo en Elementor
- [ ] Animaciones configurables
- [ ] Soporte para ACF (Advanced Custom Fields)
- [ ] Modo de carrusel automático (auto-play)

## 📧 Soporte

Si tienes problemas o preguntas, contacta al desarrollador.

## 📜 Changelog

### Versión 1.0.1 (13 Feb 2026)
- Reescrito JavaScript con vanilla JS (sin jQuery)
- Agregado Custom Post Type "Servicio"
- Agregada Taxonomía "Categoría de Servicio"
- Mejorado rendimiento y compatibilidad
- Fix definitivo para scroll en contextos embebidos

### Versión 1.0.0 (13 Feb 2026)
- Lanzamiento inicial
- Widget de Elementor
- Soporte para posts de WordPress
- Navegación con flechas

## 📜 Licencia

GPL v2 o posterior

---

**Versión**: 1.0.1  
**Última actualización**: 13 Febrero 2026  
**Autor**: Tu Nombre
