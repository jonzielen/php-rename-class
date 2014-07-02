<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
<form action="<?php echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'results/'; ?>" method="post" enctype="multipart/form-data">
  <label for="file"></label>
  <input type="file" id="file" name="file" />
  <button id="go">go!</button>
</form>
  <script type="text/javascript">
    var elem = document.getElementById('go');
    elem.addEventListener('click', function(e) {
      var fileElem = document.getElementById('file').value;
      if (fileElem == '') {
        e.preventDefault();
      };
    });
  </script>
</body>
</html>
