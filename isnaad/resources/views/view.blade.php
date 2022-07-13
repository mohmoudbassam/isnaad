@extends('index2')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/vendors/css/file-uploaders/dropzone.min.css">
    <link rel="stylesheet" type="text/css"
          href="{{url('/')}}/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css"
          href="{{url('/')}}/app-assets/vendors/css/tables/datatable/extensions/dataTables.checkboxes.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/plugins/file-uploaders/dropzone.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/pages/data-list-view.css">
@endsection
@section('sec')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">

                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Data list view starts -->
                <section id="data-thumb-view" class="data-thumb-view-header">

                    <!-- dataTable starts -->
                    <div class="table-responsive">
                        <table class="table data-thumb-view" id="orders-table-CaReport">
                            <thead>
                            <tr>

                                <th>name</th>
                                <th>email</th>
                                <th>type</th>
                                <th>permision</th>

                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!-- dataTable ends -->

                    <!-- add new sidebar starts -->

                    <!-- add new sidebar ends -->
                </section>
                <!-- Data list view end -->

            </div>
        </div>
        <div class="modal fade text-left" tabindex="-1" role="dialog" id="chPermision" aria-labelledby="myModalLabel16"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="nameOfUser"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <div class="table-responsive border rounded px-1 ">
                                <h6 class="border-bottom py-1 mx-1 mb-0 font-medium-2"><i
                                        class="feather icon-lock mr-50 "></i>Permission</h6>
                                <table class="table table-borderless" id="TablePermision">
                                    <input type="hidden" id="u_id">
                                    <thead>
                                    <tr>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="addPermision()">Accept</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')


    <script>
        var chkecd=[];
        var i=0;
        function changPermisionModal(id) {
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'getUserPermision',
                data: {
                    "_token": "{{ csrf_token() }}",
                    user_id: id
                },
                success: function (data) {

                    createTablePermision(data,id);
                    $('#nameOfUser').text('Permission for user '+data.userName)
                }
            }));
            $('#chPermision').modal('show');
        }

        $(function () {
            $('#orders-table-CaReport').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('getallUser') !!}',
                    "type": "GET",
                    "data": function (d) {

                    }
                },

                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},

                    {
                        data: 'type', render: function (data, type, row, meta) {
                            if (data == 'm') {
                                return 'Manger'
                            } else if (data == 'p') {
                                return 'Profissonal'
                            }
                            return 'staff'
                        }
                    },
                    {data: 'per', name: 'per'},
                ]
            });
        });

        function createTablePermision(data,id) {
            chkecd =[];
            var keys= Object.keys(data.allPermision)
            $('.removeal').remove();
            keys.forEach(function (i){
               var text='<tr class="removeal">'+'<td>'+i+'</td>';
                data.allPermision[i].forEach(function (e){
                    text+= '<td>'+ '<div class="custom-control custom-checkbox"><input  type="checkbox" '+HasPermision(e,data)+'  id="' +e+'"' +' class="custom-control-input">'+'<label  class="custom-control-label" for="'+e+'"  >'+e+'</label> </div></td>';
                })
                text+='</tr>';
                $('#TablePermision').append(text);
            })
              $('#u_id').val(id)

            chkecd.forEach(element=>{
                $('#'+element+'').prop('checked',true)
            })



        }

        function HasPermision(e,data){

            data.userPermision.find(function (permision, index){

                if(permision.name===e){
                    chkecd[i]=e;
                    i++;
                }


            })

        }

        function addPermision(){
            var ids=[];
         $("input[type='checkbox']:checked").each(function (){
              ids.push($(this).attr('id'))
            });
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'savePermision',
                data: {
                    "_token": "{{ csrf_token() }}",
                    Permision: ids,
                    user_id: $('#u_id').val()
                },
                success: function (data) {

                }
            }));
        }

    </script>
@endsection
