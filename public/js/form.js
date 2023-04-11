const validate = (violations = {}, form = null) => {

    let fields = document.querySelectorAll('.form-control[name], .form-select[name], .form-check-input[name]');
    if (form !== null) {
        fields = form.querySelectorAll('.form-control[name], .form-select[name], .form-check-input[name]')
    }
    fields.forEach((field) => {
        const feedback = field.nextElementSibling;
        if (feedback === null || feedback === undefined) {
            const elt = document.createElement('small');
            const container = field.closest('.form-group');
            elt.innerText = violations[field.name];
            elt.classList.add('invalid-feedback');
            container.insertAdjacentHTML('beforeend', elt);
        }
        if (!violations.hasOwnProperty(field.name)) {
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.innerText = "";
                feedback.style.display = "none";
            }
            validInput(field);
        } else {
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.innerText = violations[field.name];
                feedback.style.display = "unset";
            }

            invalidInput(field);
        }
    });

    return;
}

const validInput = (input) => {
    if (input.classList.contains('is-invalid')) {
        return input.classList.replace('is-invalid', 'is-valid');
    }

    return input.classList.add('is-valid');
}

const invalidInput = (input) => {
    if (input.classList.contains('is-valid')) {
        return input.classList.replace('is-valid', 'is-invalid');
    }

    return input.classList.add('is-invalid');
}

const validateAll = (form = null) => {
    let inputs = document.querySelectorAll('.form-control[name]');
    if (form !== null) {
        inputs = form.querySelectorAll('.form-control[name]')
    }

    return inputs.forEach(input => validInput(input))
}

const resetValidation = (form = null) => {
    let inputs = document.querySelectorAll('.form-control[name]');
    if (form !== null) {
        inputs = form.querySelectorAll('.form-control[name]')
    }

    inputs.forEach((input) => {
        input.classList.remove('is-valid');
        input.classList.remove('is-invalid');
    });

    return;
}

const getValues = (form = null) => {
    let object = {};
    const selector = '.form-select, .btn-check, .form-control, .form-check-input';
    let inputs = form !== null ? form.querySelectorAll(selector) : document.querySelectorAll(selector);

    inputs.forEach((input) => {
        if (input.type === 'checkbox') {
            object[input.name] = (input.checked) ? input.value : null;
        } else if (input.type === 'radio') {
            object[input.name] = document.querySelector(`[name="${input.name}"]:checked`).value;
        } else {
            object[input.name] = input.value === "" ? null : input.value;
        }
    });

    return object;
}

const fillErrorMessage = (container, content = null) => {

    if (content !== '' && content !== null) {
        container.classList.remove('hidden')
        return container.innerHTML = content;
    }

    container.classList.add('hidden');
    return container.innerHTML = '';
}

const errorHTTPRequest = () => flash('Une érreur est survenue lors du traitement de la requête !', 'danger');

const amountTypes = document.querySelectorAll('[data-type-amount]');

if (amountTypes !== null) {
    amountTypes.forEach(elt => {
        elt.addEventListener('keyup', (e) => {
            let val = e.target?.value.replace(/,/g, '.');
            val = val.replace(/[^\w\-.]/g,'', '');
            console.info({e, elt, val})
            e.target.value = val;
        });
    });
}