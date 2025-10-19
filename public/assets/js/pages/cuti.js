(function () {
    const formCuti = document.querySelector('#formCuti');
    // Form validation for Add new record
    if (formCuti) {
        const fv = FormValidation.formValidation(formCuti, {
            fields: {
                kode_cuti: {
                    validators: {
                        notEmpty: {
                            message: 'Kode Cuti Harus Disii !'
                        },

                        stringLength: {
                            max: 3,
                            message: 'Kode Cuti Max. 3 Karakter'
                        },
                    }
                },

                jenis_cuti: {
                    validators: {
                        notEmpty: {
                            message: 'Jenis Cuti Harus Diisi !'
                        },
                    },

                },

                jumlah_hari: {
                    validators: {
                        notEmpty: {
                            message: 'Jumlah Hari Harus Diisi !'
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
