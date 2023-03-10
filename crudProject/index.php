<?php
session_start();
require_once ("inc/function.php");
$info='';
$task= $_GET['task']??'report';
$error= $_GET['error']??'0';
// edit start
if('edit'==$task){
    if(!hasPrevilent()){
        header('location:index.php?task=report'); 
    }
}

// delete start
if('delete'==$task){
    if(!isAdmin()){
        header('location:index.php?task=report');  
        return;
    }
    $id= filter_input(INPUT_GET, 'id',FILTER_SANITIZE_STRING);
    if($id>0){
        deleteStudent($id);
        header('location:index.php?task=report');
    }
}


// datasedding start
if($task=='seed'){
    if(!isAdmin()){
        header('location:index.php?task=report'); 
        return; 
    }
    seed();
    $info ="Seeding is Complete";
}
$fname='';
$lname='';
$roll='';
if(isset($_POST['submit'])){
    $id= filter_input(INPUT_POST, 'id',FILTER_SANITIZE_STRING);
    $fname = filter_input(INPUT_POST, 'fname',FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname',FILTER_SANITIZE_STRING);
    $roll = filter_input(INPUT_POST, 'roll',FILTER_SANITIZE_STRING);
    
    if($id){
        // update The Existing Student
        if($fname !='' && $lname !='' && $roll !='' ){
            $result=updateStudent($id, $fname, $lname, $roll);
            if($result){
                header('location:index.php?task=report');
            }else{
                $error=1;
            }
    }

    }else{
        // Add a New Student
        if($fname !='' && $lname !='' && $roll !=''){
            $result=addStudent($fname, $lname, $roll);
            if($result){
                header('location:index.php?task=report');
            }else{
                $error=1;
            }
            
        }
    }

    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Project</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
<style>
    body{
        margin-top:30px;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="column column-60 column-offset-10">
                <h2>CRUD PROJECT</h2>
                <p>A Simple Project to perform CRUD operations using plain files and PHP </p>
                <p>
                    <?php include_once("inc/templats/nav.php") ?>
                    <hr>
                    <?php
                    if($info!=''){
                        echo "<p>{$info}</p>";
                    }
                    ?>
                </p>
            </div>
        </div>
        <?php if('1'==$error): ?>
    <div class="row">
        <div class="column column-60 column-offset-10">
            <blockquote>
                <h4>Duplicate Roll Number..!</h4>
            </blockquote>
        </div>
    </div>


<?php endif; ?>
<?php if('report'==$task): ?>
    <div class="row">
        <div class="column column-60 column-offset-10">
            <?php genarateReport(); ?>
        </div>
    </div>


<?php endif; ?>


<?php if('add'==$task): ?>
    <div class="row">
        <div class="column column-60 column-offset-10">
            <form action="index.php?task=add" method="post">
                <label for="fname">Frist Name</label>
                <input type="text" name="fname" id="fname" value="<?php echo $fname?>">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname" value="<?php echo $lname?>">
                <label for="roll">Roll</label>
                <input type="number" name="roll" id="roll" value="<?php echo $roll?>">
                <button type="submit" class="button-primary" name="submit">Save</button>
            </form>
        </div>
    </div>
<?php endif; ?>


<?php if('edit'==$task):
    $id=filter_input(INPUT_GET,'id',FILTER_SANITIZE_STRING);
    $student= getStudent($id);
    if($student):
?>
    <div class="row">
        <div class="column column-60 column-offset-10">
            <form method="post">
                <input type="hidden" value="<?php echo $id; ?>" name="id">
                <label for="fname">Frist Name</label>
                <input type="text" name="fname" id="fname" value="<?php echo $student['fname']?>">
                <label for="lname">Last Name</label>
                <input type="text" name="lname" id="lname" value="<?php echo $student['lname']?>">
                <label for="roll">Roll</label>
                <input type="number" name="roll" id="roll" value="<?php echo $student['roll']?>">
                <button type="submit" class="button-primary" name="submit">Update</button>
            </form>
        </div>
    </div>
<?php
    endif;
 endif;
 ?>
    </div>
    
</body>
<script src="assets/js/script.js"></script>
</html>