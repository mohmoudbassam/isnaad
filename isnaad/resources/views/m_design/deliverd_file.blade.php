<html>
<form method="post" action="{{route('deliverd_file')}}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <input type="submit">
</form>
</html>
