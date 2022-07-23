<script>
    function showModal(url, callback = null) {
        console.log(url);
        $.ajax({
            url: url,
            type: "GET",
            beforeSend() {
                KTApp.blockPage({
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'success',
                    message: 'please wait'
                });
            },
            success: function (data) {
                if (callback && typeof callback === "function") {
                    callback(data);
                } else {

                    if (data.success) {

                        $('#page_modal').html(data.page).modal('show', {backdrop: 'static', keyboard: false});
                    } else {
                        showAlertMessage('error', '@lang('constants.unknown_error')');
                    }
                    KTApp.unblockPage();
                }
            },
            error: function (data) {
                KTApp.unblockPage();
            },
        });
    }

    function showAlertMessage(type, message,callback=null) {
        toastr.options = {
            "closeButton": true,
            "debug": true,
            "newestOnTop": true,
            "progressBar": false,
            "positionClass": "toast-bottom-left",
            "preventDuplicates": true,
            "onclick": callback,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        if (type === 'success') {
            toastr.success(message);
        } else if (type === 'warning') {
            toastr.warning(message);
        } else if (type === 'error' || type === 'danger') {
            toastr.error(message);
        } else {
            toastr.info(message);
        }
    }

    function change_status(id, url, status = null, callback = null) {
        $.ajax({
            url: url,
            data: {id: id, status: status, _token: '{{csrf_token()}}'},
            type: "POST",
            beforeSend() {
                KTApp.blockPage({
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'success',
                    message: '@lang('constants.please_wait') ...'
                });
            },
            success: function (data) {
                if (callback && typeof callback === "function") {
                    callback(data);
                } else {
                    if (data.success) {
                        showAlertMessage('success', data.message);
                        $('#items_table').DataTable().ajax.reload(null, false);

                    } else {
                        showAlertMessage('error', data.message);
                    }
                    KTApp.unblockPage();
                }
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data);
            },
        });
    }

    function file_input(selector, options) {
        let defaults = {
            theme: "fas",
            showDrag: false,
            deleteExtraData: {
                '_token': '{{csrf_token()}}',
            },
            browseClass: "btn btn-info",
            browseLabel: "@lang('constants.browse')",
            browseIcon: "<i class='la la-file'></i>",
            removeClass: "btn btn-danger",
            removeLabel: "@lang('constants.delete')",
            removeIcon: "<i class='la la-trash-o'></i>",
            showRemove: false,
            showCancel: false,
            showUpload: false,
            showPreview: true,
            msgPlaceholder: "@lang('constants.select_files') {files}...",
            msgSelected: "@lang('constants.selected') {n} {files}",
            fileSingle: "@lang('constants.one_files')",
            filePlural: "@lang('constants.multi_files')",
            dropZoneTitle: "@lang('constants.drag_drop_files_here') &hellip;",
            msgZoomModalHeading: "@lang('constants.file_details')",
            dropZoneClickTitle: '<br>(@lang('constants.click_to_browse'))',
            initialPreview: [],
            initialPreviewShowDelete: false,
            initialPreviewAsData: true,
            initialPreviewConfig: [],
            initialPreviewFileType: 'image',
            overwriteInitial: true,
            browseOnZoneClick: true,
            maxFileCount: 6,

        };
        let settings = $.extend({}, defaults, options);
        $(selector).fileinput(settings);
    }

    function getChildren(select, child, route, model = '', callback = null) {
        $.ajax({
            url: route,
            data: {id: select.val(), _token: '{{csrf_token()}}'},
            type: "POST",
            beforeSend() {
                if (model) {
                    KTApp.block(model, {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: '@lang('constants.please_wait') ...'
                    });
                } else {
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: '@lang('constants.please_wait') ...'
                    });
                }
            },
            success: function (data) {
                if (callback && typeof callback === "function") {
                    callback(data);
                } else {
                    if (data.success) {
                        $(child).html(data.page);
                        $(child).selectpicker('refresh');
                    } else {
                        showAlertMessage('error', '@lang('constants.unknown_error')');
                    }
                    if (model) {
                        KTApp.unblock(model);
                    } else {
                        KTApp.unblockPage();
                    }
                }
            },
            error: function (data) {
                console.log(data);
            },
        });
    }

    function delete_items(id, url, callback = null) {
        let data = [];
        if (id) {
            data = [id];
        } else {
            if ($('input.select:checked').length > 0) {
                $.each($("input.select:checked"), function () {
                    data.push($(this).val());
                });
            }
        }
        if (data.length <= 0) {
            showAlertMessage('error', '@lang('constants.noSelectedItems')');
        } else {
            Swal.fire({
                title: data.length === 1 ? '@lang('constants.deleteItem')' : '@lang('constants.delete') ' + data.length + ' @lang('constants.items')',
                text: "@lang('constants.sure')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#84dc61',
                cancelButtonColor: '#d33',
                confirmButtonText: '@lang('constants.yes')',
                cancelButtonText: '@lang('constants.no')'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'ids': data
                        },
                        beforeSend() {
                            KTApp.blockPage({
                                overlayColor: '#000000',
                                type: 'v2',
                                state: 'success',
                                message: '@lang('constants.please_wait') ...'
                            });
                        },
                        success: function (data) {
                            if (callback && typeof callback === "function") {
                                callback(data);
                            } else {
                                if (data.success) {
                                    $('#items_table').DataTable().ajax.reload(null, false);
                                    showAlertMessage('success', data.message);
                                } else {
                                    showAlertMessage('error', '@lang('constants.unknown_error')');
                                }
                                KTApp.unblockPage();
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        },
                    });
                }
            });
        }
    }

    function postData(data, url, callback = null) {
        $.ajax({
            url: url,
            data: data,
            type: "POST",
            processData: false,
            contentType: false,
            beforeSend() {
                KTApp.block('#page_modal', {
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'success',
                    message: '@lang('constants.please_wait') ...'
                });
            },
            success: function (data) {
                if (callback && typeof callback === "function") {
                    callback(data);
                } else {

                    if (data.success == true) {
                        $('#page_modal').modal('hide');
                        //  $('#items_table').DataTable().ajax.reload(null, false);
                        showAlertMessage('success', data.message);
                    } else {
                        if (data.message) {
                            showAlertMessage('error', data.message);
                        } else {
                            showAlertMessage('error', '@lang('constants.unknown_error')');
                        }
                    }
                    KTApp.unblock('#page_modal');
                }
                KTApp.unblockPage();
            },
            error: function (data) {
                console.log(data);
                KTApp.unblock('#page_modal');
                KTApp.unblockPage();
            },
        });
    }


    function postDataProc(data, url, callback = null) {
        $.ajax({
            url: url,
            data: data,
            type: "POST",
            processData: false,
            contentType: false,
            beforeSend() {
                KTApp.block('#body', {});
            },
            success: function (data) {
                if (callback && typeof callback === "function") {
                    callback(data);
                } else {

                    if (data.success == true) {
                        $('#forAll').hide();
                        $('#ManulaBoxDiv').hide();
                        showAlertMessage('success', data.message);
                        KTApp.unblock('#body');
                    } else {
                        if (data.message) {
                            showAlertMessage('error', data.message);
                        } else {
                            showAlertMessage('error', '@lang('constants.unknown_error')');
                        }
                        KTApp.unblock('#body');
                    }
                    KTApp.unblock('#page_modal');
                }
                KTApp.unblockPage();
            },
            error: function (data) {
                console.log(data);
                KTApp.unblock('#page_modal');
                KTApp.unblockPage();
            },
        });
    }

    @if(session('success'))
    showAlertMessage('success', '{{session('success')}}');
    @elseif(session('error'))
    showAlertMessage('error', '{{session('error')}}');
    @endif

</script>
