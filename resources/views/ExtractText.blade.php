<!DOCTYPE html>
<html>

<head>
    <title>Extract Text from Vue Template</title>
</head>

<body>
    <form action="{{ url('/extract-text') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="files[]" multiple>
        <input type="submit" value="Extract Text">
    </form>
</body>

</html>