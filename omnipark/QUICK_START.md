# ⚡ QUICK START - Guía rápida de instalación

## 3 formas de instalar (elige una)

---

## 🟢 OPCIÓN 1: Plugin Completo (Recomendado)

### Paso 1: Preparar carpeta
```
1. Ve a: /wp-content/plugins/
2. Crea una carpeta: omnipark-diagonal
```

### Paso 2: Copiar archivos
Copia estos 3 archivos a la carpeta `omnipark-diagonal`:
- `plugin.php`
- `diagonal-images-shortcode.php`
- `diagonal-images.css`

### Paso 3: Activar
1. Ve a WordPress → Plugins
2. Busca "OmniPark Diagonal Images"
3. Haz clic en "Activar"

### Paso 4: Usar
```
[diagonal_images ids="101,102,103,104"]
```

---

## 🟠 OPCIÓN 2: Snippet (Más rápido)

### Paso 1: Instalar plugin "Code Snippets"
- Plugin → Agregar nuevo
- Busca "Code Snippets"
- Instala y activa

### Paso 2: Agregar snippet
1. Ve a Snippets → Agregar nuevo
2. Copia todo el contenido de `snippet-functions.php`
3. Pega en el editor
4. Activa el snippet

### Paso 3: Usar
```
[diagonal_images ids="101,102,103,104"]
```

---

## 🟡 OPCIÓN 3: Elementor (Sin código)

### Paso 1: Abrir Elementor
1. Edita una página con Elementor
2. Busca el widget "HTML"

### Paso 2: Copiar HTML
1. Abre `elementor-html-widget.html`
2. Copia TODO el contenido

### Paso 3: Pegar en Elementor
1. Pega el código en el widget HTML
2. Reemplaza las URLs de las imágenes
3. Personaliza gap y height si quieres

---

## 🎯 Uso rápido

### Encuentra los IDs de tus imágenes
1. Ve a Biblioteca Multimedia en WordPress
2. Haz clic en una imagen
3. Busca el número en la URL
4. Anota: 101, 102, 103, 104

### Usa el shortcode
```
[diagonal_images ids="101,102,103,104"]
```

### Personaliza (opcional)
```
[diagonal_images ids="101,102,103,104" gap="10" height="350"]
```

---

## 🧪 Prueba primero

Abre `preview.html` en tu navegador para ver:
- 4 variantes diferentes
- Cómo se vería en desktop y mobile
- Ejemplos de código para copiar

---

## 📋 Parámetros útiles

| Parámetro | Valor | Ejemplo |
|-----------|-------|---------|
| `gap` | Espacio entre imágenes | `0`, `10`, `20` |
| `height` | Altura de imágenes | `250`, `300`, `400` |

---

## 🎨 Personalizar después

Una vez instalado, puedes:

### Cambiar el ángulo del corte
- Edita `diagonal-images.css`
- Busca `clip-path:`
- Cambia: `15% 0%` a `20% 0%` para más ángulo

### Agregar sombra
- En el CSS, agrega:
```css
.diagonal-image-wrapper {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}
```

### Cambiar colores
- Agrega fondo antes de cada imagen:
```css
.diagonal-image-wrapper {
    background: #667eea;
}
```

---

## ❌ Si algo no funciona

**Las imágenes no se muestran:**
- Verifica los IDs sean números correctos
- Asegúrate de que las imágenes existan en WordPress

**El shortcode no funciona:**
- Verifica que esté activado (Opción 1 o 2)
- Limpia el caché

**Las imágenes se ven raras:**
- Limpia caché del navegador (Ctrl+F5)
- Verifica que el CSS se cargue (DevTools → F12)

---

## 💡 Ejemplos reales

### Para homepage
```
[diagonal_images ids="10,11,12,13" gap="0" height="300"]
```

### Para sección de servicios
```
[diagonal_images ids="20,21,22,23" gap="15" height="350"]
```

### Para portafolio
```
[diagonal_images ids="30,31,32,33" gap="10" height="400"]
```

---

## 🎓 Siguiente paso

Una vez que funcione:
1. Personaliza los estilos en CSS
2. Ajusta los parámetros a tu gusto
3. Lee `EJEMPLOS.md` para más ideas

---

**¡Listo! Ya tienes todo lo que necesitas** 🚀

Cualquier pregunta, revisa:
- `README.md` - Guía completa
- `EJEMPLOS.md` - Ejemplos avanzados
- `preview.html` - Ver en el navegador
