@extends('layouts.plane')

@section('page_heading','Orders')
@section('section')

        <table class="table table-bordered" id="orders-table">
            <thead>
            <tr>
                <th>Shipping #</th>
                <th>Order #</th>
                <th>Carrier</th>
                <th>Tracking #</th>
                <th>Cod</th>
                <th>Awb</th>
                <th>Weight</th>
                <th>Store</th>
                <th>City</th>
                <th>Created_at</th>
            </tr>
            </thead>
        </table>

@stop

@push('scripts')
    <script>

        $(function() {
            $('#orders-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('datatables.orders') !!}',

                columns: [
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'carrier', name: 'carrier' },
                    { data: 'tracking_number', name: 'tracking_number' },
                    { data: 'cod_amount', name: 'cod_amount' },
                    {
                        "data": "awb_url",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                {{--data = @php --}}
                                {{--    route('AramexLabel/'.'data')--}}
                                {{--    @endphp--}}
                                data = '<a href="' + data + '">AWB</a>';
                            }
                            return data;
                        }
                    },
                  //  { data: "<a href= data:'awb_url'>awb_url<a>", name: 'awb_url' },
                    { data: 'weight', name: 'weight' },
                    { data: 'store_name', name: 'store_name' },
                    { data: 'city', name: 'city' },
                    { data: 'created_at', name: 'created_at' },
                ]
            });
        });
    </script>

@endpush
