# Guía de Deployment en Hostinger Websites

## 📋 Pasos para subir tu proyecto:

### 1. **Preparar archivos localmente:**
```bash
# Instalar dependencias
composer install --no-dev --optimize-autoloader

# Crear archivo ZIP con los archivos necesarios
zip -r proyecto.zip \
  src/ \
  vendor/ \
  *.php \
  *.html \
  *.css \
  *.js \
  img/ \
  composer.json \
  composer.lock \
  .htaccess_hostinger
```

### 2. **En el panel de Hostinger:**
- Ve a "Administrador de archivos"
- Sube el ZIP a `public_html/`
- Extrae los archivos
- Renombra `.htaccess_hostinger` a `.htaccess`

### 3. **Configurar PHP:**
- En el panel de Hostinger, ve a "PHP Configuration"
- Selecciona PHP 8.1 o superior
- Activa las extensiones: `curl`, `json`, `mbstring`, `openssl`

### 4. **Configurar variables de entorno:**
```php
// En config_mercadopago.php, usar:
'public_key' => getenv('MERCADOPAGO_PUBLIC_KEY') ?: 'fallback_key',
'access_token' => getenv('MERCADOPAGO_ACCESS_TOKEN') ?: 'fallback_token',
```

### 5. **Configurar credenciales de MercadoPago:**
- Reemplaza las credenciales de prueba por las reales
- Cambia `environment` a `production` cuando esté listo

### 6. **Verificar permisos:**
```bash
# En el File Manager de Hostinger:
chmod 755 public_html/
chmod 644 public_html/*.php
chmod 644 public_html/*.html
chmod 755 public_html/vendor/
chmod 755 public_html/src/
```

## 🔒 Seguridad en producción:

### Variables de entorno recomendadas:
```
MERCADOPAGO_PUBLIC_KEY=APP_USR-xxxxxxxxx
MERCADOPAGO_ACCESS_TOKEN=APP_USR-xxxxxxxxx  
ENVIRONMENT=production
```

### Archivos a proteger:
- `config_mercadopago.php`
- `composer.json`
- `vendor/` (solo acceso interno)
- `src/` (solo acceso interno)
- `*.log`

## 🧪 Testing en Hostinger:

1. **Subir con credenciales de sandbox primero**
2. **Probar el formulario completo**
3. **Verificar logs en el File Manager**
4. **Cambiar a producción solo cuando todo funcione**

## 📞 URLs finales:
- Formulario: `https://tudominio.com/mentoria1.html`
- API: `https://tudominio.com/process_payment_oop.php`
- Logs: Accesibles desde File Manager de Hostinger