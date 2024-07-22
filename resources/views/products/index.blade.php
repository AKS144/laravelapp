<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <h1>Products</h1>
    <table border="1">
        <tr>
            <th>SKU</th>
            <th>Title</th>
            <th>Price</th>
            <th>Picture</th>
        </tr>
        @foreach ($products as $product)
        <tr>
            <td>{{ $product->sku }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->price }}</td>
            <td><img src="{{ $product->picture }}" alt="{{ $product->title }}"></td>
        </tr>
        @endforeach
    </table>
</body>
</html>
