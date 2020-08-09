<?php include("db.php");?>  <!-- Login Session and database functions -->
<?php include("header.php");?>  <!-- Header. Replace if you want to customize -->
<?php include("menubar_pq.php");?>  <!-- Common top menu bar -->


<html>
<style>
body, html {
  height: 100%;
  margin: 0;
}

.transbox {
  width: 1300px;
  padding: 60px;
  margin: 40px;
  background-color: #ffffff;
  border: 2px solid black;
  opacity: 1.0;
}

.transbox p {
  margin: 5%;
  font-weight: bold;
  color: #000000;
}

.middle {
  position: absolute;
  top: 40%;
  left: 50%;
  margin: 5%;
  transform: translate(-50%, -50%);
  text-align: justify;
  text-justify: inter-word;
}

hr {
  margin: auto;
  width: 60%;
}

#splash {
padding: 40px;
position: relative;
background: #FFF;
margin: 20px 0 0 0;
height: 300px;
width: 1100px;
box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.25);
}

.post {
width: 820px;
margin-left: auto ;
margin-right: auto ;
}

p {
margin-bottom: 1.75em;
margin-left: auto ;
margin-right: auto ;    
}

</style>
<body>
  
<!-- <div id="splash"> -->
<div id="transbox">
    <div class="post">
        <p><embed src="pq/puzzle_skyline.pdf" width="800px" height="725px" /></p>
    </div>
</div>


</body>
</html>



<!--CONTENT GOES HERE-->

<?php include("tail.php");?>  <!-- Contact inf and end body/html tags->

