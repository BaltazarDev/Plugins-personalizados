# ⚡ GUÍA DE INSTALACIÓN RÁPIDA - v2.0.0

## 🎯 Objetivo
Tener el widget de Elementor funcionando en 5 minutos.

---

## 📋 Checklist pre-instalación

- [ ] WordPress 5.0+
- [ ] Elementor 3.0+ activado
- [ ] Acceso FTP o gestor de archivos
- [ ] 2 MB de espacio disponible

---

## 🚀 INSTALACIÓN (5 minutos)

### Paso 1: Prepara la carpeta (1 min)

```
1. Ve a tu servidor → /wp-content/plugins/
2. Crea una nueva carpeta llamada: omnipark-diagonal
3. Listo!
```

### Paso 2: Copia los archivos (2 min)

Copia TODOS estos archivos a la carpeta `omnipark-diagonal`:

**Archivos esenciales:**
```
✅ plugin.php (OBLIGATORIO)
✅ diagonal-images.css
✅ diagonal-images-shortcode.php
```

**Carpeta includes (OBLIGATORIA):**
```
✅ includes/
   └── elementor-widget.php
```

**Carpeta js (OBLIGATORIA):**
```
✅ js/
   └── frontend.js
```

**Documentación (Opcional pero recomendado):**
```
📄 README_V2.md
📄 WIDGET_ELEMENTOR.md
📄 QUICK_START.md
```

### Paso 3: Activa en WordPress (1 min)

1. Ve a **Plugins** en WordPress
2. Busca "OmniPark Diagonal Images"
3. Haz clic en **"Activar"**
4. Verás un mensaje de confirmación ✅

### Paso 4: Verifica Elementor (1 min)

1. Edita cualquier página con Elementor
2. Abre el panel izquierdo
3. Busca "OmniPark Diagonal Images"
4. Si lo ves, ¡está listo! 🎉

---

## ✅ Verificación de instalación

### Checklist técnico

- [ ] Archivo `plugin.php` existe en `/wp-content/plugins/omnipark-diagonal/`
- [ ] Carpeta `includes/` existe
- [ ] Archivo `includes/elementor-widget.php` existe
- [ ] Carpeta `js/` existe
- [ ] Archivo `js/frontend.js` existe
- [ ] Archivo `diagonal-images.css` existe
- [ ] Plugin aparece en WordPress → Plugins
- [ ] Plugin está activado
- [ ] Elementor está activado
- [ ] Widget aparece en Elementor

Si todos están ✅, estás listo.

---

## 🎨 Tu primer widget (2 minutos)

### 1️⃣ Abre Elementor
```
WordPress → Editar con Elementor
```

### 2️⃣ Busca el widget
```
Panel izquierdo → Busca "OmniPark"
```

### 3️⃣ Arrastra al contenido
```
Arrastra "OmniPark Diagonal Images" al área de contenido
```

### 4️⃣ Agrega imágenes
```
Panel izquierdo → Sección "Imágenes"
Haz clic en "Agregar elemento"
Carga una imagen
```

### 5️⃣ Personaliza
```
Sección "Diseño" → Ajusta gap, altura, ángulo
Sección "Estilos" → Cambia colores y fuentes
```

### 6️⃣ Publica
```
Botón "Publicar" en Elementor
```

---

## 🎛️ Configuración recomendada para OmniPark

Si es para el proyecto OmniPark, usa estos valores:

```
DISEÑO:
- Espacio entre imágenes: 0px (tocadas)
- Altura: 300px
- Ángulo del corte: 15°
- Alineación: Centro

TEXTOS (Ejemplo):
Imagen 1: "Inversión segura"
Imagen 2: "Plusvalía acelerada"
Imagen 3: "Business Hub con amenidades premium"
Imagen 4: "Bodegas modulares con diseño de primer nivel"

ESTILOS DE TEXTO:
- Fuente: Montserrat o similar
- Tamaño: 16px
- Peso: Bold (600)
- Color: Blanco (#FFFFFF)
- Fondo: Negro con opacidad (rgba(0,0,0,0.7))

ESTILOS DE IMAGEN:
- Efecto hover: Zoom
- Sombra: Media (10px 10px 30px)
- Radio de borde: 0px
```

---

## 🐛 ¿Problemas?

### El widget no aparece en Elementor
```
Solución 1: Recarga la página (Ctrl+F5)
Solución 2: Regenera CSS en Elementor → Tools → Regenerate CSS
Solución 3: Desactiva y reactiva el plugin
```

### Error de permisos
```
Verifica que la carpeta omnipark-diagonal tenga permisos 755
```

### Las imágenes no se cargan
```
Verifica que la carpeta /uploads/ tenga permisos 755
Comprueba que las imágenes sean válidas (JPG, PNG, WebP)
```

### El CSS no se aplica
```
1. Abre DevTools (F12)
2. Verifica que diagonal-images.css esté cargado
3. Limpia caché del navegador
```

---

## 📁 Estructura final de carpetas

```
/wp-content/plugins/
├── omnipark-diagonal/
│   ├── plugin.php                          ← Plugin principal
│   ├── diagonal-images-shortcode.php       ← Lógica
│   ├── diagonal-images.css                 ← Estilos
│   │
│   ├── includes/
│   │   └── elementor-widget.php            ← Widget de Elementor
│   │
│   ├── js/
│   │   └── frontend.js                     ← JavaScript
│   │
│   ├── README_V2.md                        ← Documentación
│   ├── WIDGET_ELEMENTOR.md
│   └── QUICK_START.md
```

---

## 🎓 Siguientes pasos

1. ✅ Instalación completada
2. 📖 Lee [WIDGET_ELEMENTOR.md](WIDGET_ELEMENTOR.md) para conocer todas las opciones
3. 🎨 Crea tu primer widget
4. 🚀 Personaliza según tus necesidades
5. 📞 Consulta EJEMPLOS.md para casos avanzados

---

## 💡 Tips rápidos

- **Guardar siempre:** Elementor auto-guarda, pero presiona Ctrl+S
- **Vista previa:** Usa "Preview" antes de publicar
- **Mobile:** Prueba cómo se ve en mobile (pestana responsive)
- **CSS personalizado:** Puedes agregar CSS en Elementor → Site Settings
- **Reutilizar:** Guarda el widget como "Global" para usarlo en otras páginas

---

## ✨ ¡Felicidades!

Ya tienes el widget funcionando. Ahora:

1. Experimenta con los parámetros
2. Personaliza los colores y tipografías
3. Crea galerías impresionantes
4. ¡Muestra los resultados!

---

## 📞 Necesitas ayuda?

- **Documentación:** Abre [WIDGET_ELEMENTOR.md](WIDGET_ELEMENTOR.md)
- **Ejemplos:** Abre [EJEMPLOS.md](EJEMPLOS.md)
- **Troubleshooting:** Abre [README_V2.md](README_V2.md)

---

**Versión:** 2.0.0
**Tiempo de instalación:** ~5 minutos
**Nivel de dificultad:** ⭐ Muy fácil

¡Disfruta creando! 🎉
