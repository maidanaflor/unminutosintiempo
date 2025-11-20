// Función para cargar MercadoPago SDK desde CDN
function loadMercadoPagoSDK() {
  return new Promise((resolve, reject) => {
    // Si ya está cargado y es una función, resolver inmediatamente
    if (window.MercadoPago && typeof window.MercadoPago === "function") {
      resolve(window.MercadoPago);
      return;
    }

    // Crear script tag para cargar desde CDN
    const script = document.createElement("script");
    script.src = "https://sdk.mercadopago.com/js/v2";

    script.onload = () => {
      // Esperar un poco para que el SDK se inicialice completamente
      setTimeout(() => {
        if (window.MercadoPago && typeof window.MercadoPago === "function") {
          resolve(window.MercadoPago);
        } else {
          reject(new Error("MercadoPago SDK no se inicializó correctamente"));
        }
      }, 100);
    };

    script.onerror = () =>
      reject(new Error("Error al cargar MercadoPago SDK desde CDN"));
    document.head.appendChild(script);
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const formulario = document.getElementById("formulario-pago");
  const metodoPagoRadios = document.querySelectorAll('input[name="pago"]');
  const camposTarjeta = document.getElementById("campos-tarjeta");
  const camposTransferencia = document.getElementById("campos-transferencia");
  const camposMercado = document.getElementById("campos-mercado");

  if (!formulario) return;

  // Mostrar/ocultar campos según método de pago
  metodoPagoRadios.forEach((radio) => {
    radio.addEventListener("change", async function () {
      await cambiarMetodoPago(this.value);
    });
  });

  /**
   * Cambia el método de pago y muestra campos correspondientes
   */
  async function cambiarMetodoPago(metodo) {
    // Ocultar todos los campos adicionales primero
    if (camposTarjeta) {
      camposTarjeta.style.display = "none";
    }
    if (camposTransferencia) {
      camposTransferencia.style.display = "none";
    }
    if (camposMercado) {
      camposMercado.style.display = "none";
    }

    // Mostrar campos según el método
    if (metodo === "tarjeta") {
      if (camposTarjeta) {
        camposTarjeta.style.display = "block";
      }
      setearValidacionTarjeta();
    } else if (metodo === "transferencia") {
      if (camposTransferencia) {
        camposTransferencia.style.display = "block";
      }
      console.log("Método: Transferencia Bancaria");
    } else if (metodo === "mercado") {
      console.log("Método: Mercado Pago");

      // Mostrar el contenedor de MercadoPago
      if (camposMercado) {
        camposMercado.style.display = "block";
      }

      try {
        // Cargar MercadoPago SDK desde CDN y esperar a que termine
        await loadMercadoPagoSDK();

        // Usar configuración estática
        const publicKey =
          window.AppConfig?.mercadoPago?.publicKey || "TEST-fallback-key";

        // Verificar que MercadoPago esté disponible y sea una función
        if (typeof window.MercadoPago !== "function") {
          throw new Error("MercadoPago SDK no se cargó correctamente");
        }

        // Inicializar MercadoPago con la API correcta
        const mp = new window.MercadoPago(publicKey);

        console.log(
          "MercadoPago inicializado correctamente con clave:",
          publicKey.substring(0, 8) + "..."
        );
        handleBrickBuilder(mp);
      } catch (error) {
        console.error("Error al cargar MercadoPago:", error);
        mostrarMensaje(
          "Error al cargar MercadoPago. Intenta de nuevo.",
          "error"
        );
      }
    }
  }

  // Variable para mantener referencia al controlador del brick
  let cardPaymentBrickController = null;

  function handleBrickBuilder(mp) {
    const bricksBuilder = mp.bricks();

    const renderCardPaymentBrick = async (bricksBuilder) => {
      // Limpiar brick anterior si existe
      if (cardPaymentBrickController) {
        try {
          cardPaymentBrickController.unmount();
        } catch (e) {
          console.log("No había brick anterior para desmontar");
        }
      }

      const settings = {
        initialization: {
          amount: 10000, // Valor del pago a procesar (en centavos, $100.00)
        },
        customization: {
          visual: {
            style: {
              theme: "default", // 'default' | 'dark' | 'bootstrap' | 'flat'
            },
          },
        },
        callbacks: {
          onSubmit: (formData) => {
            return new Promise((resolve, reject) => {
              console.log("Enviando pago a servidor:", formData);
              
              mostrarMensaje("Procesando pago...", "info");
              
              // Enviar al endpoint PHP
              fetch("process_payment.php", {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
              })
              .then(async (response) => {
                const contentType = response.headers.get("content-type");
                
                if (!response.ok) {
                  throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                // Verificar si la respuesta es JSON
                if (contentType && contentType.indexOf("application/json") !== -1) {
                  return await response.json();
                } else {
                  // Si no es JSON, obtener como texto para debug
                  const text = await response.text();
                  console.warn("Respuesta no es JSON:", text);
                  throw new Error("Respuesta inválida del servidor");
                }
              })
              .then((result) => {
                console.log("Respuesta del pago:", result);
                
                // Manejar diferentes estados
                switch(result.status) {
                  case 'approved':
                    mostrarMensaje("¡Pago aprobado exitosamente! ID: " + result.payment_id, "success");
                    resolve();
                    break;
                    
                  case 'pending':
                    mostrarMensaje("Pago pendiente de aprobación. ID: " + result.payment_id, "info");
                    resolve();
                    break;
                    
                  case 'in_process':
                    mostrarMensaje("Pago en proceso de verificación. ID: " + result.payment_id, "info");
                    resolve();
                    break;
                    
                  case 'rejected':
                    mostrarMensaje("Pago rechazado: " + result.message, "error");
                    reject(new Error(result.error || "Pago rechazado"));
                    break;
                    
                  default:
                    mostrarMensaje("Error: " + (result.error || result.message), "error");
                    reject(new Error(result.error || "Error desconocido"));
                }
              })
              .catch((error) => {
                console.error("Error procesando pago:", error);
                mostrarMensaje(`Error al procesar el pago: ${error.message}`, "error");
                reject(error);
              });
            });
          },
          onReady: () => {
            console.log("MercadoPago Brick está listo");
          },
          onError: (error) => {
            console.error("Error en MercadoPago Brick:", error);
            mostrarMensaje("Error en el procesador de pagos", "error");
          },
        },
      };

      try {
        cardPaymentBrickController = await bricksBuilder.create(
          "cardPayment",
          "cardPaymentBrick_container",
          settings
        );
        console.log("Brick de pago creado exitosamente");
      } catch (error) {
        console.error("Error creando el brick:", error);
        mostrarMensaje("Error al cargar el formulario de pago", "error");
      }
    };

    renderCardPaymentBrick(bricksBuilder);
  }

  /**
   * Valida los datos del formulario
   */
  function validarDatos() {
    const nombre = document.getElementById("nombre").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const dni = document.getElementById("dni").value.trim();

    if (!nombre || nombre.length < 3) {
      mostrarMensaje("Por favor ingresa un nombre válido", "error");
      return false;
    }

    if (!correo || !validarEmail(correo)) {
      mostrarMensaje("Por favor ingresa un correo válido", "error");
      return false;
    }

    if (!dni || !validarDNI(dni)) {
      mostrarMensaje("Por favor ingresa un DNI válido", "error");
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
    const soloNumeros = dni.replace(/\D/g, "");
    return soloNumeros.length >= 7 && soloNumeros.length <= 8;
  }

  /**
   * Configura validación de tarjeta
   */
  function setearValidacionTarjeta() {
    const inputTarjeta = document.getElementById("tarjeta");
    const inputVencimiento = document.getElementById("vencimiento");
    const inputCVV = document.getElementById("cvv");

    if (inputTarjeta) {
      inputTarjeta.addEventListener("input", function (e) {
        // Solo números, máximo 19
        e.target.value = e.target.value.replace(/\D/g, "").slice(0, 19);
      });
    }

    if (inputVencimiento) {
      inputVencimiento.addEventListener("input", function (e) {
        // Formato MM/AA
        let valor = e.target.value.replace(/\D/g, "");
        if (valor.length >= 2) {
          valor = valor.slice(0, 2) + "/" + valor.slice(2, 4);
        }
        e.target.value = valor;
      });
    }

    if (inputCVV) {
      inputCVV.addEventListener("input", function (e) {
        // Solo números, máximo 4
        e.target.value = e.target.value.replace(/\D/g, "").slice(0, 4);
      });
    }
  }

  /**
   * Muestra mensaje de confirmación para transferencia
   */
  function mostrarMensajeTransferencia() {
    const nombre = document.getElementById("nombre").value;
    const correo = document.getElementById("correo").value;
    const dni = document.getElementById("dni").value;

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

    mostrarMensaje(html, "success", true);
  }

  /**
   * Escapa caracteres HTML
   */
  function htmlEscape(text) {
    const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    };
    return text.replace(/[&<>"']/g, (m) => map[m]);
  }

  /**
   * Muestra un mensaje al usuario
   */
  function mostrarMensaje(texto, tipo = "info", esHTML = false) {
    // Buscar o crear contenedor de mensajes
    let contenedor = document.querySelector(".mensaje-alerta");

    if (!contenedor) {
      contenedor = document.createElement("div");
      contenedor.className = "mensaje-alerta";
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
    if (!esHTML && tipo === "info") {
      setTimeout(() => {
        contenedor.style.display = "none";
      }, 5000);
    }

    // Scroll a la vista
    contenedor.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }

  // Inicializar estado
  cambiarMetodoPago("tarjeta").catch(console.error);
});

/**
 * Copia texto al portapapeles
 */
function copiarAlPortapapeles(texto) {
  // Crear elemento temporal
  const temp = document.createElement("textarea");
  temp.value = texto;
  temp.style.position = "fixed";
  temp.style.opacity = "0";

  document.body.appendChild(temp);
  temp.select();

  try {
    document.execCommand("copy");

    // Mostrar feedback visual
    showToast("✓ Copiado al portapapeles!");
  } catch (err) {
    console.error("Error al copiar:", err);
    showToast("Error al copiar", "error");
  }

  document.body.removeChild(temp);
}

/**
 * Muestra notificación tipo toast
 */
function showToast(mensaje, tipo = "success") {
  const toast = document.createElement("div");
  toast.className = `toast toast-${tipo}`;
  toast.textContent = mensaje;

  document.body.appendChild(toast);

  // Animar entrada
  setTimeout(() => {
    toast.style.opacity = "1";
  }, 10);

  // Remover después de 2 segundos
  setTimeout(() => {
    toast.style.opacity = "0";
    setTimeout(() => {
      document.body.removeChild(toast);
    }, 300);
  }, 2000);
}


