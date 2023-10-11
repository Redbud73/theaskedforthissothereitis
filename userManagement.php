<?php session_start(); require_once("modules/db_open.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление пользователями</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
        crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/userManagement.css">
    <link rel="stylesheet" href="css/navigationBar.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    <?php 
    $userName = $_SESSION["username"];
    $access = $_SESSION["access"];

    if (!$userName) { 
        header("Location: authorizationPage.php");
    } else { ?>

    <div id="user_panel"></div>
    <div id="navigation"></div>

    <table id="editing_table">
        <tr>
          <th class="employee_id_header">ID</th>
          <th class="employee_name_header">Имя сотрудника</th>
          <th class="username_header">Имя пользователя</th>
          <th class="password_header">Пароль</th>
          <th class="access_header">Уровень доступа</th>
        </tr>

        <?php
        $query = "SELECT * FROM $employees_table";
        $query_result = mysqli_query($conn,$query);
        
        $counter = 0;
        
        while ($row = mysqli_fetch_assoc($query_result)) {
            $counter++;
            $employee_id   = $row["employee_id"];
            $employee_name = $row["employee_name"];
            $username = $row["username"];
            $password = $row["password"];
            $access_level = $row["access"];

            echo "
            <tr id='row_$counter'>
                <td class='employee_id_holder'>
                    $employee_id
                </td>
                <td class='employee_name_holder'>
                    $employee_name
                </td>
                <td class='username_holder'>$username</td>
                <td class='password_holder'>$password</td>
                <td class='access_holder'>$access_level</td>
                <td class='edit_holder'><button class='btn key_sign_button' onclick='editCredits($counter)'><span class='material-symbols-outlined key_sign'>key</span></button></td>
            </tr>";
        } ?>
        
    </table>
    <?php } ?>
</body>
<script>
    $(function(){
        $.ajax({
        type: "POST",
        url: "modules/loadNavigationBar.php",
        data: {
            access: '<?php echo $access?>',
        }, 
        success: function(data){
            $("#navigation").html(data)
        }
        })

        $.ajax({
        type: "POST",
        url: "modules/loadUserPanel.php",
        data: {
            username: '<?php echo $userName?>',
        }, 
        success: function(data){
            $("#user_panel").html(data)
        }
        })
    })

    function goToMenu(){
        window.location.href = "index.php";
    }

    function editCredits(counter){
        var userID = document.getElementById("row_" + counter).getElementsByClassName("employee_id_holder")[0].innerText;
        var username = prompt("Введите новое имя пользователя:")
        var password = prompt("Введите пароль:")
        var access = prompt("Введите уровень доступа:", "1/2/3")

        $.ajax({
            type: "POST",
            url: "modules/saveCredentials.php",
            data: {
                userID: userID,
                username: username,
                password: password,
                access: access, 
            },
            success: function(data){
                location.reload()
            }
        })
    }
</script>
</html>