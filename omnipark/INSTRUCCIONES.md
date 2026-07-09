# Diagonal Images Shortcode - Guía de Uso

## Instalación

### Opción A: Como Plugin (Recomendado)
1. Coloca los archivos `diagonal-images-shortcode.php` y `diagonal-images.css` en una carpeta llamada `omnipark-diagonal` en `/wp-content/plugins/`
2. Crea un archivo `plugin.php` con el header de plugin
3. Activa desde el panel de WordPress

### Opción B: Como Snippet
Usa el plugin "Code Snippets" e inserta el código de `diagonal-images-shortcode.php`

---

## Uso del Shortcode

### Ejemplo 1: Usando IDs de imágenes (recomendado)
```
[diagonal_images ids="101,102,103,104" gap="0" height="300"]
```

### Ejemplo 2: Usando URLs directas
```
[diagonal_images images="https://ejemplo.com/img1.jpg,https://ejemplo.com/img2.jpg,https://ejemplo.com/img3.jpg,https://ejemplo.com/img4.jpg" gap="10" height="300"]
```

### Ejemplo 3: Con espaciado entre imágenes
```
[diagonal_images ids="101,102,103,104" gap="15" height="350"]
```

---

## Parámetros

| Parámetro | Descripción | Valor por defecto | Ejemplo |
|-----------|-------------|-------------------|---------|
| `ids` | IDs de attachment de WordPress separados por coma | - | `"101,102,103"` |
| `images` | URLs de imágenes separadas por coma | - | `"https://...jpg,https://...jpg"` |
| `gap` | Espacio entre imágenes en píxeles | `0` | `"15"` |
| `height` | Altura de las imágenes en píxeles | `300` | `"400"` |

---

## Personalización

### Cambiar el ángulo del corte diagonal

En `diagonal-images.css`, modifica el `clip-path`:

**Más ángulo:**
```css
clip-path: polygon(
    20% 0%,
    100% 0%,
    80% 100%,
    0% 100%
);
```

**Menos ángulo:**
```css
clip-path: polygon(
    10% 0%,
    100% 0%,
    90% 100%,
    0% 100%
);
```

### Agregar sombra
```css
.diagonal-image-wrapper {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
```

### Agregar efecto hover
```css
.diagonal-image-wrapper {
    transition: transform 0.3s ease;
}

.diagonal-image-wrapper:hover {
    transform: scale(1.05);
}
```

---

## Compatibilidad

- ✅ WordPress 5.0+
- ✅ Todos los navegadores modernos (Firefox, Chrome, Safari, Edge)
- ✅ Responsive
- ✅ Compatible con Elementor (en editor HTML)

---

## Notas

- Las imágenes se ajustan automáticamente al contenedor
- El corte diagonal es puro CSS (sin imágenes adicionales)
- No requiere JavaScript adicional
