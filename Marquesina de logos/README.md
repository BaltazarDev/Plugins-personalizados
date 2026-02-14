# Plugin Logo Marquee - WordPress/Elementor

Plugin de WordPress que muestra logos en una marquesina infinita completamente personalizable desde el editor de Elementor.

## 🎯 Características

- ✅ **Galería de Imágenes**: Selecciona múltiples logos desde la biblioteca de medios
- ✅ **Dirección Configurable**: Izquierda o derecha
- ✅ **Duplicación Automática**: Loop infinito sin interrupciones
- ✅ **Control de Velocidad**: Ajusta la velocidad de la animación (1-100)
- ✅ **Object-Fit Completo**: Contain, cover, fill, scale-down, none
- ✅ **Tamaños Personalizables**: Altura y ancho de imágenes (responsive)
- ✅ **Espaciado Ajustable**: Gap entre logos
- ✅ **Efectos Visuales**: Escala de grises, opacidad, bordes redondeados
- ✅ **Fondo Transparente**: Color de fondo configurable
- ✅ **100% Responsive**: Controles específicos por dispositivo

## 📦 Instalación

### Opción 1: Instalación Manual
1. Copia la carpeta `Marquesina de logos` completa a `wp-content/plugins/`
2. Ve a WordPress Admin → Plugins
3. Activa "Plugin Logo Marquee"

### Opción 2: Instalación por ZIP
1. Comprime la carpeta `Marquesina de logos` en un archivo .zip
2. Ve a WordPress Admin → Plugins → Añadir nuevo → Subir plugin
3. Sube el archivo .zip y activa el plugin

## 🚀 Uso

### 1. Agregar Widget en Elementor

1. Edita una página con Elementor
2. Busca el widget **"Logo Marquee"** en el panel izquierdo
3. Arrastra el widget a tu página

### 2. Configurar Contenido

#### Pestaña Contenido

**Seleccionar Imágenes:**
- Haz clic en "Seleccionar Imágenes"
- Elige múltiples logos desde la biblioteca de medios
- Puedes reordenar arrastrando las imágenes

**Dirección:**
- **Izquierda**: Los logos se mueven de derecha a izquierda →
- **Derecha**: Los logos se mueven de izquierda a derecha ←

**Duplicar Imágenes:**
- **Activado**: Duplica las imágenes para crear un loop infinito perfecto
- **Desactivado**: Muestra solo un conjunto de imágenes

**Velocidad:**
- Slider de 1 a 100
- 1 = Muy lento (100 segundos por ciclo)
- 100 = Muy rápido (10 segundos por ciclo)
- Recomendado: 20-40 para mejor visualización

### 3. Personalizar Estilos

#### Pestaña Estilo → Imágenes

**Altura de Imagen:**
- Ajusta la altura de los logos
- Unidades: px, vh, em
- Responsive: Configura diferentes alturas para móvil, tablet, escritorio

**Ancho de Imagen:**
- Auto: Mantiene proporción
- Custom: Define ancho específico en px

**Object Fit:**
- **Contain**: La imagen completa es visible (recomendado para logos)
- **Cover**: Rellena el espacio, puede recortar
- **Fill**: Estira la imagen
- **Scale Down**: Como contain pero nunca agranda
- **None**: Tamaño original

**Espaciado entre Imágenes:**
- Gap entre cada logo
- Unidades: px, em
- Responsive

**Radio del Borde:**
- Esquinas redondeadas
- 0 = Cuadrado
- 50% = Circular

**Escala de Grises:**
- Activado: Logos en blanco y negro
- Hover: Vuelven a color al pasar el mouse

**Opacidad:**
- 0 = Transparente
- 1 = Opaco

#### Pestaña Estilo → Contenedor

**Color de Fondo:**
- Color del contenedor
- Transparente por defecto

**Padding:**
- Espaciado interno del contenedor
- Top, Right, Bottom, Left
- Responsive

## 🎨 Ejemplos de Uso

### Marquesina de Clientes
```
- Imágenes: Logos de clientes
- Dirección: Izquierda
- Duplicar: Sí
- Velocidad: 25
- Object Fit: Contain
- Altura: 60px
```

### Marquesina de Partners
```
- Imágenes: Logos de partners
- Dirección: Derecha
- Duplicar: Sí
- Velocidad: 35
- Object Fit: Contain
- Altura: 80px
- Escala de Grises: Sí
```

### Marquesina Rápida
```
- Imágenes: Iconos o badges
- Dirección: Izquierda
- Duplicar: Sí
- Velocidad: 60
- Object Fit: Cover
- Altura: 50px
```

## 📁 Estructura de Archivos

```
Marquesina de logos/
├── plugin-logo-marquee.php          # Archivo principal del plugin
├── widgets/
│   └── logo-marquee-widget.php      # Widget de Elementor
└── README.md                         # Este archivo
```

## 🔧 Requisitos

- WordPress 5.0 o superior
- Elementor 3.0 o superior
- PHP 7.0 o superior

## 💡 Consejos

1. **Número de Imágenes**: 6-12 logos es ideal para un buen efecto visual
2. **Duplicación**: Siempre activada para loop infinito sin cortes
3. **Velocidad**: 
   - Lento (10-20): Para lectura detallada
   - Medio (25-40): Balance perfecto
   - Rápido (50-80): Efecto dinámico
4. **Object Fit**: Usa "Contain" para logos con diferentes tamaños
5. **Altura**: Mantén consistente entre 60-100px para mejor legibilidad
6. **Formato de Imágenes**: PNG con fondo transparente funciona mejor

## 🐛 Solución de Problemas

**Las imágenes no se mueven:**
- Verifica que hayas seleccionado imágenes en la galería
- Comprueba que JavaScript esté habilitado
- Revisa la consola del navegador para errores

**La animación se corta:**
- Activa "Duplicar Imágenes"
- Aumenta el número de logos en la galería

**Los logos se ven distorsionados:**
- Cambia el Object Fit a "Contain"
- Ajusta la altura de imagen
- Usa imágenes con proporción similar

**La marquesina es muy rápida/lenta:**
- Ajusta el slider de velocidad
- Valores recomendados: 20-40

## 📝 Notas Técnicas

- Usa CSS `@keyframes` para animación suave
- Animación con `transform: translateX()` para mejor rendimiento
- Cada instancia tiene ID único para evitar conflictos
- Compatible con múltiples widgets en la misma página
- No requiere JavaScript (solo CSS)

## 🎓 Créditos

Desarrollado por Antigravity
Plugin de marquesina infinita para WordPress/Elementor
