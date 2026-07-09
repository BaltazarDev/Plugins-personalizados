# 🎨 Diagonal Images Shortcode - OmniPark

Solución completa para crear imágenes con corte diagonal para WordPress y Elementor.

## ✨ Características

✅ **Shortcode WordPress** - Fácil de usar: `[diagonal_images ids="101,102,103,104"]`
✅ **Responsive** - Se adapta a cualquier pantalla
✅ **Puro CSS** - Sin JavaScript, súper rápido
✅ **Elementor Compatible** - Widget HTML integrado
✅ **Personalizable** - Gap, altura, ángulo del corte
✅ **Efecto Hover** - Animación suave al pasar el mouse
✅ **3 formas de usar** - Plugin, Snippet o HTML directo

---

## 📁 Archivos incluidos

| Archivo | Descripción |
|---------|-------------|
| `diagonal-images-shortcode.php` | Plugin completo |
| `diagonal-images.css` | Estilos CSS |
| `snippet-functions.php` | Versión de snippet (para Code Snippets o functions.php) |
| `elementor-html-widget.html` | HTML para usar en Elementor |
| `preview.html` | Vista previa interactiva (abre en navegador) |
| `INSTRUCCIONES.md` | Guía de instalación completa |
| `EJEMPLOS.md` | Ejemplos de uso y personalización |

---

## 🚀 Instalación rápida

### Opción 1: Como Plugin (Recomendado)

```bash
1. Copia los archivos a: /wp-content/plugins/omnipark-diagonal/
2. Crea un archivo plugin.php con:
```

```php
<?php
/**
 * Plugin Name: OmniPark Diagonal Images
 * Description: Shortcode para imágenes con corte diagonal
 * Version: 1.0
 * Author: Tu nombre
 */

require_once __DIR__ . '/diagonal-images-shortcode.php';
```

```bash
3. Activa desde WordPress
4. Listo! Usa: [diagonal_images ids="101,102,103,104"]
```

### Opción 2: Como Snippet

1. Instala el plugin "Code Snippets"
2. Copia el contenido de `snippet-functions.php`
3. Agrega un snippet nuevo
4. Listo! Usa: `[diagonal_images ids="101,102,103,104"]`

### Opción 3: En Elementor (HTML Widget)

1. Abre Elementor
2. Agrega widget "HTML"
3. Copia el contenido de `elementor-html-widget.html`
4. Personaliza las URLs de las imágenes

---

## 💡 Uso

### Ejemplo básico
```
[diagonal_images ids="101,102,103,104"]
```

### Con parámetros
```
[diagonal_images ids="101,102,103,104" gap="10" height="350"]
```

### Con URLs directas
```
[diagonal_images images="https://ejemplo.com/img1.jpg,https://ejemplo.com/img2.jpg" gap="0" height="300"]
```

---

## 🎛️ Parámetros

| Parámetro | Descripción | Default | Ejemplo |
|-----------|-------------|---------|---------|
| `ids` | IDs de imágenes separadas por coma | - | `"101,102,103"` |
| `images` | URLs separadas por coma | - | `"url1.jpg,url2.jpg"` |
| `gap` | Espacio entre imágenes (px) | `0` | `"10"` |
| `height` | Altura de imágenes (px) | `300` | `"400"` |

---

## 🎨 Personalización

### Cambiar ángulo del corte diagonal

En `diagonal-images.css`, busca:

```css
clip-path: polygon(15% 0%, 100% 0%, 85% 100%, 0% 100%);
```

**Más ángulo (20°):**
```css
clip-path: polygon(20% 0%, 100% 0%, 80% 100%, 0% 100%);
```

**Menos ángulo (10°):**
```css
clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%);
```

### Agregar sombra

```css
.diagonal-image-wrapper {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
```

### Efecto zoom personalizado

```css
.diagonal-image-wrapper {
    transform: scale(1.1); /* Mayor zoom */
    transition: transform 0.5s ease; /* Más lento */
}
```

---

## 📺 Vista previa

Abre `preview.html` en tu navegador para ver todas las variantes:
- Sin espaciado
- Con espaciado pequeño
- Con espaciado grande
- Versión compacta

---

## 🔍 Obtener IDs de imágenes

1. Ve a **Biblioteca Multimedia** en WordPress
2. Haz clic en una imagen
3. Busca el ID en la URL o detalles
4. Anota los IDs separados por coma

---

## 📱 Responsive

El diseño se adapta automáticamente:
- **Desktop**: 4 imágenes lado a lado
- **Tablet**: Tamaño reducido
- **Mobile**: Apiladas verticalmente (configurable)

---

## ⚙️ Requisitos

- WordPress 5.0+
- Navegadores modernos (Firefox, Chrome, Safari, Edge)
- PHP 7.0+

---

## 🐛 Troubleshooting

**Las imágenes no se muestran:**
- Verifica los IDs sean correctos
- Asegúrate de que las imágenes existan en Biblioteca Multimedia

**El corte diagonal no aparece:**
- Abre DevTools (F12) y verifica el CSS
- Algunos navegadores viejos pueden no soportar clip-path

**Responsive no funciona:**
- Verifica que no tengas CSS en conflicto
- Borra cache del navegador

---

## 📝 Notas

- ✅ Sin dependencias externas
- ✅ Compatible con caché de WordPress
- ✅ Optimizado para SEO
- ✅ Altamente personalizable
- ✅ Código limpio y bien documentado

---

## 📞 Soporte

Para más información:
- Lee `INSTRUCCIONES.md`
- Revisa `EJEMPLOS.md`
- Abre `preview.html` en navegador

---

**Hecho con ❤️ para OmniPark**
