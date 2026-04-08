<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>

    <h1>All Users</h1>

    <!-- ✅ Success Message -->
    @if(session('success'))
        <p style="color:green; font-weight:bold;">
            {{ session('success') }}
        </p>
    @endif

    <!-- ✅ Error Messages -->
    @if ($errors->any())
        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ✅ User Table -->
    @if($users->count() > 0)
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Action</th>
            </tr>

            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->name }}</td>
                <td>
                    <!-- ✅ Delete Button -->
                    <form action="/delete-user/{{ $user->id }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                        
                        @csrf
                        @method('DELETE')

                        <button type="submit" style="color:red;">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    @else
        <p>No users found.</p>
    @endif

    <hr>

    <!-- ✅ Add User Form -->
    <h2>Add User</h2>

    <form method="POST" action="/save-user">
        @csrf

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Submit</button>
    </form>

</body>
</html>