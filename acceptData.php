<?php
    if(!empty($_POST['deleteitem']))
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

    if(!empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_FILES['photo']))
    {
        $imageExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        if(!file_exists('saved_pictures') || !is_dir('saved_pictures'))
        {
            mkdir('saved_pictures');
        }
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

        // Write data to file
        $data = new DOMDocument("1.0", "utf-8");
        if(!file_exists($dataFile) || filesize($dataFile) == 0)
        {
            $file = fopen($dataFile, 'w');
            fwrite($file, '<?xml version="1.0" encoding="utf-8"?>');
            fwrite($file, '<appeals></appeals>');
            fclose($file);
        }
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

        $id = $data->getElementsByTagName('appeal')->length + 1;
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