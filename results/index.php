<!DOCTYPE html>
<html>
<head>
  <title>Results!</title>
</head>
<body>
  <h1>results:</h1>
  <?php
    // REQUIRED START
    switch ($_SERVER['SERVER_NAME']) {
      case 'staging.flycommunications.com':
        $filePath = __DIR__;
        break;

      default:
        $filePath = 'C:/Users/jzielenkievicz/Documents/test/file-rename/';
        break;
    };
    require_once('../class.rename.php');
    $objFileNewName = new fly_fileRename(
      $originalFileName = $_FILES['file']['name'], // required
      $fileStorage = '../img/', // required
      $includeTimestamp = false,
      $whitespaceToUnderscores = true,
      $overwriteExistingFiles = false,
      $maxCharacterLimit = 150 // 200 Character max (including optional timestamp and file extension), 5 Character min
    );
    $newFileName = $objFileNewName->updatedFileName;
    // REQUIRED END

    if ($newFileName != '') {
      echo 'New File Name: <strong>'.$newFileName.'</strong><br />';
      // file upload
      move_uploaded_file($_FILES['file']['tmp_name'], $fileStorage.$newFileName);
      echo 'Stored in: <a href="'.$fileStorage.$newFileName.'" target="_blank">'.$fileStorage.$newFileName.'</a><br />';
    } else {
      echo 'no file<br />';
    }
  ?>
  <a href="#" id="show-more">Show More</a>
  <div class="hidden-pre" style="display:none;">
  <?php
    echo '<pre>';
    print_r($objFileNewName);
    //var_dump($objFileNewName);
    echo '</pre>';
  ?>
  </div>

  <br /><a href='../'>BACK</a>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
  <script src="../js/main.js"></script>
</body>
</html>
