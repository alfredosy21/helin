/**
 * Solicitud - Validación de formulario, envío AJAX, selects dinámicos
 *
 * Requiere que la vista inyecte:
 *   window.HelinSolicitud = {
 *     pickups: {},  // datos de pickup por estado
 *     cities: {},   // ciudades agrupadas por código de estado
 *   }
 */
(function () {
    /**
     * Form validation for submit button
     */
    function validateForm() {
        const form = document.getElementById('solicitud-form');
        const submitBtn = document.getElementById('submit-btn');
        if (!form || !submitBtn) return;

        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (field.type === 'checkbox' ? !field.checked : !field.value.trim()) {
                isValid = false;
            }
        });

        // Check if cart has items
        const cartItems = Cart.getItems();
        if (cartItems.length === 0) {
            isValid = false;
        }

        if (isValid) {
            submitBtn.disabled = false;
            submitBtn.className = 'w-full bg-turquesa hover:bg-turquesa-dark text-white font-bold text-sm py-3 rounded-full uppercase transition-colors';
        } else {
            submitBtn.disabled = true;
            submitBtn.className = 'w-full bg-gray-400 text-white font-bold text-sm py-3 rounded-full uppercase transition-colors cursor-not-allowed';
        }
    }

    // Initialize validation
    document.addEventListener('DOMContentLoaded', function() {
        // Validate on load
        validateForm();

        // Validate on input change
        const form = document.getElementById('solicitud-form');
        if (form) {
            form.addEventListener('input', validateForm);
            form.addEventListener('change', validateForm);
        }

        // Validate on cart updates
        document.addEventListener('cart:updated', validateForm);
    });

    // Form submission with AJAX
    document.getElementById('solicitud-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = document.getElementById('submit-btn');

        // Enable all disabled fields temporarily for submission
        const allDisabledFields = form.querySelectorAll('[disabled]');
        allDisabledFields.forEach(field => {
            field.disabled = false;
            field.dataset.wasDisabled = 'true';
        });

        const formData = new FormData(form);

        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enviando...';
        submitBtn.className = 'w-full bg-gray-400 text-white font-bold text-sm py-3 rounded-full uppercase cursor-not-allowed';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to success page
                window.location.href = data.redirect_url;
            } else {
                // Show error message
                alert('Error: ' + (data.message || 'Hubo un error al procesar tu solicitud'));

                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Enviar Solicitud Comercial';
                submitBtn.className = 'w-full bg-turquesa hover:bg-turquesa-dark text-white font-bold text-sm py-3 rounded-full uppercase transition-colors';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión. Por favor, intenta nuevamente.');

            // Re-enable button
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Enviar Solicitud Comercial';
            submitBtn.className = 'w-full bg-turquesa hover:bg-turquesa-dark text-white font-bold text-sm py-3 rounded-full uppercase transition-colors';
        })
        .finally(() => {
            // Re-disable fields that were originally disabled
            const originallyDisabledFields = form.querySelectorAll('[data-was-disabled="true"]');
            originallyDisabledFields.forEach(field => {
                field.disabled = true;
                delete field.dataset.wasDisabled;
            });
        });
    });

    const envioInput = document.getElementById('envio-input');
    const otherDeliveryField = document.getElementById('other-delivery-company-field');
    const otherDeliveryCompanyInput = otherDeliveryField?.querySelector('input');

    function updateOtherDeliveryField() {
        const isOtherDeliverySelected = envioInput?.value === 'otra-empresa';
        otherDeliveryField?.classList.toggle('hidden', !isOtherDeliverySelected);

        if (otherDeliveryCompanyInput) {
            otherDeliveryCompanyInput.disabled = !isOtherDeliverySelected;
            otherDeliveryCompanyInput.required = isOtherDeliverySelected;
        }
    }

    envioInput?.addEventListener('change', updateOtherDeliveryField);
    updateOtherDeliveryField();

    const shippingDataBlock = document.getElementById('shipping-data-block');
    const paymentMethodsBlock = document.getElementById('payment-methods-block');
    const pickupInfoBlock = document.getElementById('pickup-info');
    const shippingFields = document.querySelectorAll('.shipping-field');

    const pickupData = (window.HelinSolicitud && window.HelinSolicitud.pickups) || {};
    const estadoInput = document.getElementById('estado-input');
    const pickupZoneContent = document.getElementById('pickup-zone-content');
    const pickupZoneEmpty = document.getElementById('pickup-zone-empty');
    const pickupZoneAddress = document.getElementById('pickup-zone-address');

    function updatePickupInfo() {
        const stateCode = estadoInput?.value;
        const info = stateCode ? pickupData[stateCode] : null;

        if (info && info.location) {
            pickupZoneAddress.textContent = info.location;
            pickupZoneContent.classList.remove('hidden');
            pickupZoneEmpty.classList.add('hidden');
        } else {
            pickupZoneContent.classList.add('hidden');
            pickupZoneEmpty.classList.remove('hidden');
        }
    }

    function updateShippingFields() {
        const selectedEnvio = envioInput?.value;
        const hasDeliveryMethod = !!selectedEnvio;
        const needsShipping = hasDeliveryMethod && selectedEnvio !== 'pickup';
        const isPickup = selectedEnvio === 'pickup';

        shippingDataBlock?.classList.toggle('hidden', !needsShipping);
        pickupInfoBlock?.classList.toggle('hidden', !isPickup);

        if (isPickup) {
            updatePickupInfo();
        }

        if (paymentMethodsBlock) {
            paymentMethodsBlock.classList.toggle('opacity-50', !hasDeliveryMethod);
            paymentMethodsBlock.classList.toggle('pointer-events-none', !hasDeliveryMethod);
        }

        shippingFields.forEach(field => {
            if (field.tagName.toLowerCase() === 'textarea') {
                field.disabled = !needsShipping;
                field.required = needsShipping;
            } else if (field.type === 'hidden' && field.classList.contains('custom-select-option')) {
                return;
            } else {
                field.disabled = !needsShipping;
                field.required = needsShipping;
            }
        });
    }

    envioInput?.addEventListener('change', updateShippingFields);
    estadoInput?.addEventListener('change', function () {
        if (envioInput?.value === 'pickup') {
            updatePickupInfo();
        }
    });
    updateShippingFields();

    const panel      = document.getElementById('payment-description');
    const panelText  = document.getElementById('payment-description-text');
    const pagoInput  = document.getElementById('pago-input');
    const receiptBlock = document.getElementById('payment-receipt-block');
    const receiptInput = document.getElementById('payment-receipt-input');
    if (!pagoInput || !panel || !panelText) return;

    function requiresReceipt(value) {
        if (!value) return false;
        const lowerValue = value.toLowerCase();
        const normalized = lowerValue.replace(/\s+/g, '').normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        return normalized.includes('binance') ||
               normalized.includes('pagomovil') ||
               normalized.includes('zelle') ||
               normalized.includes('pagosmultiples');
    }

    function updateReceiptField(value) {
        const needsReceipt = requiresReceipt(value);
        receiptBlock?.classList.toggle('hidden', !needsReceipt);
        if (receiptInput) {
            receiptInput.disabled = !needsReceipt;
            receiptInput.required = needsReceipt;
        }
    }

    function showDescription(value) {
        if (!value) {
            panel.classList.add('hidden');
            return;
        }

        const lowerValue = value.toLowerCase();
        if (lowerValue.includes('binance')) {
            panelText.textContent = 'Paga con USDT a trav\u00e9s de Binance Pay y env\u00edanos el comprobante de la transacci\u00f3n al finalizar.';
            panel.classList.remove('hidden');
            return;
        }

        if (lowerValue.includes('acordar')) {
            panelText.textContent = 'Coordina la forma de pago con nuestro equipo comercial. Te contactaremos en breve para confirmar los detalles.';
            panel.classList.remove('hidden');
            return;
        }

        if (lowerValue.replace(/\s+/g, '').includes('pagomovil') || lowerValue.includes('pago m\u00f3vil') || lowerValue.includes('pago movil')) {
            panelText.textContent = 'Realiza tu pago mediante Pago M\u00f3vil desde cualquier banco venezolano y env\u00edanos el comprobante al finalizar.';
            panel.classList.remove('hidden');
            return;
        }

        if (lowerValue.includes('zelle')) {
            panelText.textContent = 'Realiza tu pago en d\u00f3lares a trav\u00e9s de Zelle desde una cuenta bancaria en Estados Unidos y env\u00edanos el comprobante al finalizar.';
            panel.classList.remove('hidden');
            return;
        }

        if (lowerValue.includes('pagos multiples') || lowerValue.includes('pagos m\u00faltiples')) {
            panelText.textContent = 'Combina diferentes m\u00e9todos de pago para completar tu compra. Nuestro equipo te contactar\u00e1 en breve para coordinar las transacciones.';
            panel.classList.remove('hidden');
            return;
        }

        const selectedOption = document.querySelector('#payment-methods-list .custom-select-option[data-value="' + value + '"]');
        const desc = selectedOption?.dataset?.description || '';
        if (desc) {
            panelText.textContent = desc;
            panel.classList.remove('hidden');
        } else {
            panel.classList.add('hidden');
        }
    }

    pagoInput.addEventListener('change', function () {
        showDescription(pagoInput.value);
        updateReceiptField(pagoInput.value);
    });

    /**
     * Show description for pre-selected option on load
     */
    showDescription(pagoInput.value);
    updateReceiptField(pagoInput.value);
})();

