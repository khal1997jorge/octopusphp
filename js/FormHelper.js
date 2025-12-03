class FormHelper {
    static MENSAJES_ERRORES = {
        CONTRASENA_INVALIDA: 'La contraseña solo puede contener letras, números y los símbolos @$!%*?&#.',
        CONTRASENA_CORTA: 'La contraseña debe tener al menos 8 caracteres',
        CORREO_INVALIDO: 'El formato del correo es inválido',
        FALTA_CAMPO_OBLIGATORIO: `Este campo es obligatorio`,
    }

    static Initialize = (idFormulario, idBotonGuardar) => {
        /**
         * Hacemos que cada input del formulario y hacemos que esté pendiente cada vez que el usuario pierda el foco del
         * campo seleccionado, se realice la validación del campo, y que al final dispare el evento personalizado 'onValidateInput'
         */
        const inputsHTML = document.querySelectorAll(`#${idFormulario} input[name]`);

        for (const input of inputsHTML) {
            input.addEventListener("blur", () => {
                this._mostrarMensajeDeErrorSiAplica(input)
                document.dispatchEvent(new CustomEvent('onValidateInput'))
            });
        }

        /**
         * Le indicamos al formulario que escuche el evento personzonalizado 'onValidateInput' para que habilite o
         * deshabilite el botón guardar cada vez que se haga una validación de cualquier campo del formulario
         */
        document.addEventListener("onValidateInput", () =>
            FormHelper._deshabilitarBotonSiAplica(idBotonGuardar)
        )
    }

    static _mostrarMensajeDeErrorSiAplica = (input) => {
        const spanErrorHTML = document.querySelector(`span[data-error-for="${input.name}"]`);

        const msgError = this._validarCampo(input.name, input.value, input.required);

        if (msgError) {
            input.classList.add('field-form-error');
            spanErrorHTML.innerText = msgError;
        } else {
            input.classList.remove('field-form-error');
            spanErrorHTML.innerText = '';
        }
    }

    static _validarCampo = (nombreCampo, valor, esObligatorio) => {
        if (!valor && esObligatorio) {
            return this.MENSAJES_ERRORES.FALTA_CAMPO_OBLIGATORIO
        }

        switch (nombreCampo) {
            case "password":
                if (valor && valor.length < 8) {
                    return this.MENSAJES_ERRORES.CONTRASENA_CORTA
                }
                if (valor && !this._contrasenaEsValida(valor)) {
                    return this.MENSAJES_ERRORES.CONTRASENA_INVALIDA;
                }
                break;

            case "email":
                if (!this._correoEsValido(valor)) {
                    return this.MENSAJES_ERRORES.CORREO_INVALIDO;
                }
                break;
            default:
                break;
        }

        return "";
    }

    static _deshabilitarBotonSiAplica = (idBotonGuardar) => {
        const spansErrorHTML = Array.from(document.getElementsByClassName(`text-error`));
        const hayErrores = spansErrorHTML.some(spanError => spanError.textContent);

        const inputsHTML =  Array.from(document.querySelectorAll("input[name]"));
        const hayCampoObligatorioVacio = inputsHTML.some(input => input.required && !input.value);

        const btnGuardar = document.getElementById(idBotonGuardar);

        btnGuardar.disabled = hayErrores || hayCampoObligatorioVacio;
    }

    static _contrasenaEsValida = (input) => /^[A-Za-z0-9@$!%*?&#]{8,}$/.test(input);
    static _correoEsValido = (input) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input);
}