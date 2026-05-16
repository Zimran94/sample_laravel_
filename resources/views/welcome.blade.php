<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>AJAX CRUD USERS</title>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<h1>User Management (AJAX)</h1>

<p id="message" style="color:green;font-weight:bold;"></p>

<!-- USERS TABLE -->
<table border="1" cellpadding="10" id="userTable">
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Name</th>
        <th>Profile</th>
        <th>Action</th>
    </tr>

    @foreach($users as $user)
    <tr id="userRow{{ $user->id }}">
        <td>{{ $user->id }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->name }}</td>
        <td>
            @if($user->profile)
                <img src="{{ asset('storage/users/' . $user->profile) }}" width="50" height="50">
            @else
                N/A
            @endif
        </td>
        <td>
            <button onclick="editUser({{ $user->id }}, '{{ $user->email }}', '{{ $user->name }}')" style="color:blue;">
                Edit
            </button>

            <button onclick="deleteUser({{ $user->id }})" style="color:red;">
                Delete
            </button>
        </td>
    </tr>
    @endforeach
</table>

<hr>

<!-- FORM -->
<h2 id="formTitle">Add User</h2>

<form id="userForm">

<form id="userForm" enctype="multipart/form-data">
    @csrf

    <input type="hidden" id="user_id">

    <label>Email:</label><br>
    <input type="email" id="email" required><br><br>

    <label>Name:</label><br>
    <input type="text" id="name" required><br><br>

    <label>Password (leave empty when updating):</label><br>
    <input type="password" id="password"><br><br>

    <label>Profile Image:</label><br>
    <input type="file" id="profile_image" name="profile_image"><br><br>   
    <button type="submit" id="submitBtn">Submit</button>
</form>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let editMode = false;

/* ================= ADD / UPDATE ================= */
$('#userForm').submit(function(e){
    e.preventDefault();

    let id = $('#user_id').val();
    let url = editMode ? '/update-user/' + id : '/save-user';

    let formData = new FormData();
    formData.append('email', $('#email').val());
    formData.append('name', $('#name').val());
    formData.append('password', $('#password').val());
    let image = $('#profile_image')[0].files[0];

    if(image){
        formData.append('profile_image', image);
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res){

            $('#message').text(res.message);

            if(editMode){
                // UPDATE ROW
                $('#userRow' + id).html(`
                    <td>${id}</td>
                    <td>${res.user.email}</td>
                    <td>${res.user.name}</td>
                    <td>
                        @if($user->profile)
                            <img src="{{ asset('storage/profiles/' . $user->profile) }}" width="50" height="50">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <button onclick="editUser(${id}, '${res.user.email}', '${res.user.name}')" style="color:blue;">Edit</button>
                        <button onclick="deleteUser(${id})" style="color:red;">Delete</button>
                    </td>
                `);
            } else {
                // ADD ROW
                $('#userTable').append(`
                    <tr id="userRow${res.user.id}">
                        <td>${res.user.id}</td>
                        <td>${res.user.email}</td>
                        <td>${res.user.name}</td>
                        <td>
                            <button onclick="editUser(${res.user.id}, '${res.user.email}', '${res.user.name}')" style="color:blue;">Edit</button>
                            <button onclick="deleteUser(${res.user.id})" style="color:red;">Delete</button>
                        </td>
                    </tr>
                `);
            }

            resetForm();
        }
    });
});

/* ================= EDIT ================= */
function editUser(id, email, name){
    editMode = true;

    $('#formTitle').text('Update User');
    $('#submitBtn').text('Update');

    $('#user_id').val(id);
    $('#email').val(email);
    $('#name').val(name);
    $('#password').val('');
}

/* ================= DELETE ================= */
function deleteUser(id){
    if(confirm("Delete this user?")){
        $.ajax({
            url: '/delete-user/' + id,
            type: 'DELETE',
            success: function(res){
                $('#message').text(res.message);
                $('#userRow' + id).remove();
            }
        });
    }
}

/* ================= RESET FORM ================= */
function resetForm(){
    editMode = false;

    $('#formTitle').text('Add User');
    $('#submitBtn').text('Submit');

    $('#userForm')[0].reset();
    $('#user_id').val('');
}
</script>

</body>
</html>