# Cómo Usar el Carrusel Horizontal en Elementor

## 📁 Archivos Disponibles

### 1. `carousel-clean.html` ✅ USAR ESTE EN ELEMENTOR
**Contenido**: Solo el código del carrusel (CSS + HTML + JavaScript)
**Para**: Copiar y pegar en un bloque HTML de Elementor
**Incluye**: Todo lo necesario, nada de más

### 2. `carousel-full-demo.html` (antes carousel-fixed-demo.html)
**Contenido**: Demo completo con secciones antes y después
**Para**: Probar localmente en navegador
**Incluye**: Secciones de demostración que NO debes copiar a WordPress

---

## 🚀 Instrucciones de Uso

### PASO 1: Copiar el código limpio

1. Abre el archivo `carousel-clean.html`
2. **Selecciona TODO el contenido** (Ctrl+A)
3. **Copia** (Ctrl+C)

### PASO 2: Pegar en Elementor

1. Abre tu página en **Elementor**
2. Agrega un widget **HTML** (busca "HTML" en el panel)
3. **Pega** todo el código copiado (Ctrl+V)
4. Haz clic en **"Actualizar"**

### PASO 3: Verificar

1. **Publica** la página
2. Abre la página en el navegador
3. **Haz scroll** hacia abajo hasta llegar al carrusel
4. Verifica que:
   - ✅ El carrusel se mueve horizontalmente
   - ✅ Empieza desde el primer slide
   - ✅ Las flechas funcionan

---

## 🎨 Personalización

### Cambiar la velocidad del scroll

En el CSS, busca esta línea:
```css
.carousel-section {
    height: 400vh; /* Cambia este valor */
}
```

- **200vh** = Muy rápido
- **400vh** = Normal (recomendado)
- **600vh** = Lento
- **800vh** = Muy lento

### Agregar más slides

Busca el comentario `<!-- AGREGAR MÁS SLIDES -->` en el HTML y copia/pega esta estructura:

```html
<div class="slide">
    <img src="URL_DE_TU_IMAGEN" alt="Descripción">
    <div class="slide-content">
        <h2 class="slide-title">Tu<br>Título</h2>
        <a href="#" class="consultar-btn">Consultar <span>→</span></a>
    </div>
</div>
```

### Cambiar imágenes

Reemplaza la URL en el atributo `src`:
```html
<img src="https://tu-imagen-aqui.jpg" alt="Descripción">
```

### Cambiar textos

Edita el contenido dentro de `.slide-title`:
```html
<h2 class="slide-title">Tu<br>Texto<br>Aquí</h2>
```

### Cambiar enlaces

Cambia el `href` del botón:
```html
<a href="/tu-pagina" class="consultar-btn">Consultar <span>→</span></a>
```

---

## ⚠️ Importante

### ✅ LO QUE SÍ DEBES HACER

- ✅ Usar `carousel-clean.html` para Elementor
- ✅ Copiar TODO el contenido (incluye CSS, HTML y JavaScript)
- ✅ Pegarlo en un widget HTML de Elementor
- ✅ Tener otras secciones en tu página (el carrusel funcionará con ellas)

### ❌ LO QUE NO DEBES HACER

- ❌ Copiar el código de `carousel-full-demo.html` (tiene secciones extra)
- ❌ Copiar solo el HTML sin el CSS o JavaScript
- ❌ Separar el código en múltiples bloques HTML

---

## 🧪 Probar Antes de Usar

### Probar localmente

1. Abre `carousel-full-demo.html` en tu navegador
2. Haz scroll y verifica que funciona
3. Este archivo tiene secciones de demostración para simular WordPress

### Probar en Elementor

1. Crea una página de prueba
2. Agrega contenido normal arriba del carrusel (texto, imágenes, etc.)
3. Agrega el carrusel (widget HTML con `carousel-clean.html`)
4. Agrega contenido normal abajo del carrusel
5. Publica y verifica

---

## 🔧 Troubleshooting

### El carrusel no se mueve

**Problema**: El código no se copió completo
**Solución**: Asegúrate de copiar TODO el contenido de `carousel-clean.html`

### Empieza desde el slide equivocado

**Problema**: Hay contenido antes del carrusel que afecta el cálculo
**Solución**: El JavaScript ya está corregido para esto, debería funcionar

### Las imágenes no se ven

**Problema**: URLs de ejemplo no cargan
**Solución**: Reemplaza las URLs con tus propias imágenes

### Los estilos se ven raros

**Problema**: Conflicto con el tema de WordPress
**Solución**: Agrega `!important` a los estilos que no se aplican

---

## 📝 Ejemplo de Uso Real

```
TU PÁGINA EN ELEMENTOR:

[Sección 1: Hero/Banner]
[Sección 2: Texto/Contenido]
[WIDGET HTML: carousel-clean.html] ← PEGAR AQUÍ
[Sección 3: Footer]
```

El carrusel funcionará perfectamente entre tus otras secciones.

---

## 💡 Consejos

1. **Imágenes**: Usa imágenes de alta calidad (1920x1080 o más)
2. **Títulos**: Mantén los títulos cortos (2-3 palabras máximo)
3. **Cantidad**: 5-10 slides es ideal
4. **Altura**: Empieza con 400vh y ajusta a tu gusto
5. **Prueba**: Siempre prueba en una página borrador primero

---

**¿Listo para usar? Abre `carousel-clean.html`, copia todo, y pégalo en Elementor.** 🚀
