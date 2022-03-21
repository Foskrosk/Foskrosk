<?php
    require __DIR__ . "../vendor/autoload.php";
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API PHP Quickstart');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig('../credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent'); 

    $service = new Google_Service_Sheets($client);
    $spreadsheetId = '1JUifJb7MjxGTpEaBi_Qv3t3mTuTBc1MelRUnmJ-ArGc';
    
    $range = 'AdData!A1:D';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
    var_dump($values);
?>
<html>

<body>
    <form action="/lab4/save.php" method="GET">
        <input type="email" name="email" placeholder="Email"></br></br>
		<input type="text" name="header" placeholder="Заголовок"></br></br>
		<textarea name="text" placeholder="Текст объявления"></textarea></br></br>
        <select name="category">
            <option selected>Категория</option>
            <option>Недвижимость</option>
            <option>Автомобили</option>
            <option>Услуги</option>
        </select></br></br>
        <input type="submit" value="Добавить">
    </form>
    <br>
    <?php
        $categories = scandir(__DIR__."/categories");
        $ads = [];
        foreach($categories as $category){
            if ($category != "." && $category != ".."){
                $ads[$category] = scandir("categories/$category");
            }
        };
        echo "<table border=1><caption>Объявления</caption>";
        foreach($ads as $category => $arr){

            echo "<tr><th colspan=3 align=center>" . ucfirst($category) . "</th></tr>";
            foreach($arr as $ad){
                if ($ad != "." && $ad != ".."){
                    $file = fopen("categories/$category/$ad", "r");
                    $email = fgets($file);
                    $header = fgets($file);
                    $adText = fgets($file);
                    echo "<tr><td>$email</td><td>$header</td><td>$adText</td></tr>";
                }
            }
        }
        echo "</table>";
    ?>
    <table>
    <tr><th>Категория</th><th>Email</th><th>Заголовок</th><th>Текст объявления</th></tr>
    <?php
    foreach($values as $adData){
        echo "<tr>" . "<td>" . ucfirst($adData[0]) . "</td>";
        echo "<td>" . $adData[1] . "</td>";
        echo "<td>" . $adData[2] . "</td>";
        echo "<td>" . $adData[3] . "</td>" . "</tr>";
    }
    ?>
    </table>
</body>
</html>