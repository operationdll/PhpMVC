<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Person</title>
    <!-- Bootstrap  CSS file -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <script src="http://localhost/Demo/public/js/jquery.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script>
        function details(id){
            window.location = "detail?id="+id;
        }
        $(function (){
            $(".pagination a").click(function(){
                var curPage = $("#page").val();
                if($(this).attr("page")=="pre"){
                    if(curPage!=1){
                        curPage = Number(curPage) -1;
                        $("#page").val(curPage);
                        $("form").submit();
                    }
                }else if($(this).attr("page")=="next"){
                    if(curPage!=<?=$data['totalPage']?>){
                        curPage = Number(curPage) +1;
                        $("#page").val(curPage);
                        $("form").submit();
                    }
                }else{
                    curPage = $(this).attr("page");
                    $("#page").val(curPage);
                    $("form").submit();
                }

            });
            $("form button").click(function(){
                $("#page").val(1);
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        </br></br></br>
    </div>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-12 col-sm-12 col-lg-5">
            <nav class="navbar navbar-light bg-light">
                <form class="form-inline" method="post">
                    <?php
                        echo "<input id='page' name='page' type='hidden' value='".$data['page']."'/>";
                        echo '<font size="4px"><b>Nick name:</b></font><input name="name" value="'.$data['xm'].'" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">';
                    ?>
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </nav>
        </div>
        <div class="col-lg-4"></div>
    </div>
    <div class="container">
        <h2>Users information</h2>
        <table class="table">
            <thead>
            <tr>
                <th>User id</th>
                <th>Nick name</th>
                <th>Email</th>
                <th>Login time</th>
                <th>Detail</th>
            </tr>
            </thead>
            <tbody id="clientList">
            <?php
                foreach($data['arr'] as $u){
                    echo "<tr>";
                    echo "   <td>";
                    echo        $u->id;
                    echo "   </td>";
                    echo "   <td>";
                    echo        $u->nickname;
                    echo "   </td>";
                    echo "   <td>";
                    echo        $u->email;
                    echo "   </td>";
                    echo "   <td>";
                    echo        $u->loginTime;
                    echo "   </td>";
                    echo "   <td>";
                    echo        "<a href=\"javascript:details(".$u->id.")\">Detail</a>";
                    echo "   </td>";
                    echo "</tr>";
                }
            ?>
            <tr>
                <td colspan="5" align="right">
                    <ul class="pagination">
                        <?php
                            if($data['totalPage']==1){
                                echo '<li class="disabled"><a href="#" page="pre">&laquo;</a></li>';
                                echo '<li class="active"><a href="#"  page="1">1</a></li>';
                                echo '<li class="disabled"><a href="#" page="next">&raquo;</a></li>';
                            }else{
                                for ($x=1; $x<=$data['totalPage']; $x++) {
                                    if($data['page']==1&&$x==1){
                                        echo '<li class="disabled"><a href="#" page="pre">&laquo;</a></li>';
                                        echo '<li class="active"><a href="#"  page="1">1</a></li>';
                                    }
                                    else if($data['page']==$data['totalPage']&&$x==$data['totalPage']){
                                        echo '<li class="active"><a href="#"  page="'.$x.'">'.$x.'</a></li>';
                                        echo '<li class="disabled"><a href="#" page="next">&raquo;</a></li>';
                                    }
                                    else{
                                        if($x==1){
                                            echo '<li><a href="#" page="pre">&laquo;</a></li>';
                                        }
                                        if($x==$data['page']){
                                            echo '<li class="active"><a href="#" page="'.$x.'">'.$x.'</a></li>';
                                        }else{
                                            echo '<li><a href="#" page="'.$x.'">'.$x.'</a></li>';
                                        }
                                        if($x==$data['totalPage']){
                                            echo '<li><a href="#" page="next">&raquo;</a></li>';;
                                        }
                                    }
                                }
                            }
                        ?>
                    </ul>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

