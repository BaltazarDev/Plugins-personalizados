# Modern Roster Scroll Effect - HTML Puro

Conversión del efecto React a HTML vanilla para usar en WordPress/Elementor.

## 📁 Archivos Disponibles

### 1. `roster-scroll-vanilla.html` 
**Para**: Probar localmente en navegador
**Contenido**: Página completa con intro, efecto y outro
**Uso**: Abre directamente en el navegador para ver cómo funciona

### 2. `roster-scroll-elementor.html` ✅ USAR EN WORDPRESS
**Para**: Copiar a Elementor (widget HTML)
**Contenido**: Solo el efecto de scroll
**Uso**: Copia todo el código y pégalo en un bloque HTML de Elementor

---

## 🎯 Qué hace este efecto

**Efecto principal**: Parallax vertical + horizontal

- **Texto gigante**: Se mueve horizontalmente (de derecha a izquierda) mientras haces scroll
- **Imágenes del equipo**: Se mueven verticalmente a diferentes velocidades (parallax)
- **Hover effects**: Las imágenes pasan de blanco y negro a color al pasar el mouse
- **Altura**: 500vh (5 veces la altura del viewport) para scroll lento y suave

---

## 🚀 Cómo Usar en Elementor

### PASO 1: Copiar el código

1. Abre `roster-scroll-elementor.html`
2. Selecciona TODO (Ctrl+A)
3. Copia (Ctrl+C)

### PASO 2: Pegar en Elementor

1. Edita tu página en Elementor
2. Agrega un widget **HTML**
3. Pega el código copiado
4. Actualiza/Publica

### PASO 3: Verificar

- Haz scroll en la página
- El texto debe moverse horizontalmente
- Las imágenes deben moverse verticalmente a diferentes velocidades

---

## 🎨 Personalización

### Cambiar las imágenes y nombres del equipo

Busca en el JavaScript la sección `const ROSTER`:

```javascript
const ROSTER = [
    { 
        id: 1, 
        name: "ALEX RIVERA", // Cambia el nombre
        img: "https://tu-imagen.jpg", // Cambia la imagen
        x: "8%",    // Posición horizontal (0-100%)
        y: "15%",   // Posición vertical (0-100%)
        speed: 1.6, // Velocidad del parallax (0.5-2.5)
        z: 20       // Profundidad (5 o 20)
    },
    // ... más personas
];
```

**Parámetros explicados**:
- `name`: Nombre que aparece en la etiqueta
- `img`: URL de la imagen (puedes usar imágenes de tu WordPress)
- `x` y `y`: Posición inicial en la pantalla
- `speed`: Qué tan rápido se mueve (mayor = más rápido)
- `z`: Profundidad visual (20 = al frente con etiqueta blanca, 5 = atrás con etiqueta gris)

### Cambiar el texto de fondo

Busca esta línea en el HTML:

```html
<h2 class="archivo-black">EL EQUIPO EL EQUIPO EL EQUIPO</h2>
```

Cambia "EL EQUIPO" por tu texto. **Nota**: Repite el texto 3 veces para que funcione bien.

### Ajustar la velocidad del scroll

En el CSS, busca:

```css
.roster-section {
    height: 500vh; /* Cambia este valor */
}
```

- **300vh** = Más rápido
- **500vh** = Normal (recomendado)
- **700vh** = Más lento
- **900vh** = Muy lento

### Cambiar colores

**Fondo del efecto**:
```css
.roster-section {
    background: #000; /* Negro por defecto */
}
```

**Color del texto marquesina**:
```css
.marquee-text h2 {
    -webkit-text-stroke: 1.5px rgba(255, 255, 255, 0.2); /* Cambia el color del outline */
}
```

**Etiquetas de nombres**:
```css
.card-label-front {
    background: white;  /* Fondo de etiquetas frontales */
    color: black;       /* Texto de etiquetas frontales */
}

.card-label-back {
    background: #27272a; /* Fondo de etiquetas traseras */
    color: white;        /* Texto de etiquetas traseras */
}
```

---

## 📐 Agregar o Quitar Personas

Para **agregar** una persona, copia este bloque dentro del array `ROSTER`:

