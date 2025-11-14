/**
 * Manejo de formularios de pago
 * Incluye validaciones y comportamiento dinámico
 */

document.addEventListener('DOMContentLoaded', function() {
  const formulario = document.getElementById('formulario-pago');
  const metodoPagoRadios = document.querySelectorAll('input[name="pago"]');
  const camposTarjeta = document.getElementById('campos-tarjeta');
  const camposTransferencia = document.getElementById('campos-transferencia');

  if (!formulario) return;

  // Mostrar/ocultar campos según método de pago
  metodoPagoRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      cambiarMetodoPago(this.value);
    });
  });

  // Validar y procesar formulario
  formulario.addEventListener('submit', function(e) {
    const metodoPago = document.querySelector('input[name="pago"]:checked').value;
    
    if (!validarDatos()) {
      e.preventDefault();
      return false;
    }

    if (metodoPago === 'mercado') {
      // Permitir envío a pago_mercado.php
      console.log('Enviando a Mercado Pago...');
      return true;
    } else if (metodoPago === 'tarjeta') {
      e.preventDefault();
      mostrarMensaje('Procesamiento de tarjeta en desarrollo', 'info');
      return false;
    } else if (metodoPago === 'transferencia') {
      e.preventDefault();
      mostrarMensajeTransferencia();
      return false;
    }
  });

  /**
   * Cambia el método de pago y muestra campos correspondientes
   */
  function cambiarMetodoPago(metodo) {
    // Ocultar todos los campos adicionales primero
    if (camposTarjeta) {
      camposTarjeta.style.display = 'none';
    }
    if (camposTransferencia) {
      camposTransferencia.style.display = 'none';
    }

    // Mostrar campos según el método
    if (metodo === 'tarjeta') {
      if (camposTarjeta) {
        camposTarjeta.style.display = 'block';
      }
      setearValidacionTarjeta();
    } else if (metodo === 'transferencia') {
      if (camposTransferencia) {
        camposTransferencia.style.display = 'block';
      }
      console.log('Método: Transferencia Bancaria');
    } else if (metodo === 'mercado') {
      console.log('Método: Mercado Pago');
    }
  }

  /**
   * Valida los datos del formulario
   */
  function validarDatos() {
    const nombre = document.getElementById('nombre').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const dni = document.getElementById('dni').value.trim();

    if (!nombre || nombre.length < 3) {
      mostrarMensaje('Por favor ingresa un nombre válido', 'error');
      return false;
    }

    if (!correo || !validarEmail(correo)) {
      mostrarMensaje('Por favor ingresa un correo válido', 'error');
      return false;
    }

    if (!dni || !validarDNI(dni)) {
      mostrarMensaje('Por favor ingresa un DNI válido', 'error');
      return false;
    }

    return true;
  }

  /**
   * Valida formato de email
   */
  function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  }

  /**
   * Valida DNI argentino (básico)
   */
  function validarDNI(dni) {
    const soloNumeros = dni.replace(/\D/g, '');
    return soloNumeros.length >= 7 && soloNumeros.length <= 8;
  }

  /**
   * Configura validación de tarjeta
   */
  function setearValidacionTarjeta() {
    const inputTarjeta = document.getElementById('tarjeta');
    const inputVencimiento = document.getElementById('vencimiento');
    const inputCVV = document.getElementById('cvv');

    if (inputTarjeta) {
      inputTarjeta.addEventListener('input', function(e) {
        // Solo números, máximo 19
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 19);
      });
    }

    if (inputVencimiento) {
      inputVencimiento.addEventListener('input', function(e) {
        // Formato MM/AA
        let valor = e.target.value.replace(/\D/g, '');
        if (valor.length >= 2) {
          valor = valor.slice(0, 2) + '/' + valor.slice(2, 4);
        }
        e.target.value = valor;
      });
    }

    if (inputCVV) {
      inputCVV.addEventListener('input', function(e) {
        // Solo números, máximo 4
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
      });
    }
  }

  /**
   * Muestra mensaje de confirmación para transferencia
   */
  function mostrarMensajeTransferencia() {
    const nombre = document.getElementById('nombre').value;
    const correo = document.getElementById('correo').value;
    const dni = document.getElementById('dni').value;
    
    const html = `
      <div class="confirmacion-transferencia">
        <h4>✅ Datos recibidos correctamente</h4>
        <p><strong>Nombre:</strong> ${htmlEscape(nombre)}</p>
        <p><strong>Email:</strong> ${htmlEscape(correo)}</p>
        <p style="color: #666; margin-top: 15px;">
          ⏳ Ya tienes los datos de la cuenta. Ahora:
        </p>
        <ol style="color: #333;">
          <li>Realiza la transferencia desde tu banco</li>
          <li>Guarda el comprobante</li>
          <li>Usa los botones de WhatsApp o Email abajo para enviarlo</li>
          <li>¡Nosotros te contactaremos en 24hs!</li>
        </ol>
      </div>
    `;

    mostrarMensaje(html, 'success', true);
  }

  /**
   * Escapa caracteres HTML
   */
  function htmlEscape(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
  }

  /**
   * Muestra un mensaje al usuario
   */
  function mostrarMensaje(texto, tipo = 'info', esHTML = false) {
    // Buscar o crear contenedor de mensajes
    let contenedor = document.querySelector('.mensaje-alerta');
    
    if (!contenedor) {
      contenedor = document.createElement('div');
      contenedor.className = 'mensaje-alerta';
      formulario.parentElement.insertBefore(contenedor, formulario);
    }

    // Limpiar clase anterior
    contenedor.className = `mensaje-alerta mensaje-${tipo}`;

    // Establecer contenido
    if (esHTML) {
      contenedor.innerHTML = texto;
    } else {
      contenedor.textContent = texto;
    }

    // Auto-ocultarse en 5 segundos (solo para mensajes simples)
    if (!esHTML && tipo === 'info') {
      setTimeout(() => {
        contenedor.style.display = 'none';
      }, 5000);
    }

    // Scroll a la vista
    contenedor.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  // Inicializar estado
  cambiarMetodoPago('tarjeta');
});

/**
 * Copia texto al portapapeles
 */
function copiarAlPortapapeles(texto) {
  // Crear elemento temporal
  const temp = document.createElement('textarea');
  temp.value = texto;
  temp.style.position = 'fixed';
  temp.style.opacity = '0';
  
  document.body.appendChild(temp);
  temp.select();
  
  try {
    document.execCommand('copy');
    
    // Mostrar feedback visual
    showToast('✓ Copiado al portapapeles!');
  } catch (err) {
    console.error('Error al copiar:', err);
    showToast('Error al copiar', 'error');
  }
  
  document.body.removeChild(temp);
}

/**
 * Muestra notificación tipo toast
 */
function showToast(mensaje, tipo = 'success') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${tipo}`;
  toast.textContent = mensaje;
  
  document.body.appendChild(toast);
  
  // Animar entrada
  setTimeout(() => {
    toast.style.opacity = '1';
  }, 10);
  
  // Remover después de 2 segundos
  setTimeout(() => {
    toast.style.opacity = '0';
    setTimeout(() => {
      document.body.removeChild(toast);
    }, 300);
  }, 2000);
}
