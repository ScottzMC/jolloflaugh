<?php
define( '_FEXEC', 1 );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Clear Cache</title>
<script type='text/javascript'>
     self.close();
</script>
</head>

<body>
<?php
$cachedir = "../cache/";
if ($cachehandle = opendir($cachedir)) {
   while (false !== ($file = readdir($cachehandle))) {
    if ($file != "." && $file != "..") {
        $file2del = $cachedir."/".$file;
		unlink($file2del);     
       }
   }
   closedir($cachehandle);
}
echo 'CACHE_CLEARED';
?>
</body>
</html>
