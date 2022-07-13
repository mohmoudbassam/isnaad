
    <div class="container">
        <div class="card bg-light mt-3">
            <div class="card-header">
                Please select the file you want to upload
            </div>
            <div class="card-body">
                <div id="app">

                </div>

                <form action="{{url('return_order')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="file" name="file" class="form-control">
                    <br>
                    <button class="btn btn-success">submit</button>
                </form>
            </div>
        </div>
    </div>

