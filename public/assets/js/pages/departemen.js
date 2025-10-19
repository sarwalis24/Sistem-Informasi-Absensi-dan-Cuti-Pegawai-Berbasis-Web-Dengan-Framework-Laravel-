(function () {
    const formDepartemen = document.querySelector('#formDepartemen');
    // Form validation for Add new record
    if (formDepartemen) {
        const fv = FormValidation.formValidation(formDepartemen, {
            fields: {
                kode_dept: {
                    validators: {
                        notEmpty: {
                            message: 'Kode Departemen Harus Disii !'
                        },

                        stringLength: {
                            max: 3,
                            message: 'Kode Departemen Max. 3 Karakter'
                        },
                    }
                },

                nama_dept: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Departemen Harus Diisi !'
                        },
                    },

                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.mb-3'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),

                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        });
    }
})();
