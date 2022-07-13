@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body" id="bodyCadr">
                        <div class="tab-content">
                            <div class="form-body">
                                <form method="post" action="{{route('store-statment')}}" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="row">

                                        <div class="col-12">

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>INV</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="inv" class="form-control"
                                                           name="inv" placeholder="INV">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>description</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="description_from_date" class="form-control"
                                                           name="description_from_date" data-date-format="yyyy-m-d"
                                                           placeholder="from date">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="description_to_date" class="form-control"
                                                           name="description_to_date" data-date-format="yyyy-m-d"
                                                           placeholder="to date">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>last date</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="last_date" class="form-control"
                                                           name="last_date"
                                                           placeholder="last date" data-date-format="yyyy-mm-dd">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>files</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="file" id="file" class="form-control" name="files[]"
                                                           multiple
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>store</span>
                                                </div>
                                                <div class="col-md-5 col-12 mb-5">
                                                    <select id="store" name="account_id">
                                                        <option value="">select</option>
                                                        @foreach($sotres as $store)
                                                            <option
                                                                value="{{$store->account_id}}">{{$store->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>Isnaad invoice</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="total_amount" class="form-control valRep"
                                                           name="total_amount" placeholder="Isnaad invoice">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>COD</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="cod" class="form-control valRep"
                                                           name="cod" placeholder="COD">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>Edit</span>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" id="edit" class="form-control valRep"
                                                           name="edit" placeholder="Edit">
                                                </div>
                                                <div class="col-md-5">
                                                    <textarea class="form-control form-control-solid" name="note"
                                                              placeholder="note" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>Balance</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="balance" class="form-control valRep"
                                                           name="balance" placeholder="Balance" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>total payment</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="totalPayemnt" disabled class="form-control"
                                                           placeholder="total Payemnt">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>Net Balance</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="netBalance" disabled class="form-control"
                                                           placeholder="Net Balance">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 float-md-left">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>last invoices</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <table class="table" id="notPaidTable">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col">INV</th>
                                                            <th scope="col">date</th>
                                                            <th scope="col">net blance</th>
                                                            <th scope="col">new value</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                        <br>
                                        <br>
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light "
                                            @if (auth()->user()->id===100)
                                                {{'disabled'}}
                                                @endif
                                            >Submit
                                            </button>

                                        </div>


                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
        </div>


    </div>
    </div>


@endsection
@section('scripts')

    <script>

        $(document).ready(function () {
            $(".dateRep").datepicker({});


        });
        $(function () {
            $("#description_to_date").datepicker({
                dateFormat: 'yy-mm-dd'

            });
        });
        $(function () {
            $("#description_from_date").datepicker({
                dateFormat: 'yy-mm-dd'

            });

        });
        $(function () {
            $("#statment_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $(function () {
            $("#initial_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $(function () {
            $("#last_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $("#store").select2({
            placeholder: "Select store",
            allowClear: true
        });

        $('#kt_repeater_2').repeater({

            initEmpty: false,

            defaultValues: {
                'text-input': ''
            },

            show: function () {
                const ar = [];
                document.querySelectorAll('[data-repeater-item]').forEach((e) => {
                    //   e.datepicker({});
                    $(this).find('.dateRep').datepicker();

                    if (e.style.display !== "none") {
                        ar.push(e)
                    }
                })
                //   console.log(ar.length)
                if (ar.length >= 4) {
                    alert('you cant add more than four payments')
                } else {
                    $(this).slideDown();
                }


            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });


        function totalPayment() {

            return totalPayment;
        }

        $(document).on("keyup", ".valRep", function () {
            let totalPayment = 0;
            document.querySelectorAll('.paymentVal').forEach((e) => {
                let value = parseFloat(e.value)
                if (value) {
                    totalPayment += value;
                }
            })
            let edit = parseFloat($('#edit').val())
            let cod = parseFloat($('#cod').val())
            let total_amount = parseFloat($('#total_amount').val())
            edit = edit ? edit : 0;
            cod = cod ? cod : 0;
            total_amount = total_amount ? total_amount : 0;
            let balance = cod - total_amount + edit
            let netBalance = balance - totalPayment
            $('#totalPayemnt').val(totalPayment)
            $('#balance').val(balance)
            $('#netBalance').val(netBalance)


        })
        $('#store').on('change', function () {

            var url = '{{route('getNotPaidStatment',':store_id')}}';

            url = url.replace(':store_id', $(this).val());

            $.ajax({
                url: url,

                type: "GET",
                processData: false,
                contentType: false,
                beforeSend() {
                    KTApp.block('#bodyCadr', {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'pleas wait'
                    });
                },
                success: function (data) {

                    if (data.success) {
                        console.log('success')
                        makeNotPaidTable(data.statments);

                    }
                    KTApp.unblock('#bodyCadr');
                },
                error: function (data) {
                    KTApp.unblock('#bodyCadr');
                    KTApp.unblockPage();
                },
            });
        })

        function makeNotPaidTable(statments) {
            $('.removal').remove()
            var i = 1;
            var totalSumNetBlance = 0;
            statments.forEach(function (statment) {
                totalSumNetBlance += parseFloat(statment.net_blance.replace(',', ''));
                var url = '{{Route('ne-show-statment',':statment_id')}}'
                url = url.replace(':statment_id', statment.id);

                var $tr = $('<tr class="removal">').append(
                    $('<td>').text(i),
                    $('<td>').html("<a href='" + url + "' target='_blank'>" + statment.inv + "</a>"),
                    $('<td>').text(statment.statment_date),
                    $('<td>').text(statment.net_blance),
                    $('<td>').html("<input type ='text' class='newPayment' name='payment[" + statment.id + "]'/> ")
                ).appendTo('#notPaidTable');
                i++;
            })

            $('<tr class="removal">').append(
                $('<td>').html("<b>total</b>"),
                $('<td>').text(''),
                $('<td>').text(''),
                $('<td>').text(totalSumNetBlance.toFixed('3')),
                $('<td>').html("<b id='totalNewPayment'>0</b>")
            ).appendTo('#notPaidTable');

        }

        $('#notPaidTable').on('change', '.newPayment', function () {

            var totalNewPayment = 0;
            $('.newPayment').each(function () {
                if ($(this).val()) {
                    totalNewPayment += parseFloat($(this).val())
                }

            });
            $('#totalNewPayment').text(totalNewPayment)
        });
        $('#description_to_date').on('change', function () {

            const date = new Date($(this).val());
            console.log(date)
             var newData = date.setDate(date.getDate() + 10);

            newData=   new Date(newData)
            console.log(newData)
           console.log(newData.getDay())

       //   console.log(newData)
            let formatted_date = newData.getFullYear() + "-" + (newData.getMonth()+1) + "-" + newData.getDate()
            console.log(formatted_date)
          $('#last_date').val(formatted_date)  ;

        })
    </script>
    <script>


    </script>
@endsection
