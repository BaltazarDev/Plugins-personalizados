# 🎨 OmniPark Diagonal Images - Widget Elementor

## ✨ Características v2.0.0

✅ **Widget Elementor profesional** - Panel de control visual
✅ **Múltiples imágenes** - Agregar/editar sin límite
✅ **Textos personalizables** - Para cada imagen
✅ **Colores ajustables** - Texto, fondo, sombras
✅ **Tipografías flexibles** - Tamaño, fuente, peso
✅ **Ángulo diagonal configurable** - 5 opciones preestablecidas
✅ **Efectos hover** - Zoom, elevación, escala de grises
✅ **Espaciado personalizable** - Gap y altura dinámicos
✅ **100% Responsive** - Mobile, tablet, desktop
✅ **Elementor nativo** - Sin código, solo arrastrar

---

## 📥 Instalación

### Opción 1: Como Plugin (Recomendado)

```bash
1. Descarga los archivos
2. Ve a /wp-content/plugins/
3. Crea carpeta: omnipark-diagonal
4. Copia TODOS los archivos dentro
5. WordPress → Plugins → Busca "OmniPark" → Activa
```

### Opción 2: Verificar Elementor

1. Asegúrate de tener **Elementor instalado** (versión 3.0+)
2. El widget se registrará automáticamente
3. Listo! ✅

---

## 🚀 Cómo usar

### Paso 1: Abrir Elementor
1. Edita una página con Elementor
2. Abre el panel izquierdo
3. Busca "OmniPark Diagonal Images"
4. Arrastra al contenido

### Paso 2: Agregar imágenes
1. En el panel, ve a la sección "Imágenes"
2. Haz clic en "Agregar elemento"
3. Carga tu imagen
4. Agrega el texto de la etiqueta
5. Personaliza colores de texto y fondo
6. ¡Repite para más imágenes!

### Paso 3: Personalizar diseño
En la sección "Diseño":
- **Espacio entre imágenes** - Slider 0-50px
- **Altura de imágenes** - Slider 100-600px
- **Ángulo del corte** - 5 opciones (10° a 30°)
- **Alineación** - Izquierda, centro, derecha

### Paso 4: Estilizar (Opcional)
En las secciones de estilos:
- **Estilos de Imagen** - Sombra, radio de borde, efectos hover
- **Estilos de Texto** - Fuente, tamaño, color, alineación
- **Estilos del Contenedor** - Fondo, espaciado

---

## 🎛️ Panel de control - Detalles

### 📋 Sección: Imágenes
- **Imagen** - Cargador de medios
- **Texto sobre la imagen** - Etiqueta visible
- **Color del texto** - Selector de color
- **Color de fondo del texto** - Selector de color con opacidad

### 📐 Sección: Diseño
| Control | Rango | Default |
|---------|-------|---------|
| Espacio entre imágenes | 0-50px | 0px |
| Altura de imágenes | 100-600px | 300px |
| Ángulo del corte | 10° a 30° | 15° |
| Alineación | Izq/Centro/Der | Centro |

### 🎨 Sección: Estilos de Imagen
- **Radio de borde** - 0-50px
- **Sombra** - Control de box-shadow completo
- **Efecto hover** - Sin efecto, Zoom, Elevación, Escala de grises

### ✍️ Sección: Estilos de Texto
- **Tipografía** - Fuente, tamaño, peso, estilo
- **Alineación** - Izquierda, centro, derecha
- **Espaciado** - Padding customizable

### 📦 Sección: Estilos del Contenedor
- **Color de fondo** - Selector de color
- **Espaciado** - Padding customizable

---

## 💡 Ejemplos prácticos

### Ejemplo 1: Portafolio minimalista
```
- Altura: 350px
- Espacio: 10px
- Ángulo: 15°
- Efecto hover: Zoom
- Sombra: Media
```

### Ejemplo 2: Showcase elegante
```
- Altura: 400px
- Espacio: 20px
- Ángulo: 20°
- Efecto hover: Elevación
- Sombra: Fuerte
- Fuente: Georgia, tamaño 18px
```

### Ejemplo 3: Galería compacta (para mobile)
```
- Altura: 250px
- Espacio: 5px
- Ángulo: 10°
- Efecto hover: Grayscale
- Sombra: Suave
```

### Ejemplo 4: Inmobiliario (como OmniPark)
```
- Altura: 300px
- Espacio: 0px
- Ángulo: 15°
- Efecto hover: Zoom
- Textos: "Inversión segura", "Plusvalía acelerada", etc.
- Colores: Blanco sobre fondo oscuro
- Fuente: Montserrat, tamaño 16px, bold
```

---

## 🎓 Personalización avanzada

### Cambiar el clip-path dinámicamente
El ángulo se calcula automáticamente según la opción seleccionada:
- **10°** → polygon(10%, 100%, 90%, 0%)
- **15°** → polygon(15%, 100%, 85%, 0%)
- **20°** → polygon(20%, 100%, 80%, 0%)
- **25°** → polygon(25%, 100%, 75%, 0%)
- **30°** → polygon(30%, 100%, 70%, 0%)

### Agregar efectos CSS personalizados
Si necesitas efectos adicionales, puedes agregar CSS personalizado en:
**Elementor → Site Settings → Custom CSS**

```css
/* Ejemplo: Efecto de superposición */
.elementor-widget-omnipark-diagonal-images .diagonal-image-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.1);
    transition: background 0.3s ease;
    pointer-events: none;
}

.elementor-widget-omnipark-diagonal-images .diagonal-image-wrapper:hover::before {
    background: rgba(102, 126, 234, 0.3);
}
```

---

## 📱 Responsive

El widget es completamente responsive:

**Desktop (>1024px)**
- Todas las imágenes lado a lado
- Tamaño completo configurado

**Tablet (768-1024px)**
- Imágenes reducidas automáticamente
- Espaciado ajustado

**Mobile (<768px)**
- Imágenes apiladas verticalmente
- Tamaño optimizado para pantalla pequeña
- Texto más pequeño

---

## 🐛 Troubleshooting

**El widget no aparece:**
- Verifica que Elementor esté activado
- Recarga la página (Ctrl+F5)
- Verifica la consola del navegador (F12)

**Las imágenes no se cargan:**
- Verifica que la imagen sea válida
- Comprueba permisos de carpeta /uploads/
- Intenta recargar la biblioteca de medios

**Los estilos no se aplican:**
- Limpia caché de Elementor
- Reconstruye CSS (Elementor → Tools → Regenerate CSS)

**El texto se ve cortado:**
- Aumenta el padding en "Estilos de Texto"
- Reduce el tamaño de fuente
- Aumenta la altura de las imágenes

---

## ✅ Checklist de instalación

- [ ] Plugin OmniPark instalado
- [ ] Elementor activado (v3.0+)
- [ ] PHP 7.0+
- [ ] WordPress 5.0+
- [ ] Plugin activado en WordPress
- [ ] Cache limpio
- [ ] Widget visible en Elementor

---

## 📞 Requisitos

- **WordPress:** 5.0+
- **Elementor:** 3.0+
- **PHP:** 7.0+
- **Navegadores:** Chrome, Firefox, Safari, Edge (versiones modernas)

---

## 🎉 ¡Listo!

Ahora puedes crear galerías diagonales profesionales sin tocar una sola línea de código.

**¿Preguntas o sugerencias?**
- Lee la documentación en `README.md`
- Revisa ejemplos en `EJEMPLOS.md`
- Consulta `QUICK_START.md`

---

**Versión:** 2.0.0
**Última actualización:** Mayo 2026
**Estado:** Estable y listo para producción
