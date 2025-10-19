(function () {
    const formeditCabang = document.querySelector('#formeditCabang');
    // Form validation for Add new record
    if (formeditCabang) {
        const fv = FormValidation.formValidation(formeditCabang, {
            fields: {
                kode_cabang: {
                    validators: {
                        notEmpty: {
                            message: 'Kode Cabang Harus Diisi'
                        },
                        stringLength: {
                            max: 3,
                            min: 3,
                            message: 'Kode Cabang Harus 3 Karakter'
                        },


                    }
                },
                nama_cabang: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Cabang Harus Diisi'
                        }
                    }
                },

                alamat_cabang: {
                    validators: {
                        notEmpty: {
                            message: 'Alamat Cabang Harus Diisi'
                        }
                    }
                },

                telepon_cabang: {
                    validators: {
                        notEmpty: {
                            message: 'Telepon Cabang Harus Diisi'
                        },
                        numeric: {
                            message: 'Telepon Cabang Harus Diisi dengan Angka'
                        },

                        stringLength: {
                            max: 13,
                            message: 'Maksimal 13 Karakter'
                        },
                    }
                },

                lokasi_cabang: {
                    validators: {
                        notEmpty: {
                            message: 'Lokasi Cabang Harus Diisi'
                        }
                    }
                },

                radius_cabang: {
                    validators: {
                        notEmpty: {
                            message: 'Radius Cabang Harus Diisi'
                        }
                    }
                },

                kode_pt: {
                    validators: {
                        notEmpty: {
                            message: 'Kode PT Harus Diisi'
                        },
                        stringLength: {
                            max: 3,
                            min: 3,
                            message: 'Kode PT Harus 3 Karakter'
                        },


                    }
                },

                nama_pt: {
                    validators: {
                        notEmpty: {
                            message: 'Nama PT Harus Diisi'
                        }
                    }
                },


                kode_regional: {
                    validators: {
                        notEmpty: {
                            message: 'Regional Harus Dipilih'
                        }
                    }
                },


                urutan: {
                    validators: {
                        notEmpty: {
                            message: 'Urutan Harus Diisi'
                        }
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
            }
        });
    }
})();
