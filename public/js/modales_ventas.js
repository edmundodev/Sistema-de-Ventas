// Mostrar campos según método de pago
document.querySelectorAll('input[name="metodo_pago"]').forEach(radio => {
    radio.addEventListener('change', function () {
        const tarjeta = document.getElementById('campos-tarjeta');
        const transferencia = document.getElementById('info-transferencia');

        if (this.value === 'tarjeta') {
            tarjeta.classList.remove('hidden');
            transferencia.classList.add('hidden');
        } else if (this.value === 'transferencia') {
            tarjeta.classList.add('hidden');
            transferencia.classList.remove('hidden');
        }
    });
});

// Mejora visual de selección
document.querySelectorAll('input[name="metodo_pago"]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('input[name="metodo_pago"]').forEach(r => {
            r.parentElement.classList.remove('border-blue-500', 'bg-blue-50');
        });
        this.parentElement.classList.add('border-blue-500', 'bg-blue-50');
    });
});

// Función para abrir modal con datos dinámicos
function abrirModal(planId, nombrePlan, precio) {
    document.getElementById('modalPlan').classList.remove('hidden');
    document.getElementById('modalPlanTitulo').innerText = `Plan ${nombrePlan}`;
    document.getElementById('modalPlanPrecio').innerText = `Precio: $${precio} / mes`;
    document.getElementById('plan_id_input').value = planId;

    // Opcional: resetear formulario y estados de pago al abrir modal
    const form = document.getElementById('formSuscripcion');
    form.reset();
    document.getElementById('campos-tarjeta').classList.add('hidden');
    document.getElementById('info-transferencia').classList.add('hidden');
}

function cerrarModal() {
    document.getElementById('modalPlan').classList.add('hidden');
}




// JS  para la compra de un plan basico 
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({ duration: 800, easing: 'ease-in-out', once: true });

    // Envío del formulario
    document.getElementById('formSuscripcion').addEventListener('submit', async e => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        try {
            const res = await fetch('../bd/procesar_pago.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.status === 'success') {
                alert('✅ Compra realizada con éxito');
                cerrarModal();
                form.reset();
                location.reload();
            } else {
                alert('❌ Error: ' + data.message);
            }
        } catch (error) {
            alert('❌ Error de red o servidor');
        }
    });
});

function abrirModal(planId, nombrePlan, precio) {
    document.getElementById('modalPlan').classList.remove('hidden');
    document.getElementById('modalPlanTitulo').innerText = `Plan ${nombrePlan}`;
    document.getElementById('modalPlanPrecio').innerText = `Precio: $${precio} / mes`;
    document.getElementById('plan_id_input').value = planId;

    // Reset formulario
    const form = document.getElementById('formSuscripcion');
    form.reset();
}

function cerrarModal() {
    document.getElementById('modalPlan').classList.add('hidden');
}
