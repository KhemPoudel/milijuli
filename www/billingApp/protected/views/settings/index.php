<?php
    $src=Yii::app()->request->baseUrl.'/protected/data/testdrive.db';
    echo CHtml::link('backup',$src);
?>
<form action='#' method='POST' enctype='multipart/form-data'>
    <input type='file' name='userFile'><br>
    <input type='submit' name='upload_btn' value='upload'>
</form>
<?php
if(isset($_FILES['userFile']['name'])){
    $info = pathinfo($_FILES['userFile']['name']);
    $ext = $info['extension']; // get the extension of the file
    $newname = "testDrive1".$ext;
    $target =  Yii::app()->request->baseUrl;'/data/'.$newname;
    //echo $target;
    move_uploaded_file( $_FILES['userFile']['name'], $target);

}
?>
