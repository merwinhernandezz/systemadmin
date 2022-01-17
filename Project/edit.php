<?php
  require_once('db.php');
  $upload_dir = 'uploads/';

  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "select * from contacts where id=".$id;
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
    }else {
      $errorMsg = 'Could not Find Any Record';
    }
  }

  if(isset($_POST['Submit'])){
		$name = $_POST['name'];
    $contact = $_POST['contact'];
		$email = $_POST['email'];

		$imgName = $_FILES['image']['name'];
		$imgTmp = $_FILES['image']['tmp_name'];
		$imgSize = $_FILES['image']['size'];

    $fileName = $_FILES['file']['name'];
	$fileTmp = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];


		if($imgName){

			$imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
      

			$allowExt  = array('jpeg', 'jpg', 'png', 'gif');
    


			$userPic = time().'_'.rand(1000,9999).'.'.$imgExt;
    
			if(in_array($imgExt, $allowExt)){

				if($imgSize < 5000000){
					unlink($upload_dir.$row['image']);
          $userFile = $row['file'];
					move_uploaded_file($imgTmp ,$upload_dir.$userPic);
  
				}else{
					$errorMsg = 'Image too large';
				}
			}else{
				$errorMsg = 'Please select a valid image';
			}
		}elseif($fileName){
      $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      $allowExt1  = array('csv', 'docx', 'xlsx', 'pdf');

      $userFile = time().'_'.rand(1000,9999).'.'.$fileExt;

      if(in_array($fileExt,$allowExt1)){

				if($fileSize < 5000000){
          $userPic = $row['image'];
          unlink($upload_dir.$row['file']);
          move_uploaded_file($fileTmp,$upload_dir.$userFile);
				}else{
					$errorMsg = 'File too large';
				}
			}else{
				$errorMsg = 'Please select a valid file';
			}


    }
    else{

			$userPic = $row['image'];
      $userFile = $row['file'];
		}

		if(!isset($errorMsg)){
			$sql = "update contacts
									set name = '".$name."',
										contact = '".$contact."',
                    email = '".$email."',
										image = '".$userPic."',
                    file = '".$userFile."'
					where id=".$id;
			$result = mysqli_query($conn, $sql);
			if($result){
				$successMsg = 'New record updated successfully';
				header('Location:index.php');
			}else{
				$errorMsg = 'Error '.mysqli_error($conn);
			}
		}

	}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>PHP CRUD</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css">
  </head>
  <body>

    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
      <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item"><a class="btn btn-outline-danger" href="index.php"><i class="fa fa-sign-out-alt"></i></a></li>
            </ul>
        </div>
      </div>
    </nav>

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card">
              <h3>
              <div class="text-center">
                Edit Profile
              </div>
</h3>
              <div class="card-body">
                <form class="" action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="name">Name: </label>
                      <input type="text" class="form-control" name="name"  placeholder="Enter Name" value="<?php echo $row['name']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="contact">Contact No:</label>
                      <input type="text" class="form-control" name="contact" placeholder="Enter Mobile Number" value="<?php echo $row['contact']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="email">E-Mail</label>
                      <input type="email" class="form-control" name="email" placeholder="Enter Email" value="<?php echo $row['email']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="image">Choose Image</label>
                      <div class="col-md-10">
                        <img src="<?php echo $upload_dir.$row['image'] ?>" width="100">
                        <input type="file" class="form-control" name="image" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="file">Choose File</label>
                      <div class="col-md-10">
                        <a href="<?php echo $filename= 'uploads/'.$row['file']; ?>"><?php echo $row['file'] ?></a>
                        <input type="file" class="form-control" name="file" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" name="Submit" class="btn btn-info waves">Submit</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" charset="utf-8"></script>
  </body>
</html>


<style>
  body {
  background-color: #FEF5ED;
}
h3{
  font-family: 'Lora', serif;
  font-weight: bold;
  color: #11324D;
  background-color: #ADC2A9;
}
.card{
  background-color: #D3E4CD;
}
label{
  font-family: 'Courier New';
  font-weight: bold;
}
</style>