/**
 * Custom Selects and State/City dynamic filter
 */
(function () {
    const citiesByState = (window.HelinSolicitud && window.HelinSolicitud.cities) || {};

    function initCustomSelect(container) {
        const trigger = container.querySelector('.custom-select-trigger');
        const optionsContainer = container.querySelector('.custom-select-options');
        let options = Array.from(container.querySelectorAll('.custom-select-option'));
        const hiddenInput = container.querySelector('input[type="hidden"]');
        let isDisabled = container.dataset.disabled === 'true';

        function updateDisabledState() {
            container.dataset.disabled = isDisabled ? 'true' : 'false';
            if (isDisabled) {
                container.classList.add('pointer-events-none', 'opacity-60');
                if (hiddenInput) hiddenInput.disabled = true;
            } else {
                container.classList.remove('pointer-events-none', 'opacity-60');
                if (hiddenInput) hiddenInput.disabled = false;
            }
        }

        updateDisabledState();
        setValue(hiddenInput.value || '', hiddenInput.value ? trigger.textContent : trigger.dataset.placeholder);

        function refreshOptions() {
            options = Array.from(container.querySelectorAll('.custom-select-option'));
        }

        function setValue(value, label) {
            if (!hiddenInput || !trigger) return;
            hiddenInput.value = value;
            trigger.textContent = label || trigger.dataset.placeholder || '';
            trigger.classList.toggle('is-placeholder', value === '');
            options.forEach(opt => {
                opt.classList.toggle('selected', value !== '' && opt.dataset.value === value);
            });
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
        }

        function handleOptionClick(e) {
            e.stopPropagation();
            setValue(this.dataset.value, this.textContent);
            optionsContainer.classList.remove('open');
            trigger.classList.remove('open');
        }

        function bindOptions() {
            options.forEach(option => {
                option.addEventListener('click', handleOptionClick);
            });
        }

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            if (isDisabled) return;
            document.querySelectorAll('.custom-select-options.open').forEach(open => {
                if (open !== optionsContainer) open.classList.remove('open');
            });
            document.querySelectorAll('.custom-select-trigger.open').forEach(open => {
                if (open !== trigger) open.classList.remove('open');
            });
            optionsContainer.classList.toggle('open');
            trigger.classList.toggle('open');
        });

        bindOptions();

        document.addEventListener('click', function () {
            optionsContainer.classList.remove('open');
            trigger.classList.remove('open');
        });

        container.setCustomOptions = function (newOptions) {
            const placeholder = trigger.dataset.placeholder;
            optionsContainer.innerHTML = '';
            newOptions.forEach(item => {
                const option = document.createElement('div');
                option.className = 'custom-select-option';
                option.dataset.value = item.slug;
                option.textContent = item.name;
                optionsContainer.appendChild(option);
            });
            refreshOptions();
            bindOptions();
            setValue('', placeholder);
            isDisabled = newOptions.length === 0;
            updateDisabledState();
        };

        container.clearOptions = function () {
            const placeholder = trigger.dataset.placeholder;
            optionsContainer.innerHTML = '';
            refreshOptions();
            bindOptions();
            setValue('', placeholder);
            isDisabled = true;
            updateDisabledState();
        };
    }

    document.querySelectorAll('.custom-select').forEach(initCustomSelect);

    const customerTypeInput = document.querySelector('input[name="tipo_cliente"]');
    const cedulaField = document.getElementById('cedula-field');
    const empresaFields = document.getElementById('empresa-fields');
    const cedulaInput = cedulaField?.querySelector('input');
    const empresaInputs = empresaFields?.querySelectorAll('input');

    function updateCustomerFields() {
        const customerType = customerTypeInput?.value;
        const isPerson = customerType === 'doctor' || customerType === 'paciente';
        const isCompany = customerType === 'empresa';

        cedulaField?.classList.toggle('hidden', !isPerson);
        empresaFields?.classList.toggle('hidden', !isCompany);

        if (cedulaInput) {
            cedulaInput.disabled = !isPerson;
            cedulaInput.required = isPerson;
        }

        empresaInputs?.forEach(input => {
            input.disabled = !isCompany;
            input.required = isCompany;
        });
    }

    customerTypeInput?.addEventListener('change', updateCustomerFields);
    updateCustomerFields();

    function setupStateCityFilter(estadoSelectId, ciudadSelectId) {
        const estadoCustom = document.getElementById(estadoSelectId);
        const ciudadCustom = document.getElementById(ciudadSelectId);
        if (!estadoCustom || !ciudadCustom) return;

        const estadoInput = estadoCustom.querySelector('input[type="hidden"]');

        estadoInput.addEventListener('change', function () {
            const cities = citiesByState[this.value] || [];
            if (cities.length === 0) {
                ciudadCustom.clearOptions();
            } else {
                ciudadCustom.setCustomOptions(cities);
            }
        });
    }

    setupStateCityFilter('estado-select-custom', 'ciudad-select-custom');
    setupStateCityFilter('envio-estado-select-custom', 'envio-ciudad-select-custom');
})();
