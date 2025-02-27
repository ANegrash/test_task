<?
    // check credentials for db connecting in functions: saveToHistory(), getHistory()
    if (isset($_POST) && isset($_GET)) {
        $q = $_GET['q'];
        
        if ($q == "check_string") {
            $text = $_POST['text'];
            $to_history = $_GET['to_history'];
            
            if ($text) {
                if ($to_history)
                    saveToHistory($text);
                
                $ru_array = mb_str_split("АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя");
                $en_array = mb_str_split("AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz");
                $text_array = mb_str_split($text);
                
                $result_string = "";
                
                $lang_code = chooseLang($text_array, $ru_array, $en_array); //en - 1, ru - 2
                
                $errors_count = 0;
                
                for ($i = 0; $i < count($text_array); $i++) {
                    if ($lang_code == 1) {
                        if (in_array($text_array[$i], $ru_array)) {
                            $result_string .= "<b>".$text_array[$i]."</b>";
                            $errors_count++;
                        } else
                            $result_string .= $text_array[$i];
                    } else if ($lang_code == 2) {
                        if (in_array($text_array[$i], $en_array)) {
                            $result_string .= "<b>".$text_array[$i]."</b>";
                            $errors_count++;
                        } else
                            $result_string .= $text_array[$i];
                    } else
                        $result_string .= $text_array[$i];
                }
                
                if ($errors_count)
                    sendAnswer($result_string);
                else
                    sendAnswer("В строке все символы из ".($lang_code == 1 ? "английского" : "русского")." алфавита");
            } else
                sendAnswer("Ошибка: отправлена пустая строка");
        } else if ($q == "get_history")
            echo getHistory();
    } else
        sendAnswer("Ошибка: переданы неверные данные");
    
    function chooseLang($text_array, $ru_array, $en_array) {
        $ru_count = 0;
        $en_count = 0;
        
        foreach ($text_array as $symbol) {
            if (in_array($symbol, $ru_array))
                $ru_count++;
            
            if (in_array($symbol, $en_array))
                $en_count++;
        }
        
        if ($en_count > $ru_count)
            return 1;
        return 2;
    }
    
    function saveToHistory($text) {
        $hostname = "...";
        $username = "..."; 
        $password = "..."; 
        $database = "test_task"; 
        $link = mysqli_connect($hostname, $username, $password, $database);
        
        mysqli_query($link, "
            INSERT INTO `history` (`text`, `timestamp`) VALUES ('".mysqli_real_escape_string($link, $text)."', CURRENT_TIMESTAMP)
        ");
    }
    
    function getHistory() {
        $hostname = "...";
        $username = "..."; 
        $password = "..."; 
        $database = "test_task";
        $link = mysqli_connect($hostname, $username, $password, $database);
        
        $result = mysqli_query($link, "
            SELECT 
                id_history, 
                text, 
                timestamp 
            FROM 
                history 
            ORDER BY 
                timestamp DESC
        ");
        
        $answer = "<table><tr><td>Id</td><td>Строка</td><td>Дата</td></tr>";
        
        while ($row = mysqli_fetch_array($result)) {
            $answer .= "
                <tr>
                    <td>".$row['id_history']."</td>
                    <td>".$row['text']."</td>
                    <td>".$row['timestamp']."</td>
                </tr>
            ";
        }
        $answer .= "</table>";
        
        return $answer;
    }
    
    function sendAnswer($data) {
        echo json_encode(array('message' => $data));
    }
?>
