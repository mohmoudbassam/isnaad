<html>
<form enctype="multipart/form-data" method="post" action="{{route('return-file-action')}}">
    @csrf
    <input type="file" name="file">
    <input type="submit">
</form>
</html>
