# 🎵 Un Minuto Sin Tiempo - Integración Mercado Pago

## ✅ Integración Completada

Se ha implementado un **sistema de pago completo con Mercado Pago** en tu plataforma de mentorías.

---

## 📦 Archivos Creados

### Backend (PHP)
- **pago_mercado.php** → Procesa pagos con SDK Mercado Pago
- **webhook.php** → Recibe notificaciones automáticas de pagos
- **pago_exitoso.php** → Página de confirmación (pago aprobado)
- **pago_pendiente.php** → Página de pago en proceso
- **pago_fallido.php** → Página de error

### Frontend (HTML/CSS/JS)
- **pago.js** → Validación de formularios y eventos interactivos
- **pago.css** → Estilos para los formularios de pago
- **mentoria1.html** → ✅ Actualizado con integración
- **mentoria2.html** → ✅ Actualizado con integración

### Configuración
- **composer.json** → Dependencias PHP (SDK Mercado Pago)
- **.env.example** → Template para variables secretas

### Documentación
- **GUIA_RAPIDA.txt** → ⭐ COMIENZA AQUÍ - Pasos para configurar
- **MERCADO_PAGO_SETUP.md** → Guía detallada
- **INTEGRACION_RESUMEN.txt** → Resumen ejecutivo
- **ESTRUCTURA.txt** → Diagrama de la arquitectura
- **EJEMPLOS_API.php** → Ejemplos de código avanzado

---

## 🚀 Primeros Pasos (5 minutos)

### 1. Instalar dependencias
```bash
cd /Users/facu/Desktop/AgusWeb
composer install
```

### 2. Obtener credenciales de Mercado Pago
1. Ve a: https://www.mercadopago.com.ar/developers/es
2. Crea una cuenta de desarrollador
3. Copia tu **Access Token** (no el sandbox)

### 3. Configurar el token
```bash
# En tu servidor, configura variable de entorno:
export MERCADO_PAGO_ACCESS_TOKEN="tu_token_aqui"
```

### 4. ¡Listo!
Ya puede probar en: `http://localhost:8000/mentoria1.html`

---

## 💳 Cómo Funciona

```
Usuario abre mentoria1.html
    ↓
Completa formulario (nombre, email, DNI)
    ↓
Selecciona "Mercado Pago"
    ↓
Hace clic en "Finalizar compra"
    ↓
Se envía a pago_mercado.php
    ↓
PHP crea preferencia con SDK
    ↓
Usuario redirigido a MERCADO PAGO (checkout seguro)
    ↓
Usuario completa pago en sitio de MP
    ↓
↙ APROBADO          ⏳ PENDIENTE          ✗ RECHAZADO
  pago_exitoso.php    pago_pendiente.php   pago_fallido.php
  + Email automático  + Email de estado    + Permite reintentar
```

---

## 📋 Checklist de Configuración

- [ ] Ejecuté `composer install`
- [ ] Obtuve Access Token de Mercado Pago
- [ ] Configuré variable de entorno (o .env)
- [ ] Testé con tarjeta de prueba (4111 1111 1111 1111)
- [ ] Configué URLs de retorno en panel MP
- [ ] Configué Webhook en panel MP
- [ ] Subí archivos a servidor (excepto .env)
- [ ] Probé con un pago real
- [ ] Recibí notificaciones en pagos_log.txt

---

## 🔧 Archivos Clave por Caso

### Quiero cambiar el precio
→ Edita `mentoria1.html` línea ~103:
```html
<input type="hidden" name="precio" value="500.00">
```

### Quiero cambiar textos de confirmación
→ Edita `pago_exitoso.php`, `pago_pendiente.php`, `pago_fallido.php`

### Quiero cambiar estilos del formulario
→ Edita `pago.css`

### Quiero agregar validaciones adicionales
→ Edita `pago.js` (función `validarDatos()`)

### Quiero procesar el pago diferente
→ Edita `pago_mercado.php` (función de crear preferencia)

---

## 📚 Documentación Recomendada

1. **GUIA_RAPIDA.txt** ← Comienza aquí
2. MERCADO_PAGO_SETUP.md (configuración completa)
3. ESTRUCTURA.txt (diagramas técnicos)
4. EJEMPLOS_API.php (código avanzado)

---

## 🐛 Problemas Comunes

**Error: "Class not found MercadoPago"**
→ Ejecuta `composer install`

**Error: "Access Token inválido"**
→ Verifica que usas token de PRODUCCIÓN (no sandbox)

**Usuario no va a Mercado Pago**
→ Revisa que el precio sea mayor a 0

**Webhook no funciona**
→ Configura URL en panel Mercado Pago y verifica pagos_log.txt

---

## 🔒 Seguridad

✅ Token guardado en variables de entorno (NO en código)
✅ Validación en servidor (PHP) además de cliente (JS)
✅ Webhook para confirmar pagos reales
✅ Datos escapados contra inyección
✅ HTTPS obligatorio en producción

---

## 📊 Ver Pagos

Los pagos se registran en:
- **Archivo local**: `pagos_log.txt` (en el servidor)
- **Panel Mercado Pago**: https://www.mercadopago.com.ar
- **Email**: Notificaciones automáticas

---

## 🚢 Deployar a Producción

1. Copia todos los archivos al servidor
2. Ejecuta `composer install` en el servidor
3. Configura variable de entorno: `export MERCADO_PAGO_ACCESS_TOKEN="..."`
4. Actualiza URLs en panel Mercado Pago (usa HTTPS)
5. Configura Webhook en panel Mercado Pago
6. Prueba con un pago real
7. ¡Listo!

---

## 💡 Próximas Mejoras (Opcionales)

- Base de datos para guardar pagos
- Panel de administración
- Suscripciones mensuales
- Descuentos con códigos
- Más mentorías (mentoria3.html)
- Facturación automática

---

## 📞 Soporte

- Email: unminutosintiempo@gmail.com
- Instagram: @unminutosintiempo
- Documentación Mercado Pago: https://www.mercadopago.com.ar/developers/es

---

## 📜 Resumen Técnico

| Aspecto | Detalles |
|--------|----------|
| **Framework** | Vanilla PHP + JavaScript |
| **SDK** | mercadopago/sdk ^2.0 |
| **Métodos de pago** | Mercado Pago (redirige a checkout seguro) |
| **Base de datos** | Archivo txt (puede mejorar a SQL) |
| **Email** | PHP mail() nativa |
| **Seguridad** | Validación servidor + HTTPS |
| **Logs** | pagos_log.txt |

---

## 🎯 Siguiente Paso

👉 **LEE:** `GUIA_RAPIDA.txt`

Tiene todo lo que necesitas saber para empezar.

---

**Creado:** 14 de noviembre de 2025
**Proyecto:** Un minuto sin tiempo - Mentorías de canto
**Versión:** 1.0 - Mercado Pago Integration Complete ✅
