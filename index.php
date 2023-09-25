<?php
if (!is_dir("lembretes"))
    mkdir("lembretes");


if (isset($_GET['apagar'])) {
    if (file_exists('lembretes/' . $_GET['apagar'] . '.txt')) {
        unlink('lembretes/' . $_GET['apagar'] . '.txt');
    }
    if (file_exists('lembretes/hora_' . $_GET['apagar'] . '.hora')) { 
        unlink('lembretes/hora_' . $_GET['apagar'] . '.hora'); 
    }
    if (file_exists('lembretes/' . $_GET['apagar'] . '.cor')) { 
        unlink('lembretes/' . $_GET['apagar'] . '.cor'); 
    }
    echo '<script>window.location.href = "index.php";</script>';
}


if (isset($_POST['lembrete'])) {
    $index = 0;
    $boleta = true;
    while ($boleta) {
        if (file_exists('lembretes/' . $index . '.txt')) {
            $index++;
        } else {
            $boleta = false;
        }
    }
    file_put_contents('lembretes/' . $index . '.txt', $_POST['lembrete']);

    date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário do Brasil

    $dataAtual = new DateTime();
    $dataFormatada = $dataAtual->format('d/m/Y - H:i:s');

    file_put_contents('lembretes/hora_' . $index . '.hora',$dataFormatada);

    file_put_contents('lembretes/' . $index . '.cor', $_POST['cor']);
    echo '<script>window.location.href = "index.php";</script>';
}


function LerLembretes()
{
    $directory = 'lembretes';
    $files = scandir($directory);

    if ($files !== false) {
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
                $lembrete = file_get_contents('lembretes/' . $file);
                $horarioPost = file_get_contents('lembretes/hora_' . pathinfo($file, PATHINFO_FILENAME).'.hora');
                $cor = file_get_contents('lembretes/' . pathinfo($file, PATHINFO_FILENAME).'.cor');
                echo '
                    <div style="background-color:'.$cor.'"  id="arq_' . pathinfo($file, PATHINFO_FILENAME) . '" class="anotacao">
                        <div style="background-color:'.$cor.'" class="xis" id="xis_' . pathinfo($file, PATHINFO_FILENAME) . '">X</div>'
                    . $lembrete .
                    '</div>
                    <div class="data">'.$horarioPost.'</div>
                    <center><hr style="width: 70%"></center>
                ';

                echo '
                    <script>
                            document.getElementById("xis_' . pathinfo($file, PATHINFO_FILENAME) . '").addEventListener("click",()=>{
                            
                            document.getElementById("arq_' . pathinfo($file, PATHINFO_FILENAME) . '").style.backgroundColor = "red";
                            document.getElementById("xis_' . pathinfo($file, PATHINFO_FILENAME) . '").style.backgroundColor = "red";
                            window.location.href = "index.php?apagar=' . pathinfo($file, PATHINFO_FILENAME) . '";
                            });
                        
                        
                          </script>
                ';
            }
        }
    } else {
        echo "Não foi possível ler o diretório.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    *{
        font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif
    }
    html {
        background-color: #2f2f2f;
        padding: 0px;
        user-select: none;
        
    }

    .data {
        text-align: right;
        margin-right: 8%;
        color: white;
        font-size: small;
    }

    input{
        border: none;
       width: 40px;
        background-color: transparent;
        height: 30px;
    }

    .estrutura {
        background-color: #666;

        margin-top: 50px;
        width: 90%;
        padding: 10px;
    }

    .campoTexto {
       
        color: white;
        width: 85%;
        border-radius: 5px;
        background-color: #333;
    }

    .butaum {
        padding: 2%;
        background-color: #333;
        margin-top: 3%;
        width: 85%;
        border: none;
        border-radius: 5px;
        color: white;
    }

    .anotacao {
        padding: 2%;
border-radius: 10px;
        margin-top: 3%;
        width: 80%;
        border: 1px dotted white;
        background-color: #333;
        color: black;
        max-width: 85%;
        word-wrap: break-word;
        font-size:large;
    }

    .xis {
        background-color: #333;
        text-align: right;
        color: black;
        
        float:right;
        margin-bottom: 12%;
        margin-left: 2%;
    }
    .xis:hover {
        color: red;
        cursor: pointer;
    }
    table{
        width: 100%;
    }
</style>

<body>
    <center>
        <div class="estrutura">
            <form action="" method="post">
                <table col="2">
                    <td>
                        <th><input id="lembrete" name="lembrete" type="text" class="campoTexto" placeholder="Digite aqui seu lembrete" required></th>
                        <th><input id="cor" name="cor" type="color" value="#85db8b"></th>
                    </td>
                </table>
                <button class="butaum">Adicionar</button>
            </form>
            <center><hr style="width: 70%"></center>

            <?php
            LerLembretes();
            ?>



        </div>
    </center>
</body>

</html>