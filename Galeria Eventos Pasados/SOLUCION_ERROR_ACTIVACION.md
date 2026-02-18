# 🔧 Guía de Solución: Error Fatal al Activar "Galería Eventos Pasados"

## ❌ Problema

Al intentar activar el plugin "Galería Eventos Pasados", WordPress muestra:
```
El plugin no ha podido activarse porque ha provocado un error fatal.
```

## 🔍 Causa

WordPress tiene en caché la información del plugin con el nombre antiguo ("Nuevos eventos galeria") y al renombrar la carpeta, se produce un conflicto.

## ✅ Solución

### Opción 1: Desactivar desde la Base de Datos (Recomendado)

1. **Accede a phpMyAdmin** o tu gestor de base de datos
2. **Busca la tabla** `wp_options` (el prefijo puede variar)
3. **Busca la opción** `active_plugins`
4. **Edita el valor** y elimina cualquier referencia a:
   - `Nuevos eventos galeria/galeria-eventos-parallax.php`
   - `galeria-eventos-parallax`
5. **Guarda los cambios**
6. **Vuelve a WordPress** e intenta activar el plugin

### Opción 2: Reinstalación Limpia

1. **Elimina la carpeta** `Galeria Eventos Pasados` del directorio de plugins
2. **Descomprime** el archivo `Galeria Eventos Pasados.zip` en el directorio de plugins
3. **Activa el plugin** desde WordPress

### Opción 3: Usar WP-CLI (Si está disponible)

```bash
wp plugin deactivate galeria-eventos-parallax
wp plugin delete galeria-eventos-parallax
wp plugin activate galeria-eventos-pasados
```

### Opción 4: Desactivar Todos los Plugins

1. **Renombra temporalmente** la carpeta `plugins` a `plugins_backup`
2. **Crea una nueva carpeta** llamada `plugins`
3. **Accede a WordPress** (todos los plugins estarán desactivados)
4. **Restaura** la carpeta `plugins` original
5. **Activa solo** "Galería Eventos Pasados"

## 🔄 Pasos Posteriores

Una vez resuelto el error:

1. ✅ Verifica que "Carrusel de Eventos" esté activado primero
2. ✅ Activa "Galería Eventos Pasados"
3. ✅ Ve a Elementor y busca el widget "Galería Eventos Parallax"
4. ✅ Configura el widget según tus necesidades

## 📝 Notas Importantes

- **Orden de instalación**: Primero "Carrusel de Eventos", luego "Galería Eventos Pasados"
- **Dependencia**: El plugin "Carrusel de Eventos" debe estar activo (crea el CPT)
- **Elementor**: Debe estar instalado y activado

## 🆘 Si el Error Persiste

Si después de seguir estos pasos el error continúa, por favor proporciona:

1. **Mensaje de error completo** (revisa en: `wp-content/debug.log`)
2. **Versión de WordPress**
3. **Versión de Elementor**
4. **Versión de PHP**

Para habilitar el debug log, agrega esto a `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Luego intenta activar el plugin nuevamente y revisa el archivo `wp-content/debug.log`.
