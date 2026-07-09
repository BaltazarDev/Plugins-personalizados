📦 ESTRUCTURA DEL PROYECTO - OmniPark Diagonal Images
======================================================

## Archivos principales

### 📄 plugin.php
- **Qué es:** Plugin principal de WordPress (listo para usar)
- **Uso:** Copiar con otros archivos a /wp-content/plugins/omnipark-diagonal/
- **Versión:** 1.0.0
- **Activación:** WordPress → Plugins → Activar

### 📄 diagonal-images-shortcode.php
- **Qué es:** Lógica del shortcode (importado por plugin.php)
- **Uso:** No se edita normalmente
- **Función:** Registra el shortcode [diagonal_images]

### 🎨 diagonal-images.css
- **Qué es:** Estilos CSS con el efecto diagonal
- **Uso:** Se carga automáticamente
- **Personalización:** Edita aquí para cambiar estilos

### 💻 snippet-functions.php
- **Qué es:** Versión snippetizada (para Code Snippets plugin)
- **Uso:** Copiar en Code Snippets si no quieres plugin completo
- **Alternativa a:** plugin.php + diagonal-images-shortcode.php

---

## Documentación

### 📖 README.md (COMIENZA AQUÍ)
- Descripción general del proyecto
- Características principales
- Instalación rápida
- Parámetros disponibles

### ⚡ QUICK_START.md (MÁS IMPORTANTE)
- Guía de instalación PASO A PASO
- 3 opciones diferentes de instalación
- Troubleshooting rápido
- Ejemplos reales

### 📚 INSTRUCCIONES.md
- Documentación completa
- Cómo obtener IDs de imágenes
- Personalización avanzada
- Compatibilidad

### 🎓 EJEMPLOS.md
- Múltiples ejemplos de uso
- Variantes de clip-path
- Agregar efectos (sombra, zoom, etc)
- Responsive personalizado

---

## Vista previa

### 🌐 preview.html
- **Qué es:** Demostración visual interactiva
- **Uso:** Abre en navegador (no necesita servidor)
- **Muestra:** 4 variantes del diseño
- **Función:** Probar antes de instalar

### 📄 elementor-html-widget.html
- **Qué es:** HTML para usar en Elementor
- **Uso:** Copiar en widget HTML de Elementor
- **No necesita:** Plugin ni shortcode

---

## ÍNDICE DE ARCHIVOS

```
omnipark/
├── 📄 plugin.php                    ← Plugin principal
├── 📄 diagonal-images-shortcode.php ← Lógica del shortcode
├── 🎨 diagonal-images.css           ← Estilos CSS
├── 💻 snippet-functions.php         ← Versión snippet
├── 🌐 preview.html                  ← Demo interactiva
├── 📄 elementor-html-widget.html    ← Para Elementor
│
├── 📖 README.md                     ← Guía general
├── ⚡ QUICK_START.md                ← EMPIEZA POR AQUÍ
├── 📚 INSTRUCCIONES.md              ← Documentación completa
├── 🎓 EJEMPLOS.md                   ← Ejemplos avanzados
├── 📋 ARCHIVO_INDICE.md             ← Este archivo
└── 📄 VERSIONES.md                  ← Historial de cambios
```

---

## PLAN DE INSTALACIÓN

### Opción A: Plugin (Recomendado)
```
1. Lee: QUICK_START.md
2. Copia: plugin.php, diagonal-images-shortcode.php, diagonal-images.css
3. Pega en: /wp-content/plugins/omnipark-diagonal/
4. Activa en WordPress
5. Usa: [diagonal_images ids="101,102,103,104"]
```

### Opción B: Snippet
```
1. Lee: QUICK_START.md
2. Instala: Code Snippets plugin
3. Copia: snippet-functions.php
4. Agrégalo en Code Snippets
5. Activa
6. Usa: [diagonal_images ids="101,102,103,104"]
```

### Opción C: Elementor
```
1. Abre: preview.html (para ver cómo se vería)
2. Copia: elementor-html-widget.html
3. En Elementor, agrega widget HTML
4. Pega el código
5. Reemplaza URLs de imágenes
```

---

## FLUJO RECOMENDADO

1. 📖 Lee `QUICK_START.md` (5 minutos)
2. 🌐 Abre `preview.html` en navegador (para ver)
3. 💻 Instala usando una de las 3 opciones
4. 🧪 Prueba con el shortcode
5. 🎨 Personaliza si necesitas

---

## CHEAT SHEET - Comandos rápidos

### Shortcode básico
```
[diagonal_images ids="101,102,103,104"]
```

### Con espaciado
```
[diagonal_images ids="101,102,103,104" gap="10"]
```

### Con altura personalizada
```
[diagonal_images ids="101,102,103,104" height="400"]
```

### Todas las opciones
```
[diagonal_images ids="101,102,103,104" gap="10" height="350"]
```

---

## PREGUNTAS FRECUENTES

**¿Cuál es la mejor opción para instalar?**
→ Plugin (Opción A) es más profesional y fácil de mantener

**¿Necesito ser developer?**
→ No, es tan fácil como copiar archivos

**¿Funciona con Elementor?**
→ Sí, con 3 métodos diferentes

**¿Puedo personalizar los estilos?**
→ Sí, edita diagonal-images.css

**¿Es responsive?**
→ Sí, automáticamente

---

## NOTAS IMPORTANTES

✅ Sin JavaScript - puro CSS
✅ Compatible con caché
✅ SEO friendly
✅ Altamente personalizable
✅ Mantenimiento mínimo

---

**COMIENZA AQUÍ:** QUICK_START.md ⚡

Para más detalles, lee README.md 📖
