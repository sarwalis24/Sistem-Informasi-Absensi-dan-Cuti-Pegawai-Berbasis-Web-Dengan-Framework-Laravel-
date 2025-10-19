(function () {
    const formcreateJamKerja = document.querySelector('#formcreateJamKerja');
    // Form validation for Add new record
    if (formcreateJamKerja) {
        const fv = FormValidation.formValidation(formcreateJamKerja, {
            fields: {
                kode_jam_kerja: {
                    validators: {
                        notEmpty: {
                            message: 'Kode Jam Kerja Harus Disii !'
                        },

                        stringLength: {
                            max: 4,
                            min: 4,
                            message: 'Kode Jam Kerja Harus 4 Karakter'
                        },

                    }
                },

                nama_jam_kerja: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Jam Kerja Harus Disii !'
                        },
                    }
                },

                jam_masuk: {
                    validators: {
                        notEmpty: {
                            message: 'Jam Masuk Harus Diisi !'
                        },
                        regexp: {
                            regexp: /^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/,
                            message: 'Format Jam Masuk harus hh:mm'
                        }
                    }
                },

                jam_pulang: {
                    validators: {
                        notEmpty: {
                            message: 'Jam Pulang Harus Diisi !'
                        },
                        regexp: {
                            regexp: /^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/,
                            message: 'Format Jam Pulang harus hh:mm'
                        }
                    }
                },

                istirahat: {
                    validators: {
                        notEmpty: {
                            message: 'Istirahat Harus Diisi'
                        },
                    }
                },

                jam_awal_istirahat: {
                    validators: {
                        callback: {
                            message: 'Jam Awal Istirahat Harus Diisi',
                            callback: function (input) {
                                const istirahatValue = document.querySelector('[name="istirahat"]').value;
                                if (istirahatValue === '1') {
                                    return input.value !== '';
                                }
                                return true;
                            }
                        },
                        regexp: {
                            regexp: /^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/,
                            message: 'Format Jam Awal Istirahat harus hh:mm',
                            enabled: function () {
                                const istirahatValue = document.querySelector('[name="istirahat"]').value;
                                return istirahatValue === '1';
                            }
                        }
                    }
                },
                jam_akhir_istirahat: {
                    validators: {
                        callback: {
                            message: 'Jam Akhir Istirahat Harus Diisi',
                            callback: function (input) {
                                const istirahatValue = document.querySelector('[name="istirahat"]').value;
                                if (istirahatValue === '1') {
                                    return input.value !== '';
                                }
                                return true;
                            }
                        },
                        regexp: {
                            regexp: /^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$/,
                            message: 'Format Jam Akhir Istirahat harus hh:mm',
                            enabled: function () {
                                const istirahatValue = document.querySelector('[name="istirahat"]').value;
                                return istirahatValue === '1';
                            }
                        }
                    }
                },

                total_jam: {
                    validators: {
                        notEmpty: {
                            message: 'Total Jam Harus Diisi'
                        },
                    }
                },

                lintashari: {
                    validators: {
                        notEmpty: {
                            message: 'Lintas Hari Harus Diisi'
                        },
                    }
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

                // Tambahkan event listener untuk select istirahat
                document.querySelector('[name="istirahat"]').addEventListener('change', function () {
                    // Revalidate jam_awal_istirahat dan jam_akhir_istirahat
                    fv.revalidateField('jam_awal_istirahat');
                    fv.revalidateField('jam_akhir_istirahat');
                });
            }
        });
    }
})();
