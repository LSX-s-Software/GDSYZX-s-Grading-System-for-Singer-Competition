<!DOCTYPE  html>
<html>
<head>
    <meta name="viewport" content="width=device-width,  initial-scale=1.0,  maximum-scale=1.0,  minimum-scale=1.0,  user-scalable=no" />
    <meta charset="utf-8" />
    <style>
        .footer {
            color: #fff;
            background-color: #312e2e;
            height: 30px;
            line-height: 30px;
            margin: 0;
            padding: 0;
            clear: both;
            position: absolute;
            bottom: 0px;
            width: 100%;
        }

        input {
            text-align: center;
            width: 90%;
            border: 1px solid #ccc;
            padding: 7px 0px;
            border-radius: 3px;
            padding-left: 5px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s
        }

        table {
            padding-left: 5px;
            padding-right: 5px;
        }

        button {
            background: #D0EEFF;
            border: 1px solid #99D3F5;
            border-radius: 4px;
            padding: 4px 12px;
            overflow: hidden;
            color: #1E88C7;
            font-size: large;
            width: 25%
        }

            button:hover {
                background: #AADFFD;
                border-color: #78C3F3;
                color: #004974;
                text-decoration: none;
            }

        input:focus {
            border-color: #66afe9;
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)
        }
    </style>
    <title>歌手大赛结果查看</title>
    <script src="jquery202.js"></script>
    <script src="jquery.growl.js"></script>
    <link href="jquery.growl.css" rel="stylesheet" type="text/css" />
    <script>
                var  str  =  "";
                $.ajaxSetup({
                        async:  false
                });
                var  i  =  1;
                var  j  =  1;
                var  ba  =  0;
                var  t=0;
                var  id  =  0;
                var  turn  =  "";
                function  restore()  {
                        $.get("server.php?turn="+turn+"&cmd=restoreall",  function  (data)  {
                                str  =  data;
                        });
                }
                $(document).ready(function  ()  {
                        $.get("t.php?"  +  Math.random(),  function  (data)  {
                                turn  =  data;
                        });
                        $(".h2").text("歌手结果查看(第" + turn + "轮)");
                        $.growl.warning({ title: "正在计算分数", message: "请稍等..." });
                        restore();
                        var c = 0;
                        var cc =0;
                        var  strs  =  new  Array();
                        var T = new Array();
                        for (i = 0; i <= 8; i++) {
                            T[i] = 0;
                        }
            strs = str.split(":");
            if (turn == "1") {
                for(t=1;t<=8;t++){
                    for(i=1;i<=5;i++){
                        for(j=0;j<=2;j++){
                            if(i<=2){
                                c = parseFloat(strs[(i - 1) * 24 + j + ba]);
                                cc = Math.round(c * 100*0.3) / 100/2;
                                T[t] += cc;
                            }
                            if(i>2){
                                c = parseFloat(strs[(i-1)*24+j+ba]);
                                cc = Math.round(c * 100 * 0.7) / 100/3;
                                T[t] += cc;
                            }
                        }
                    }
                    ba+=3;
                }
                $("#1").val(T[1]);
                $("#2").val(T[2]);
                $("#3").val(T[3]);
                $("#4").val(T[4]);
                $("#5").val(T[5]);
                $("#6").val(T[6]);
                $("#7").val(T[7]);
                var max = 1;
                for (i = 1; i <= 3; i++) {
                    if(T[i] >= T[max]){
                        max = i;
                    }
                }
                $("body").append("<br><center>"+"第一组最高分选手序号：" + max+"<br /><center />");
                max = 1;
                for (i = 1; i <= 3; i++) {
                    if (T[i+3] >= T[max+3]) {
                        max = i;
                    }
                }
                $("body").append("<br><center>" + "第二组最高分选手序号：" + max + "<br /><center />");
            }
            if (turn == "2") {
                for (t = 1; t <= 3; t++) {
                    for (i = 1; i <= 5; i++) {
                        for (j = 0; j <= 2; j++) {
                            if (i <= 2) {
                                c = parseFloat(strs[(i - 1) * 9 + j + ba]);
                                cc = Math.round(c * 100 * 0.3) / 100/2;
                                T[t] += cc;
                            }
                            if (i > 2) {
                                c = parseFloat(strs[(i - 1) * 9 + j + ba]);
                                cc = Math.round(c * 100 * 0.7) / 100/3;
                                T[t] += cc;
                            }
                        }
                    }
                    ba += 3;
                }
                $("#1").val(T[1]);
                $("#2").val(T[2]);
                $("#3").val(T[3]);
                var max = 1;
                for (i = 1; i <= 3; i++) {
                    if (T[i] >= T[max]) {
                        max = i;
                    }
                }
                $("body").append("<br><center>" + "冠军序号：" + max + "<br /><center />");
            }
        });
    </script>
</head>
<body style="background-color:lightgrey;font-family:'Microsoft YaHei','Times New Roman', Times, serif" leftmargin="0">
    <center>
        <h2 class="h2">歌手大赛结果查看</h2>
        <div class="main">
            <?php
            error_reporting(0);
            $servername = "localhost:3306";
            $username = "root";
            $password = "root";
            $con = mysql_connect($servername,$username,$password);
            if (!$con)
            {
                die('连接失败，请刷新页面重试：' . mysql_error());
            }
            mysql_select_db("score", $con);
            ?>
            <form>
                <table border="1" style="text-align:center">
                    <tr>
                        <th width="20px">序号</th>
                        <th width="70px">姓名</th>
                        <th>总分</th>
                    </tr>
                    <?php
                    $i=0;
                    error_reporting(0);
                    $result = mysql_query("SELECT * from s",$con);
                    while ($property = mysql_fetch_field($result))
                    {
                        $cache=$property->name;
                        if($i==4){echo "<tr><td colspan='3'>第2组</td></tr>";}
                        if($i==7){echo "<tr><td colspan='3'>第3组</td></tr>";}
                        if($i!=0&&$i!=8){
                            $id_1 = $i*10 + 1;
                            $id_2 = $i*10 + 2;
                            $id_3 = $i*10 + 3;
                            $id_4 = $i*10 + 4;
                            echo "<tr><td>$i</td>
                              <td>$cache</td>
                              <td><input type='text' id='$i' readonly='true' maxlength='6'/></td>
                          </tr>";
                        }
                        if($i==0){echo "<tr><td colspan='3'>第1组</td></tr>";}
                        $i++;
                    }
                    mysql_close($con);
                    ?>
                </table>
            </form>
        </div>

        <!----------Footer---------->
        <!--<div class="footer">
            ©2017 广东实验中学学生会信息部
        </div>-->
    </center>
</body>
</html>