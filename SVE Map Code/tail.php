<?php
if (isset($achtext)) echo "<script>alert(\"",$achtext,"\");</script>";
if (isset($alert)) echo "<script>alert(\"",$alert,"\");</script>";

mysqli_close($link);
?>

</body>
</html>
