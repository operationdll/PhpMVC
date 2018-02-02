<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Client</title>
    <!-- Bootstrap  CSS file -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link href="http://localhost/Demo/public/js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
</head>
<body>
<div class="container">
    <div class="row">
        </br></br></br>
    </div>
    <div class="col-lg-4"></div>
    <div class="col-12 col-sm-12 col-lg-4">
        <?php
            echo "<h2>Name:".$data['user']->nickname."</h2>";
        ?>
        <p>Please select the query time period</p>
        <form action="statistical" method="post" enctype="multipart/form-data">
            <?php
            echo "<input id='uid' name='uid' type='hidden' value='".$data['user']->id."'>";
            ?>
            <div class="form-group">
                <div class="controls input-append date form_datetime" placeholder="Start time" data-date-format="dd MM yyyy - HH:ii p" data-link-field="stime">
                    <input placeholder="Start time" class="form-control" size="16" type="text" value="" readonly>
                    <span class="add-on"><i class="icon-remove"></i></span>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <input type="hidden" id="stime" name="stime" value="" />
            </div>
            <div class="form-group">
                <div class="controls input-append date form_datetime" placeholder="Start time" data-date-format="dd MM yyyy - HH:ii p" data-link-field="etime">
                    <input placeholder="End time" class="form-control" size="16" type="text" value="" readonly>
                    <span class="add-on"><i class="icon-remove"></i></span>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <input type="hidden" id="etime" name="etime" value="" />
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
    <div class="col-lg-4"></div>
</div>
<script src="http://localhost/Demo/public/js/jquery.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://localhost/Demo/public/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script>
    $('.form_datetime').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $(function(){
        $("form:first").submit(function(){
            var stime=$("#stime").val();
            if(""==stime){
                alert('Start time not be empty!');
                return false;
            }
            var etime=$("#etime").val();
            if(""==etime){
                alert('End time not be empty!');
                return false;
            }
        });
    });
</script>
</body>
</html>
