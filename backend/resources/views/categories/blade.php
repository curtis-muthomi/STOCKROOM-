<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
</head>
<body>

    <h1>Create Category</h1>

    <form action="/categories" method="POST">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Name</label>
            <input
                type="text"
                id="name"
                name="name"
            >
        </div>

        <br>

        <div>
            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
            ></textarea>
        </div>

        <br>

        <button type="submit">Create Category</button>

    </form>

</body>
</html>