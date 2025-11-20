/**
 * Configuración de la aplicación - EJEMPLO PARA PRODUCCIÓN
 * 
 * INSTRUCCIONES:
 * 1. Copia este archivo como config.js
 * 2. Cambia "TEST-your-public-key-here" por tu clave pública real de MercadoPago
 * 3. Cambia environment a "production" cuando vayas a producción
 * 
 * ⚠️  IMPORTANTE: Solo coloca aquí datos PÚBLICOS
 *     NUNCA pongas tu ACCESS_TOKEN (token privado) aquí
 */
window.AppConfig = {
  // MercadoPago - Solo claves públicas
  mercadoPago: {
    publicKey: "TEST-your-public-key-here", // 🔑 CAMBIAR: Tu clave pública real
    environment: "sandbox" // 🔄 CAMBIAR: "production" para producción
  },
  
  // Configuración de la app
  environment: "production", // "development" o "production"
  debug: false // true para mostrar más logs
};