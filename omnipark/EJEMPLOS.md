# EJEMPLOS DE USO - Diagonal Images Shortcode

## OPCIÓN 1: Shortcode WordPress (Lo más fácil)

Después de activar el plugin o agregar el snippet, usa:

```
[diagonal_images ids="101,102,103,104" gap="0" height="300"]
```

Sustituye 101,102,103,104 con los IDs reales de tus imágenes.

---

## OPCIÓN 2: Con URLs directas (sin necesidad de subir a WordPress Media)

```
[diagonal_images images="https://ejemplo.com/img1.jpg,https://ejemplo.com/img2.jpg,https://ejemplo.com/img3.jpg,https://ejemplo.com/img4.jpg" gap="0" height="300"]
```

---

## OPCIÓN 3: Con espaciado y altura personalizada

```
[diagonal_images ids="101,102,103,104" gap="15" height="400"]
```

---

## OPCIÓN 4: En Elementor (HTML Widget)

1. Abre una página en Elementor
2. Agrega un widget "HTML" (o "Custom Code")
3. Copia el contenido de `elementor-html-widget.html`
4. Reemplaza las URLs de las imágenes

---

## EJEMPLOS PARA OMNIPARK

### Ejemplo 1: Inversión segura, Plusvalía, Business Hub, Bodegas
```
[diagonal_images ids="101,102,103,104" gap="0" height="300"]
```

### Ejemplo 2: Con espaciado para que se vea más separado
```
[diagonal_images ids="101,102,103,104" gap="10" height="350"]
```

### Ejemplo 3: Imágenes más grandes
```
[diagonal_images ids="101,102,103,104" gap="5" height="450"]
```

---

## CÓMO OBTENER LOS IDs DE LAS IMÁGENES

1. Ve a **Biblioteca Multimedia** en WordPress
2. Haz clic en cada imagen
3. En la URL del navegador o en los detalles, encontrarás el ID
4. O pasa el mouse sobre la imagen en la lista y verás el ID

---

## VARIANTES DE CLIP-PATH (Ángulo del corte)

En el CSS, puedes cambiar el ángulo del corte diagonal:

### Ángulo suave (15°)
```css
clip-path: polygon(15% 0%, 100% 0%, 85% 100%, 0% 100%);
```

### Ángulo medio (20°) - MÁS PRONUNCIADO
```css
clip-path: polygon(20% 0%, 100% 0%, 80% 100%, 0% 100%);
```

### Ángulo suave (10°) - MÁS SUTIL
```css
clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%);
```

### Ángulo muy pronunciado (25°)
```css
clip-path: polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%);
```

---

## AGREGAR EFECTOS

### Sombra
```css
.diagonal-image-wrapper {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
```

### Efecto zoom al pasar el mouse
```css
.diagonal-image-wrapper {
    transition: transform 0.3s ease;
}

.diagonal-image-wrapper:hover {
    transform: scale(1.05);
}
```

### Bordes redondeados (opcional)
```css
.diagonal-image-wrapper {
    border-radius: 8px;
}
```

---

## RESPONSIVE - CÓMO SE VE EN MOBILE

El shortcode es completamente responsive. En dispositivos pequeños:
- Las imágenes se adaptan automáticamente
- Se pueden mostrar en columna si necesitas
- El corte diagonal se mantiene

---

## TROUBLESHOOTING

### Las imágenes no se muestran
- Verifica que los IDs sean correctos
- Usa `wp_get_attachment_image_url()` para probar

### El corte no aparece
- Abre DevTools y verifica que el CSS se cargue
- Algunos navegadores viejos no soportan `clip-path`

### Espaciado irregular
- Ajusta el parámetro `gap`
- Si uses `gap="0"`, las imágenes se tocan completamente

---

## MOBILE FIRST - PERSONALIZACIÓN RESPONSIVA

Para cambiar el comportamiento en mobile, edita el CSS:

```css
/* Tablets */
@media (max-width: 768px) {
    .diagonal-image-wrapper {
        min-width: 150px;
        height: 150px;
    }
}

/* Móviles */
@media (max-width: 480px) {
    .diagonal-images-container {
        flex-direction: column; /* Apila verticalmente */
    }
    
    .diagonal-image-wrapper {
        min-width: 100%; /* Ancho completo */
        height: 250px;
    }
}
```

---

## NOTAS IMPORTANTES

✅ Sin JavaScript - puro CSS
✅ Compatible con todos los navegadores modernos
✅ Las imágenes son responsive
✅ El corte diagonal es suave y profesional
✅ Personalizable fácilmente
✅ Sin dependencias externas
