<!DOCTYPE html>
<html>
<head>
    <title>Import Users</title>
</head>
<body>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button type="submit">Import Users</button>
    </form>
</body>
</html>
