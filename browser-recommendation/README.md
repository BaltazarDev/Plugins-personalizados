# Browser Recommendation Banner — Plugin de WordPress

Muestra un banner personalizable a usuarios de **Safari** y/o **Firefox** sugiriéndoles que usen **Google Chrome** o **Microsoft Edge**.

---

## 📦 Instalación

1. Descarga el archivo `browser-recommendation.zip`.
2. En el panel de WordPress ve a **Plugins → Añadir nuevo → Subir plugin**.
3. Selecciona el ZIP y haz clic en **Instalar ahora → Activar**.

---

## ⚙️ Configuración

Ve a **Ajustes → Browser Recommendation** y encontrarás cuatro pestañas:

| Pestaña | Qué configuras |
|---------|---------------|
| **⚙️ General** | Activar/desactivar, qué navegadores detectar, posición (barra superior/inferior/modal), animación, días de cookie |
| **✏️ Contenido** | Título, mensaje (con HTML básico), textos y URLs de los botones |
| **🎨 Diseño** | Todos los colores (fondo, texto, título, acento, botones), tipografía, tamaños y border-radius |
| **👁 Vista previa** | Previsualización estática con tus ajustes guardados |

---

## 🎨 Opciones de diseño

- **Posición**: barra superior, barra inferior o modal centrado.
- **Animación**: deslizar, desvanecer o ninguna.
- **Colores**: fondo, texto, título, acento, botones primarios y botón "continuar".
- **Tipografía**: familia de fuente, tamaño del título, mensaje y botones.
- **Border radius**: bordes redondeados para el contenedor y los botones.
- **Cookie**: cuántos días esperar antes de mostrar el banner de nuevo tras cerrarlo.

---

## 📱 Responsivo

El banner se adapta automáticamente a pantallas móviles:
- En barras: los botones ocupan el 100% del ancho.
- En modal: se ajusta al padding de la pantalla.

---

## 🧪 Prueba local

Para ver el banner sin usar Safari/Firefox puedes cambiar el User-Agent en Chrome DevTools:
1. Abre DevTools → menú `⋮` → **More tools → Network conditions**.
2. Desactiva "Use browser default" y elige un User Agent de Safari o Firefox.
3. Recarga la página.

---

## Licencia

GPL v2 o superior.
