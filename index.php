<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta charset="utf-8" />
    <link href="jquery.growl.css" rel="stylesheet" type="text/css" />
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
        input{
            text-align:center;
            width:90%;
            border: 1px solid #ccc;
            padding: 7px 0px;
            border-radius: 3px;
            padding-left:5px;
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
        button{
            background: #D0EEFF;
            display:inline;
            border: 1px solid #99D3F5;
            border-radius: 4px;
            padding: 4px 12px;
            overflow: hidden;
            color: #1E88C7;
            font-size:large;
            width:25%
        }
        .last{
             width:auto;
        }
        .next{
             width:auto;
        }
        button:hover {
            background: #AADFFD;
            border-color: #78C3F3;
            color: #004974;
            text-decoration: none;
        }
        input:focus{
                    border-color: #66afe9;
                    outline: 0;
                    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
                    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6)
        }
        img{
            width:100%;
            height:85%;
        }
    </style>
    <title>歌手大赛评分系统</title>
    <script src="jquery202.js"></script>
    <script src="jquery.growl.js" type="text/javascript"></script>
    <script>
        var str = "";
        $.ajaxSetup({
            async: false
        });
        var i = 1;
        var j = 1;
        var id = 0;
        var sum = 0;
        var turn = "";
        function restore() {
            $.get("server.php?turn="+turn+"&user=" + $(".user").val() + "&cmd=restore", function (data) {
                str = data;
            });
        }
        function save(a) {
            var user = $(".user").val();
            var d = $("form").serialize();
            if (str == "") {
                $.get("server.php?turn=" + turn +"&user=" + user + "&cmd=insert&" + d, function (data) {
                    if (!a) {
                        $(".save").text("保存");
                        if (data == "保存成功！") {
                            $.growl.notice({ title: data, message: "" });
                        } else {
                            $.growl.error({ title: data, message: "" });
                        }
                    } else {
                        if (data == "保存成功！") {
                            $.growl.notice({ title: "自动保存成功！", message: "" });
                        } else {
                            $.growl.error({ title: "自动" + data, message: "" });
                        }
                    }
                }, "text");
            } else {
                $.get("server.php?turn=" + turn +"&user=" + user + "&cmd=update&" + d, function (data) {
                    if (!a) {
                        $(".save").text("保存");
                        if (data == "保存成功！") {
                            $.growl.notice({ title: data, message: "" });
                        } else {
                            $.growl.error({ title: data, message: "" });
                        }
                    } else {
                        if (data == "保存成功！") {
                            $.growl.notice({ title: "自动保存成功！", message: "" });
                        } else {
                            $.growl.error({ title: "自动"+data, message: "" });
                        }
                    }
                }, "text");
            }
        }
        function cal() {
            for (i = 1; i <= 8; i++) {
                for (j = 1; j <= 3; j++) {
                    id = i * 10 + j;
                    sum = sum + parseInt($("#" + id).val());
                }
                $("#" + i).val(sum);
                sum = 0;
            }
        }
        $.growl.settings={duration:2000}
        $(document).ready(function () {
            var winHeight = 0;
            winHeight = document.documentElement.clientHeight * 0.83;
            $(".slide-item").css("height",winHeight +"px");
            $.get("t.php?"+Math.random(), function (data) {
                turn = data;
            });
            $(".h2").text("歌手大赛决赛(第"+turn+"轮)");
            $(".submit").click(function () {
                if ($(".user").val() != "") {
                    $(".login").fadeOut("fast", function () {
                        //$(".main").fadeIn();
                        //$(".main").css("display", "fixed");
                        $(".slide-box").fadeIn();
                        $(".slide-box").css("display", "-webkit-box");
                        $.growl.notice({title: "登录成功" , message: "欢迎评委" + $(".user").val() + "！" });
                        restore();
                        if (str != "") {
                            var strs = new Array();
                            var c1 = 0;
                            var c2 = 0;
                            var c3 = 0;
                            var c = -3;
                            strs = str.split(":");
                            for (i = 1; i <= 8; i++) {
                                c1 = i * 10 + 1;
                                c2 = i * 10 + 2;
                                c3 = i * 10 + 3;
                                c = c + 3;
                                $("#" + c1).val(strs[c]);
                                $("#" + c2).val(strs[c + 1]);
                                $("#" + c3).val(strs[c + 2]);
                            }
                            cal();
                            window.setInterval(function () {
                                save(true);
                            }, 60000);
                        }
                    });
                }
            });
            $("input").keyup(' input propertychange ', function () {
                cal();
            });
            $(".save").click(function () {
                $(".save").text("正在保存...");
                $("#100").text("");
                window.setTimeout(function () {
                    save(false);
                }, 50);
            });
            $(".user").keypress(function (event) {
                if (event.keyCode == 13) {
                    $(".submit").trigger("click");
                }
            });
            $(".next").click(function(){
                $("slide-box").scrollLeft(100);
            });
        });
    </script>
</head>
<body style="background-color:lightgrey;font-family:'Microsoft YaHei','Times New Roman', Times, serif" leftmargin="0">
    <center>
        <h2 class="h2">歌手大赛评分系统</h2>
        <!----------Code For Login---------->
        <div class="login">
            <br />
            <h4>评委编号</h4>
            <input type="number"  class="user" style="font-size:xx-large;width:50%"/>
            <br />
            <br />
            <button class="submit" type="submit">登录</button>
        </div>
        <!---------------------------------->
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
                        <th width="40px">姓名</th>
                        <th>音准(15)</th>
                        <th>舞台表演(20)</th>
                        <th>演唱情感(15)</th>
                        <th>总分(50)</th>
                    </tr>
                    <?php
                $i=0;
                error_reporting(0);
                $result = mysql_query("SELECT * from s",$con);
                while ($property = mysql_fetch_field($result))
                {
                    $cache=$property->name;
                    if($i==4){echo "<tr><td colspan='6'>第2组</td></tr>";}
                    if($i==7){echo "<tr><td colspan='6'>第3组</td></tr>";}
                    if($i!=0){
                        $id_1 = $i*10 + 1;
                        $id_2 = $i*10 + 2;
                        $id_3 = $i*10 + 3;
                        $id_4 = $i*10 + 4;
                        echo "<tr><td>$i</td>
                              <td>$cache</td>
                              <td><input type='number' id='$id_1' name='$id_1'/></td>
                              <td><input type='number' id='$id_2' name='$id_2'/></td>
                              <td><input type='number' id='$id_3' name='$id_3'/></td>
                              <td><input type='text' id='$i' readonly='true'/></td>
                          </tr>";
                    }
                    if($i==0){echo "<tr><td colspan='6'>第1组</td></tr>";}
                    $i++;
                }
                mysql_close($con);
                    ?>
                </table>
            </form>
            <h5 style="text-align:center" id="100"></h5>
            <button class="save">保存</button>
        </div>

        <!----------Footer---------->
        <div class="footer">
            ©2017 广东实验中学学生会信息部
        </div>
    </center>
</body>
</html>