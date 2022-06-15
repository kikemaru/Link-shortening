<?php
header("Content-type: text/html; charset=uft-8");
session_start();
if (!isset($_SESSION['name']))
    header("Location: ../");
require_once '../class/MainClass.php';
require_once '../db_connect.php';
$login = $_SESSION['name'];
$uid = $db->query("SELECT * FROM users WHERE login = '$login'")->fetch();
$uid_user = $uid['id'];
$class = new MainClass($uid_user);

if ($_POST['func'] == 'short'){
    $link = $_POST['link'];
    $class->ShortLink($link);
    header("location: ./?page=main");

} elseif ($_GET['delete'] == 'link'){
    $dlink = $_GET['link'];
    $class->DeleteLink($dlink);
    header("location: ./?page=main&delete=success");
}

if (isset($_GET['exit'])){
    $class->UserQuit();
}

if ($_GET['statusup'] == 'on'){
    $idl = $_GET['link'];
    $class->UpdateStatus(0, $idl);
} elseif ($_GET['statusup'] == 'off'){
    $idl = $_GET['link'];
    $class->UpdateStatus(1, $idl);
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <title>Личный кабинет</title>
</head>
<body>
<center>
    <nav class="navbar navbar-inverse" style="max-width: 800px; margin-top: 40px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="collapsed navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand">LinkShorter</a>
            </div> <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-9">
                <ul class="nav navbar-nav">
                    <li <?php if ($_GET['page'] == 'main' || !isset($_GET['page']) || $_GET['page'] == 'log'){ echo 'class="active"';}?>><a href="./?page=main">Главная</a></li>
                    <li <?php if ($_GET['page'] == 'setting'){ echo 'class="active"';}?>><a href="./?page=setting">Настройки</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="./?exit">Выйти</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <p style="margin-right: 665px;"><strong>Сократить ссылку:</strong></p>
    <form method="POST" action="./">
        <input type="hidden" name="func" value="short">
        <div class="input-group" style="max-width: 800px;">
            <input type="text" name="link" required class="form-control" placeholder="http://">
            <span class="input-group-btn">
                <input class="btn btn-default" type="submit" value="Short!">
            </span>
        </div>
    </form><br>
    <?php if ($_GET['page'] == 'main' || !isset($_GET['page'])){ ?>
        <?php if ($_GET['delete'] == 'success'){ echo '<div class="alert alert-success" role="alert" style="max-width: 800px;">Ссылка успешно удалена!</div>'; }
        if ($_GET['status'] == 'off'){ echo '<div class="alert alert-warning" role="alert" style="max-width: 800px;">Статус успешно изменен. Ссылка отключена!</div>'; }
        if ($_GET['status'] == 'on'){ echo '<div class="alert alert-warning" role="alert" style="max-width: 800px;">Статус успешно изменен. Ссылка включена!</div>'; }?>
        <table class="table table-striped" style="max-width: 800px;">
            <thead>
            <th>Ссылка</th>
            <th>Redirect</th>
            <th>Лог</th>
            <th>Статус</th>
            <th></th>
            </thead>
            <tbody>
            <?php
            $rs = $db->query("SELECT * FROM link WHERE id_users = '$uid_user'");
            $rs->execute();
            while($res = $rs->fetch(PDO::FETCH_BOTH)){
                $short = $res['code_link'];
                $mainlink = $res['redirect'];
                $idlink = $res['id'];
                $status_link = $res['status'];
                $status = $class->GetStatus($status_link);
                echo '
          <tr>
          <td>http://localhost/-'.$short.'</td>
          <td>'.$mainlink.'</td>
          <td><a href="./?page=log&id='.$idlink.'">show log</a></td>
          <td>'.$status.'</td>';
                if ($status_link == 0) {
                    echo '
          <td><a href="./?statusup=off&link=' . $idlink . '"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a> &nbsp;&nbsp;
          <a href="./?delete=link&link=' . $idlink . '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
          </tr>';
                } elseif ($status_link == 1){
                    echo '
          <td><a href="./?statusup=on&link=' . $idlink . '"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a> &nbsp;&nbsp;
          <a href="./?delete=link&link=' . $idlink . '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
          </tr>';
                } else {
                    echo '
          <td>
          <a href="./?delete=link&link=' . $idlink . '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
          </tr>';
                }
            }
            ?>
            </tbody>
        </table>
    <?php } elseif ($_GET['page'] == 'log'){ ?>
        <?php
        $logid = $_GET['id'];
        $rslink = $db->query("SELECT * FROM link WHERE id = '$logid'")->fetch();
        $hashlink = $rslink['code_link'];

        $query=$db->query("SELECT COUNT(*) as count FROM log WHERE id_link = '$logid'");
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $row=$query->fetch();
        $count=$row['count'];
        ?>
        <div class="panel panel-info" style="max-width: 800px;">
            <div class="panel-heading">
                <h3 class="panel-title" style="text-align: left;"><?php echo "Лог ссылки <b>$hashlink</b> | количество переходов - ($count)"; ?></h3>
            </div>
            <div class="panel-body" style="text-align: left;">
                <?php echo ''.$login.' >> log show -> id ('.$logid.') '; ?><br><br>
                <?php
                $rslog = $db->query("SELECT * FROM log WHERE id_link = '$logid'");
                $rslog->execute();
                while($res = $rslog->fetch(PDO::FETCH_BOTH)){
                    $iplog = $res['ip'];
                    $reflog = $res['referer'];
                    $clientlog = $res['client'];
                    $datelog = $res['date_time'];
                    echo '<small>
                    [ip visitor] -> '.$iplog.'<br>
                    [from] -> '.$reflog.'<br>
                    [client] -> '.$clientlog.'<br>
                    [datetime] -> '.$datelog.'
                    </small> <br> <hr>';
                }
                ?>
            </div>
        </div>
    <?php } elseif ($_GET['page'] == 'setting'){ ?>
    <?php } ?>
</center>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>