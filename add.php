<?php
  require_once('db.php');
  $upload_dir = 'uploads/';

  if (isset($_POST['Submit'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    $imgName = $_FILES['image']['name'];
		$imgTmp = $_FILES['image']['tmp_name'];
		$imgSize = $_FILES['image']['size'];

	
	$fileName = $_FILES['file']['name'];
	$fileTmp = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];


    if(empty($name)){
			$errorMsg = 'Please input name';
		}elseif(empty($contact)){
			$errorMsg = 'Please input contact';
		}elseif(empty($email)){
			$errorMsg = 'Please input email';
		}
		else{

			$imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
			$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

			$allowExt  = array('jpeg', 'jpg', 'png', 'gif');
			$allowExt1  = array('csv', 'docx', 'xlsx', 'pdf');

			$userPic = time().'_'.rand(1000,9999).'.'.$imgExt;
			$userFile = time().'_'.rand(1000,9999).'.'.$fileExt;

			if(in_array($imgExt, $allowExt) && in_array($fileExt,$allowExt1)){

				if($imgSize < 5000000 && $fileSize < 5000000){
					move_uploaded_file($imgTmp ,$upload_dir.$userPic);
					move_uploaded_file($fileTmp,$upload_dir.$userFile);
				}else{
					$errorMsg = 'Image too large';
				}
			}else{
			
				$errorMsg = 'Please select a valid image';

					echo "<script>
						alert('Select valid file/Image');
						window.location.href='create.php';
						</script>";
			}
		}


		if(!isset($errorMsg)){
			$sql = "insert into contacts(name, contact, email, image, file)
					values('".$name."', '".$contact."', '".$email."', '".$userPic."', '".$userFile."')";
			$result = mysqli_query($conn, $sql);
			if($result){
				$successMsg = 'New record added successfully';
				header('Location: index.php');
			}else{
				$errorMsg = 'Error '.mysqli_error($conn);
			}
		}
  }
?>
