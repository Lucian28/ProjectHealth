<?php
require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;



if(isset($_POST['submit1']))
if(isset($_POST['titlu']) && ($_POST['titlu']!=''))
if(isset($_POST['ingrediente']) && ($_POST['ingrediente']!=''))
if(isset($_POST['metodaPreparare']) && ($_POST['metodaPreparare']!=''))
if(isset($_POST['imagine']))
{
     $titlu          = $_POST['titlu'];
     $ingrediente    = $_POST['ingrediente'];
     $metodaPreparare= $_POST['metodaPreparare'];
     $macronutrienti = $_POST['macronutrienti'];
     $tipArticol     = $_POST['tipArticol'];
     $imagine        = $_POST['imagine'];

     $factory = (new Factory)->withServiceAccount(__DIR__.'/project-health.json')->withDatabaseUri('https://project-health-325f3-default-rtdb.firebaseio.com/');
     $database = $factory->createDatabase();
    $database->getReference($tipArticol.'/')
   ->push([
       'titlu' => $titlu,
       'ingrediente' => $ingrediente,
       'modPreparare' => $metodaPreparare,
       'macronutrienti' => $macronutrienti,
       'tip' => $tipArticol,
       'imagine' => $imagine,
      ]);

}




?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>


	

	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet">

	<script src="https://use.fontawesome.com/939e9dd52c.js"></script>

	

</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">Adauga articol</h1>
                    <div class="form-group">


                   <form action="index.php" method="POST"> 

                    <div class="form-group">
                    
                        <select name="tipArticol" id="tipArticol">
                             <option value="vegan">Vegan</option>
                             <option value="nutritie">Nutritie</option>
                             <option value="bodyMastery">Body Mastery</option>
                         </select>
                      </div>    
                      
                      <div class="form-group">
                         <label>Titlu</label><br>
                         <input type="text" id="titlu" name="titlu"><br>
                     </div>  

                     <div class="form-group">
                         <label>Ingrediente</label><br>
                        <textarea id="ingrediente" name="ingrediente" rows="4" cols="50">  </textarea>
                      </div>  
                       

                      <div class="form-group">
                         <label>Metoda de preparare</label><br>
                        <textarea id="metodaPreparare" name="metodaPreparare" rows="6" cols="50">  </textarea>
                      </div> 

                      <div class="form-group">
                         <label>Macronutrienti</label><br>
                         <input style="width:385px" type="text" id="macronutrienti" name="macronutrienti">
                      </div> 

                      <!-- <div class="form-group">
                         <label>Imagine</label><br>
                         <input type="file" id="imagine" name="imagine"accept="image/png, image/jpeg">
                      </div>  -->

                      <div class="mainDiv" >
		<h1>Firebase File Upload</h1>
		<input type="file" name="imagine" id="fileButton" value="upload" />
	</div>

                    </div>
                   
        <input style="display: none;" type="submit" name="submit1" id="submit1">
    </form>
    <button  onClick="incarcareImagine()" value="Creare Articol" class="btn btn-primary">Adauga articol </button>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>



<script> 
fileButton.addEventListener('change', function(e){
  window.numeimagine = e.target.files[0];
});
</script>


<script src="https://www.gstatic.com/firebasejs/3.7.4/firebase.js"></script>
<!-- <script> alert("alerta"); </script> -->
<script type="text/JavaScript">
function incarcareImagine(){
 
    var config = {
    apiKey: "AIzaSyDG3-zpn3p0dMflaZe9u3RceMGoN89BnCY",
    authDomain: "project-health-325f3.firebaseapp.com",
    databaseURL: "https://project-health-325f3-default-rtdb.firebaseio.com",
    storageBucket: "gs://project-health-325f3.appspot.com",
    messagingSenderId: "727969983669"
  };
  firebase.initializeApp(config);
  //-------------------------------------

  var fileButton =  document.getElementById('fileButton');
  var file = window.numeimagine;
  alert(" window.numeimagine="+ window.numeimagine);
  var storageRef = firebase.storage().ref('vegan/'+file.name);
  var task = storageRef.put(file);
  task.on('state_changed', function progress(snapshot) {
    var percentage = (snapshot.bytesTransferred/snapshot.totalBytes)*100;
  }, function error(err) {
    alert("error");

  },function complete() {
alert("complete");
document.getElementById('submit1').click();
  });
}
</script>