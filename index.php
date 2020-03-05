<?php
    //error_reporting(E_ERROR);
    $dataFile = 'data.xml';

    if(($_POST['deleteitem']))
    {
        $xml = new DOMDocument('1.0', 'uft-8');
        $xml->load($dataFile);
        $nodeToRemove = null;
        $appeals = $xml->getElementsByTagName('appeal');
        foreach($appeals as $appeal)
        {
            $attrValue = $appeal->getAttribute('id');
            if ($attrValue == $_POST['deleteitem']) {
                $nodeToRemove = $appeal;
            }
        }
        if($nodeToRemove != null)
        {
            unlink($nodeToRemove->lastChild->nodeValue);
            $xml->documentElement->removeChild($nodeToRemove);
        }
        $xml->save($dataFile);
    }

    if(($_POST['name']) && ($_POST['surname']) && ($_POST['email']) && ($_POST['phone']) && ($_FILES['photo']))
    {
        $imageExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $imageFolder = 'saved_pictures/';
        $randomImageName = substr(md5(rand() + time()), 0, 15);
        $imageName = $imageFolder . $randomImageName . '.' . 'jpg';
        move_uploaded_file($_FILES['photo']['tmp_name'], $imageName);
        $imageSize = getimagesize($imageName);
        switch($imageExtension)
        {
            case "jpg":
                $imageResource = imagecreatefromjpeg($imageName);
            break;
            case "bmp":
                $imageResource = imagecreatefrombmp($imageName);
            break;
            case "png":
                $imageResource = imagecreatefrompng($imageName);
            break;
            case "gif":
                $imageResource = imagecreatefromgif($imageName);
            break;
        }
        $imageResource2 = imagecreatetruecolor(300, 300);
        imagecopyresized($imageResource2, $imageResource, 0, 0, 0, 0, 300, 300, $imageSize[0], $imageSize[1]);
        imagejpeg($imageResource2, $imageName, 75);

        // All data
        $data = new DOMDocument("1.0", "utf-8");
        $data->load($dataFile);
        $appeals = $data->getElementsByTagName('appeals')->item(0);
        if(!$appeals)
        {
            $appeals = $data->createElement('appeals');
            $data->appendChild($appeals);
        }
        $appeal = $data->createElement('appeal');
        $name = $data->createElement('name', $_POST['name']);
        $surname = $data->createElement('surname', $_POST['surname']);
        $email = $data->createElement('email', $_POST['email']);
        $phone = $data->createElement('phone', $_POST['phone']);
        $photo = $data->createElement('photo', $imageName);

        $id = $data->getElementsByTagName('appeal')->count() + 1;
        $appeal->setAttribute('id', $id);
        $appeal->appendChild($name);
        $appeal->appendChild($surname);
        $appeal->appendChild($email);
        $appeal->appendChild($phone);
        $appeal->appendChild($photo);

        $appeals->appendChild($appeal);
        $data->save($dataFile);

        imagedestroy($imageResource);
        imagedestroy($imageResource2);
    }
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
            $xml = simplexml_load_file($dataFile);
            $data = $xml->appeal;
            echo '<table id="table-id" class="table" border="1" align="center">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Імя</th>';
            echo '<th>Прізвище</th>';
            echo '<th>Електронна пошта</th>';
            echo '<th>Телефон</th>';
            echo '<th>Фото</th>';
            echo '<th></th>';
            echo '</tr>';
            echo '</thead>';
            foreach($data as $element)
            {
                echo '<tr>';
                echo '<td>' . $element->name . '</td>';
                echo '<td>' . $element->surname . '</td>';
                echo '<td>' . $element->email . '</td>';
                echo '<td>' . $element->phone . '</td>';
                echo '<td>' . '<img src="' . $element->photo . '"></td>';
                echo '<td>' . '<form method="POST"><input type="hidden" name="deleteitem" value="' . $element['id'] . '"><input type="submit" value="Видалити"></form>' . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        ?>
    </body>
    <script type="text/javascript" src="script.js"></script>
</html>