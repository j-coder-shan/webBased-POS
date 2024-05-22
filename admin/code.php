<?php

include('../config/function.php');

if(isset($_POST['saveAdmin'])) {
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1 : 0;

    if($name != '' && $email != '' && $password != '' ) {
        
        $emailcheck = mysqli_query($conn, "SELECT * FROM admins WHERE email = '$email'");
        if($emailcheck){
            if(mysqli_num_rows($emailcheck) > 0){
                redirect('admins-create.php', 'Email already exists');
            }
        }

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'is_ban' => $is_ban				
        ];

        $result = insert('admins', $data);

        if($result){
            redirect('admins.php', 'Admin added successfully', 'success');
        } else {
            redirect('admins-create.php', 'Error while adding admin', 'error');
        }


    } else {
        redirect('admins-create.php', 'Please fill all the fields');
    }
}

?>