<?php
    $xml = simplexml_load_file($dataFile);
    $data = $xml->appeal;
    if($data && $data->count() != 0)
    {
        echo '<table id="table-id" border="1" align="center">';
        echo '<thead>';
        echo '<tr>';
        echo '<th role="columnheader">Ім`я</th>';
        echo '<th role="columnheader">Прізвище</th>';
        echo '<th role="columnheader">Електронна пошта</th>';
        echo '<th role="columnheader">Телефон</th>';
        echo '<th data-sort-method="none">Фото</th>';
        echo '<th data-sort-method="none" role="columnheader">Видалення елементу</th>';
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
    }
?>