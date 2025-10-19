(function () {
    const formcreateKaryawan = document.querySelector('#formcreateKaryawan');
    // Form validation for Add new record
    if (formcreateKaryawan) {
        const fv = FormValidation.formValidation(formcreateKaryawan, {
            fields: {
                nik: {
                    validators: {
                        notEmpty: {
                            message: 'NIK Harus Diisi'
                        },
                    }
                },

                no_ktp: {
                    validators: {
                        notEmpty: {
                            message: 'No. KTP Harus Diisi'
                        },
                        stringLength: {
                            max: 16,
                            message: 'No. KTP Maksimal 16 Karakter'
                        },
                    },

                },

                nama_karyawan: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Karyawan Harus Diisi'
                        },
                    }
                },

                tempat_lahir: {
                    validators: {
                        notEmpty: {
                            message: 'Tempat Lahir Harus Diisi'
                        },
                    }
                },

                tanggal_lahir: {
                    validators: {
                        notEmpty: {
                            message: 'Tanggal Lahir Harus Diisi'
                        },
                    }
                },

                alamat: {
                    validators: {
                        notEmpty: {
                            message: 'Alamat Harus Diisi'
                        },
                    }
                },
                jenis_kelamin: {
                    validators: {
                        notEmpty: {
                            message: 'Jenis Kelamin Harus Diisi'
                        },
                    }
                },

                no_hp: {
                    validators: {
                        notEmpty: {
                            message: 'No. HP Harus Diisi'
                        },
                        stringLength: {
                            max: 16,
                            message: 'No. HP Maksimal 16 Karakter'
                        },
                    },

                },

                kode_status_kawin: {
                    validators: {
                        notEmpty: {
                            message: 'Status Kawin Harus Diisi'
                        },
                    }
                },

                pendidikan_terakhir: {
                    validators: {
                        notEmpty: {
                            message: 'Pendidikan Terakhir Harus Diisi'
                        },
                    }
                },


                kode_cabang: {
                    validators: {
                        notEmpty: {
                            message: 'Kantor Cabang Harus Diisi'
                        },
                    }
                },

                kode_dept: {
                    validators: {
                        notEmpty: {
                            message: 'Departemen Harus Diisi'
                        },
                    }
                },



                kode_jabatan: {
                    validators: {
                        notEmpty: {
                            message: 'Jabatan Harus Diisi'
                        },
                    }
                },


                tanggal_masuk: {
                    validators: {
                        notEmpty: {
                            message: 'Tanggal Masuk Harus Diisi'
                        },
                    }
                },
                status_karyawan: {
                    validators: {
                        notEmpty: {
                            message: 'Status Karyawan Harus Diisi'
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
            }
        });
    }
})();