```javascript
{ 
    id: 7,  // Incrementa el ID
    name: "NUEVA PERSONA", 
    img: "https://tu-imagen.jpg", 
    x: "40%",   // Ajusta posición
    y: "30%",   // Ajusta posición
    speed: 1.3, 
    z: 20 
},
```

Para **quitar** una persona, simplemente borra su bloque del array.

**Recomendación**: 6-8 personas se ve mejor. Más de 10 puede saturar.

---

## 💡 Consejos de Diseño

### Posicionamiento (x, y)

Distribuye las personas de forma balanceada:

```
Ejemplo de distribución:
┌────────────────────────────┐
│  P1 (8%, 15%)   P3 (58%, 20%)  P6 (85%, 25%)
│
│      P2 (32%, 45%)
│
│  P4 (12%, 65%)        P5 (78%, 60%)
└────────────────────────────┘
```

### Velocidad (speed)

- **Frente (z: 20)**: Usa velocidades medias-altas (1.4-2.2)
- **Atrás (z: 5)**: Usa velocidades bajas (0.5-1.1)

Esto crea un efecto de profundidad más realista.

### Imágenes

- **Tamaño recomendado**: 600x800px (aspect ratio 3:4)
- **Formato**: JPG o WebP
- **Calidad**: Media-alta (para web)
- **Todas del mismo ratio**: Para consistencia visual

---

## 🔍 Troubleshooting

### El efecto no se mueve

**Solución 1**: Asegúrate de copiar TODO el código (CSS + HTML + JavaScript)

**Solución 2**: Verifica la consola del navegador (F12) por errores

**Solución 3**: Limpia el caché de WordPress/navegador

### Las imágenes no se ven

**Problema**: URLs incorrectas

**Solución**: Verifica que las URLs de las imágenes sean accesibles

### El texto no se ve

**Problema**: Falta la fuente Archivo Black

**Solución**: El código ya incluye la importación de Google Fonts, verifica conexión a internet

### Se ve mal en móvil

**Problema**: El efecto está optimizado para desktop

**Solución**: El CSS incluye media queries, pero considera ocultar el efecto en móvil si no se ve bien:

```css
@media (max-width: 768px) {
    .roster-section {
        display: none; /* Oculta en móvil */
    }
}
```

### Conflictos con otros efectos

**Problema**: Tienes otro efecto de scroll en la misma página

**Solución**: Cada sección tiene IDs únicos (`-elementor` suffix) para evitar conflictos

---

## 🎬 Flujo Recomendado en WordPress

```
TU PÁGINA:

[Hero Section]
[Texto/Contenido]
[EFECTO ROSTER SCROLL] ← Pegar aquí
[Más Contenido]
[Footer]
```

El efecto funciona mejor cuando tiene contenido antes y después para dar contexto al scroll.

---

## ⚙️ Archivos del Proyecto React Original

Si quieres ver el código React original:

```
React/
├── index.tsx          # Versión simple del efecto
├── App.tsx            # Versión con más secciones
├── components/
│   ├── RosterSection.tsx  # Componente principal
│   └── PersonCard.tsx     # Componente de tarjeta
```

---

## 📝 Notas Técnicas

- **Vanilla JavaScript**: No requiere librerías (no jQuery, no React)
- **Framer Motion convertido**: Se reemplazó con cálculos matemáticos puros
- **Rendimiento**: Usa `requestAnimationFrame` para animaciones suaves
- **Responsive**: Incluye breakpoints para móvil y tablet
- **Compatible**: Funciona en todos los navegadores modernos

---

## ✅ Checklist de Implementación

- [ ] Copiar código de `roster-scroll-elementor.html`
- [ ] Pegar en widget HTML de Elementor
- [ ] Cambiar nombres del equipo en el array `ROSTER`
- [ ] Cambiar imágenes (URLs de tu WordPress)
- [ ] Ajustar posiciones (x, y) si es necesario
- [ ] Cambiar texto de fondo si quieres
- [ ] Ajustar altura (`500vh`) según tu preferencia
- [ ] Publicar y probar haciendo scroll
- [ ] Verificar en móvil y ajustar si es necesario

---

**¡Listo para usar!** 🎉

Abre `roster-scroll-vanilla.html` en tu navegador para ver el efecto completo, luego copia `roster-scroll-elementor.html` a Elementor.
