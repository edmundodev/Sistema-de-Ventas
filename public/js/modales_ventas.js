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


// Modales y cierre de modal de plan basico
document.addEventListener('DOMContentLoaded', (event) => {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });
});

function abrirModal(planId, nombrePlan, precio) {
    document.getElementById('modalPlan').classList.remove('hidden');
    document.getElementById('modalPlanTitulo').innerText = `Plan ${nombrePlan}`;
    document.getElementById('modalPlanPrecio').innerText = `Precio: $${precio} / mes`;
    document.getElementById('plan_id_input').value = planId;
}

function cerrarModal() {
    document.getElementById('modalPlan').classList.add('hidden');
}