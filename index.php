<!DOCTYPE html>
<html>
    <head>
        <title>Тестовое задание //Неграш Андрей</title>
    </head>
    <body>
        <div id="messages">
            Введите текст в поле ниже и нажмите кнопку "Отправить"
        </div>
        <form id="form">
            <textarea name="text" id="textarea" placeholder="Введите текст здесь" rows="4"></textarea><br>
            <input type="submit" id="submit-btn">
        </form>
        <br>
        <button id="get-history">Загрузить историю запросов</button>
        <div id="history"></div>
        <input type="hidden" id="first_time" value="0">
    </body>
    <style>
        #messages b {
            color: red;
        }
    </style>
    <script>
        document.getElementById("textarea").onchange = async (e) => {
            if (first_time.value == 1) {
                let response = await fetch('./scripts.php?q=check_string&to_history=0', {
                    method: 'POST',
                    body: new FormData(form)
                });
                let result = await response.json();
                
                messages.innerHTML = result.message;
            }
        };
        
        document.getElementById("form").onsubmit = async (e) => {
            e.preventDefault();
            first_time.value = 1;
    
            let response = await fetch('./scripts.php?q=check_string&to_history=1', {
                method: 'POST',
                body: new FormData(form)
            });
            let result = await response.json();
            
            messages.innerHTML = result.message;
        };
        
        document.getElementById("get-history").onclick = async (e) => {
            let response = await fetch('./scripts.php?q=get_history', {
                method: 'GET'
            });
            let result = await response.text();
            
            document.getElementById("history").innerHTML = result;
        }
</script>
</html>
