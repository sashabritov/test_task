<?php
    error_reporting(E_ALL);
    $dataFile = 'data.xml';
    require('acceptData.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css">
        <title>Contact us</title>
    </head>
    <body>
        <h2 align="center">Зв'яжіться з нами</h2>
        <div align="center">
            <form method="POST" name="handler" action="" enctype="multipart/form-data">
                <p>
                    <input type="text" name="name" placeholder="Введіть ім'я">
                </p>
                <p>
                    <input type="text" name="surname" placeholder="Введіть прізвище">
                </p>
                <p>
                    <input type="text" name="email" placeholder="Введіть електронну пошту">
                </p>
                <p>
                    <input type="tel" name="phone" placeholder="Введіть телефон">
                </p>
                <p>
                    <input type="file" name="photo" placeholder="Фото">
                </p>
                <p>
                    <input type="submit">
                </p>
            </form>
        </div>
        <?php
            require('displayDataTable.php');
        ?>
    </body>
    <script type="text/javascript" src="tablesort.min.js"></script>
    <script>
        new Tablesort(document.getElementById('table-id'));
    </script>
</html>