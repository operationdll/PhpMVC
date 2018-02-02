<?php

class Home extends Controller
{
    private $con;

    public function __construct()
    {
        require_once 'connect.php';
        $connect = new Connect();
        $this->con = $connect->getcon();
    }

    public function index()
    {
        $sql = 'SELECT * FROM USER';
        $xm = "";
        if (isset($_POST["name"]))
        {
            $xm = $_POST["name"];
            if(trim($xm)!=''){
                $sql = "SELECT * FROM USER WHERE nickname like '%".$xm."%'";
            }
        }
        $page = 1;
        if (isset($_POST["page"]))
        {
            $page = $_POST["page"];
        }

        $result = mysqli_query($this->con, $sql) or die('error getting data');
        //获取页数
        $total = mysqli_num_rows($result);
        $totalPage = ceil($total/5);
        $sql = $sql." limit ".(($page-1)*5).",5";
        $result = mysqli_query($this->con, $sql) or die('error getting data');

        $i = 0;
        $arr =  array();
        while( $row = mysqli_fetch_assoc($result) ){
            $user = $this->model('User');
            $user->id = $row['id'];
            $user->nickname = $row['nickname'];
            $user->email = $row['email'];
            $user->loginTime = date("Y-m-d H:i",$row['loginTime']);
            $arr[$i] = $user;
            $i++;
        }
        $this->view('home/index',['arr'=>$arr,'xm'=>$xm,'page'=>$page,'totalPage'=>$totalPage]);
    }

    public function detail(){
        $person = null;
        if (isset($_GET["id"]))
        {
            $id = $_GET["id"];
            $sql = "SELECT * FROM USER WHERE id =".$id;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            $user = $this->model('User');
            while( $row = mysqli_fetch_assoc($result) ){
                $user->id = $row['id'];
                $user->nickname = $row['nickname'];
                $user->email = $row['email'];
                $user->loginTime = date("Y-m-d H:i",$row['loginTime']);
            }
            $this->view('home/detail',['user'=>$user]);
        }
    }

    public function statistical(){
        if (isset($_POST["stime"]))
        {
            $stime = strtotime($_POST["stime"]);
            $etime = strtotime($_POST["etime"]);

            //练习题总数(The total number of exercises)
            $sql = "select".
                " t1.nickname,".
                " count(*) total".
                " from user t1 left join course_task_result t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " group by t1.nickname";
            $r1 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r1 = $row['total'];
            }
            //完成的练习总数(The total number of exercises completed)
            $sql = "select".
                " t1.nickname,".
                " count(*) total".
                " from user t1 left join course_task_result t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status='finished'".
                " group by t1.nickname";
            $r2 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r2 = $row['total'];
            }

            //未完成的练习数
            $sql = "select".
                " t1.nickname,".
                " count(*) total".
                " from user t1 left join course_task_result t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status <>'finished'".
                " group by t1.nickname";
            $r3 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r3 = $row['total'];
            }

            //完成练习的平均分
            $sql = "select".
                " t1.nickname,".
                " ROUND(avg(t3.score),2) score".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status='finished'".
                " group by t1.nickname";
            $r4 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r4 = $row['score'];
            }

            //完成练习的平均时间
            $sql = "select".
                " t1.nickname,".
                " ROUND(avg(t3.endTime-beginTime)) avgTime".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status='finished'".
                " group by t1.nickname";
            $r5 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r5 = date("H:i",$row['avgTime']);
            }

            //完成练习的最好分数
            $sql = "select".
                " t1.nickname,".
                " max(t3.score) bscore".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status='finished'".
                " group by t1.nickname";
            $r6 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r6 = $row['bscore'];
            }

            //完成练习的最差分数
            $sql = "select".
                " t1.nickname,".
                " min(t3.score) wscore".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status='finished'".
                " group by t1.nickname";
            $r7 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r7 = $row['wscore'];
            }

            //完成练习的错误题数
            $sql = "select".
                " t1.nickname,".
                " count(*) enum".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('wrong')".
                " group by t1.nickname";
            $r8 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r8 = $row['enum'];
            }

            //完成练习的未答题数
            $sql = "select".
                " t1.nickname,".
                " count(*) noAnswer".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('noAnswer')".
                " group by t1.nickname";
            $r9 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r9 = $row['noAnswer'];
            }

            //完成练习的错误题类型
            $sql = "select".
                " distinct t5.categoryId etype".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " left join question t5 on t5.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('wrong')";
            $r10 = array();
            $i = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r10[$i] = $row['etype'];
                $i++;
            }

            //完成练习的未答题类型
            $sql = "select".
                " distinct t5.categoryId ntype".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " left join question t5 on t5.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('noAnswer')";
            $r11 = array();
            $i = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r11[$i] = $row['ntype'];
                $i++;
            }

            //完成练习的difficult的错题
            $sql = "select".
                " count(*) dnum".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " left join question t5 on t5.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('wrong')".
                " and t5.difficulty='difficulty'";
            $r12 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r12 = $row['dnum'];
            }

            //完成练习的normal难度的错题
            $sql = "select".
                " count(*) nnum".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " left join question t5 on t5.courseId = t2.courseId".
                " where t1.id =".$_POST['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('wrong')".
                " and t5.difficulty='normal'";
            $r13 = 0;
            $result = mysqli_query($this->con, $sql) or die('error getting data');
            while( $row = mysqli_fetch_assoc($result) ){
                $r13 = $row['nnum'];
            }
            $this->view('home/statistical',['r1'=>$r1,'r2'=>$r2,'r3'=>$r3,'r4'=>$r4,
                'r5'=>$r5,'r6'=>$r6,'r7'=>$r7,'r8'=>$r8,'r9'=>$r9,'r10'=>$r10,'r11'=>$r11,'r12'=>$r12,'r13'=>$r13]);
        }
        else
        {
            echo 'error';
        }
    }

}

