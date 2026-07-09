# Contexto del Proyecto — Plugin “Centro de Recursos” para WordPress + Elementor

## Objetivo General

Crear un plugin personalizado para WordPress que agregue una nueva sección administrativa llamada:

```txt
Centro de Recursos
```

La intención es que el usuario pueda publicar contenido tipo:

- Webinars
- Casos de uso
- Descargables

manteniendo la facilidad de uso de Gutenberg y sin afectar:

- blog actual
- noticias
- eventos
- templates existentes
- featured images globales
- loops existentes de Elementor

La solución debe ser aislada y escalable.

---

# Requerimientos principales

## 1. Nuevo CPT

Crear un Custom Post Type:

```txt
centro_recursos
```

Label visible:

```txt
Centro de Recursos
```

Debe aparecer en el menú lateral de WordPress.

---

# 2. Mantener Gutenberg

NO reemplazar Gutenberg.

El usuario debe poder:

- crear posts normalmente
- usar bloques
- insertar imágenes
- insertar texto
- usar embeds
- usar galerías
- etc.

La experiencia debe sentirse como una entrada normal de WordPress.

---

# 3. Taxonomía obligatoria

Crear taxonomía personalizada:

```txt
tipo_recurso
```

Con categorías hijas:

- Webinars
- Casos de uso
- Descargables

Y una categoría padre:

```txt
Soluciones de Oficina
```

Estructura:

```txt
Soluciones de Oficina
 ├── Webinars
 ├── Casos de uso
 └── Descargables
```

Idealmente:

- el plugin crea automáticamente estas categorías
- el usuario no necesita configurarlas manualmente

---

# 4. Media principal dinámica

El recurso debe permitir definir una media principal.

El usuario debe poder seleccionar:

```txt
Imagen
Video
Descargable
```

Dependiendo del tipo:

## Imagen

Usar featured image normal.

## Video

Permitir:

- YouTube
- Vimeo
- embed URL

Idealmente mediante:

```txt
Campo URL simple
```

Ejemplo:

```txt
https://youtube.com/...
```

## Descargable

Aquí cambia la lógica.

Los descargables NO deberían comportarse igual que webinars.

Se recomienda:

- subir archivo PDF
- portada/thumbnail
- botón descargar
- opcionalmente preview

Campos sugeridos:

```txt
Archivo PDF
Imagen portada
Texto botón
```

---

# 5. Comportamiento esperado en frontend

## Loop/Grid Elementor

Si el recurso es:

### Video

Mostrar:

- thumbnail del video
- ícono play

### Imagen / Caso de uso

Mostrar:

- featured image

### Descargable

Mostrar:

- portada
- ícono descarga
- CTA descargar

---

# 6. Single Resource

## Video

Mostrar video arriba como hero principal.

## Caso de uso

Mostrar imagen destacada arriba.

## Descargable

Mostrar:

- portada
- botón descarga
- metadata del archivo

---

# 7. Elementor compatibility

El sistema debe ser compatible con:

- Loop Grid
- Loop Item
- Single Template
- Dynamic Tags

Idealmente mediante:

- shortcodes
- helper functions
- custom dynamic tags opcionales

---

# 8. No afectar contenido existente

IMPORTANTE:

NO modificar:

- posts normales
- blog
- eventos
- noticias
- featured image global
- templates actuales

Todo debe ser independiente.

---

# Recomendación de arquitectura

## Estructura recomendada

### CPT

```txt
centro_recursos
```

### Taxonomía

```txt
tipo_recurso
```

### Meta fields

```txt
_bg_resource_type
_bg_video_url
_bg_download_file
_bg_download_cover
_bg_cta_text
```

---

# UX sugerida para el usuario

Al crear nuevo recurso:

## Selector principal

```txt
Tipo de recurso:

( ) Webinar
( ) Caso de uso
( ) Descargable
```

Luego mostrar campos condicionales.

---

# Para webinars

Mostrar:

```txt
Video URL
```

---

# Para descargables

Mostrar:

```txt
Archivo PDF
Imagen portada
Texto botón
```

---

# Recomendación importante

NO parsear bloques Gutenberg automáticamente.

NO intentar detectar embeds dentro del contenido.

Es más estable usar:

- meta fields
- lógica explícita
- fallbacks controlados

---

# Recomendación visual

## En cards

Videos:

- overlay play icon
- thumbnail limpia

Descargables:

- ícono PDF
- badge descargar

Casos de uso:

- estilo editorial normal

---

# Recomendación técnica adicional

Crear helper central:

```php
bg_get_resource_media()
```

Que devuelva:

- iframe video
- featured image
- portada descargable

Dependiendo del tipo.

---

# Shortcodes sugeridos

```txt
[bg_resource_media]
[bg_resource_download]
```

---

# Conclusión

La solución recomendada es:

- Plugin personalizado
- CPT independiente
- Gutenberg intacto
- Elementor compatible
- Media dinámica por tipo
- Arquitectura escalable
- Sin afectar contenido existente

Y específicamente:

## Descargables

DEBEN manejarse distinto a webinars.

Porque funcionalmente son:

- assets
- PDFs
- recursos descargables

No contenido audiovisual.

Por eso requieren:

- archivo
- portada
- CTA
- lógica distinta de frontend
- posiblemente tracking futuro

