<?php
    error_reporting(0);
    $cmd=$_GET["cmd"];
    $user=$_GET["user"];
    $turn=$_GET["turn"];
    $con=mysql_connect("localhost:3306","root","root");
    mysql_select_db("score",$con);
    if(!$con){
        echo "数据库连接失败，请联系技术人员！";
        die("数据库连接失败，请联系技术人员！");
    }
    if($cmd=="restoreall"){
        $result=mysql_query("SELECT * FROM s ORDER BY id",$con);
        while($row = mysql_fetch_array($result))
        {
            if ($turn=="1"){
                echo $row[1];
                echo ":" . $row[2];
                echo ":" . $row[3];
                echo ":" . $row[4];
                echo ":" . $row[5];
                echo ":" . $row[6];
                echo ":" . $row[7];
                echo ":" . $row[8];
                echo ":";
            }
            if ($turn=="2"){
                echo $row[1];
                echo ":" . $row[2];
                echo ":" . $row[3];
                echo ":";
            }
        }
    }
    if($cmd=="insert"){
        for ($i=1;$i<=8;$i++){
            if($turn==2&&$i>=4){break;}
            $data[$i]=$data[$i].$_GET[$i*10+1].":".$_GET[$i*10+2].":".$_GET[$i*10+3];
        }
            $status=mysql_query("INSERT INTO s VALUES($user,'".implode("','",$data)."')",$con);
            if(!$status){
                echo "保存失败，请联系技术人员！".mysql_error();
                die("保存失败，请联系技术人员！".mysql_error());
            }
        echo "保存成功！";
    } elseif($cmd=="restore"){
        $result=mysql_query("SELECT * FROM s WHERE id = $user",$con);
        $row=mysql_fetch_array($result);
        for($i=1;$i<=(count($row)+1);$i++){
            echo $row[$i].":";
        }
    }elseif($cmd="update"){
        mysql_query("DELETE FROM s WHERE id = $user");
        for ($i=1;$i<=8;$i++){
            if($turn==2&&$i>=4){break;}
            $data[$i]=$data[$i].$_GET[$i*10+1].":".$_GET[$i*10+2].":".$_GET[$i*10+3];
        }
            $status=mysql_query("INSERT INTO s VALUES($user,'".implode("','",$data)."')",$con);
        if(!$status){
            echo "保存失败，请联系技术人员！".mysql_error();
            die("保存失败，请联系技术人员！"+mysql_error());
        }
        echo "保存成功！";
    }
    else{
        echo "错误的命令，请重试！";
    }
    mysql_close($con);
?>