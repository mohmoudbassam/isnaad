<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"
                id="exampleModalLongTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

            <div class="modal-body">


                    <table class="table table-bordered table-hover table-checkable" id="statment_modal"
                           style="margin-top: 13px !important">

                        <thead>
                        <th>INV</th>
                        <th>Net Balance</th>
                        <th>COD</th>
                        </thead>
                    </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">cancel</button>
            </div>

    </div>
</div>

<script>
    "use strict";
    var KTDatatablesDataSourceAjaxServer = function () {
        $.fn.dataTable.ext.errMode = 'none';
        var initTable1 = function () {
            var table = $('#statment_modal');

            // begin first table
            table.DataTable({
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                "pageLength": 10,
                ajax: {
                    url: '{!! route('sammary_statment_list') !!}',
                    type: 'GET',
                    data: function (d) {
                        d.store_id = '{{$store_id}}';
                    },
                },
                columns: [
                    {
                        data: 'inv', name: 'inv', "searchable": false,"orderable":false
                    }, {
                        data: 'net_balance', name: 'net_balance', "searchable": false,
                    }, {
                        data: 'cod', name: 'cod', "searchable": false,
                    },


                ],
                "footerCallback": function (row, data, start, end, display) {
                    console.log(this.api().ajax.json())
                    let total_amount = this.api().ajax.json().total_net_balance
                    let numberOfInvoice = this.api().ajax.json().numberOfInvoice

                    $('.removal').remove()
                    $('#kt_datatable').append($('<tfoot class="removal">').append('tr>' + '<th colspan="4" style="text-align:right">Total:</th>'  +'<th>'+ numberOfInvoice + '</th>' + '<th>' + total_amount + '</th>' + '</tr>'));


                }
            });
        };

        return {

            //main function to initiate the module
            init: function () {
                initTable1();
            },

        };

    }();

    jQuery(document).ready(function () {
        KTDatatablesDataSourceAjaxServer.init();
    });
</script>
