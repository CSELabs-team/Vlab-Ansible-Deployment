<?php include('vlab-header.inc'); ?>

<h3>Enter the Following Information to Create a New Course</h3>
<form name="input" action="MakeClass.php" method="POST">
<!--input type="text" name="InstID"     /> Instructor ID <br /-->
Course Number: <br />
<input type="text" name="CourseNum"  /> <br><p>
Class Name: <br>
<input type="text" name="ClassName"  /> <br><p>
Number of Students: <br>
<input type="text" name="StudentNum" /> <br><p>
<input type="submit" value="SUBMIT"  />
</form>

<?php include('vlab-footer.inc'); ?>

