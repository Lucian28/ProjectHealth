<?php
require __DIR__.'/vendor/autoload.php';
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

// ADUGARE articol IN REALTIME DATABASE

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

// EDITARE articol din REALTIME DATABASE

if(isset($_POST['submit_edit']))
// if(isset($_POST['edit_titlu']) && ($_POST['edit_titlu']!=''))
// if(isset($_POST['edit_ingrediente']) && ($_POST['edit_ingrediente']!=''))
// if(isset($_POST['edit_metodaPreparare']) && ($_POST['edit_metodaPreparare']!=''))
// if(isset($_POST['edit_imagine']))
{
     $titlu          = $_POST['edit_titlu'];
     $ingrediente    = $_POST['edit_ingrediente'];
     $metodaPreparare= $_POST['edit_metodaPreparare'];
     $macronutrienti = $_POST['edit_macronutrienti'];
     $tipArticol     = $_POST['edit_tipArticol'];
     $imagine        = $_POST['poza_initiala'];
	 $id_art         = $_POST['id_art'];

	 $postData = [
		'imagine' => $imagine,
		'ingrediente' => $ingrediente,
		'macronutrienti' => $macronutrienti,
		'modPreparare' => $metodaPreparare,
		'tip' => $tipArticol,
		'titlu' => $titlu,
	];

     $factory = (new Factory)->withServiceAccount(__DIR__.'/project-health.json')->withDatabaseUri('https://project-health-325f3-default-rtdb.firebaseio.com/');
     $database = $factory->createDatabase();
     $newPostKey =  $database->getReference($tipArticol.'/'.$id_art)->update($postData);
}

// stergere articol din REALTIME DATABASE

if(isset($_POST['stergeArticol'])){
    $factory = (new Factory)->withServiceAccount(__DIR__.'/project-health.json')->withDatabaseUri('https://project-health-325f3-default-rtdb.firebaseio.com/');
    $database = $factory->createDatabase();
   
    $cod_stergere = $_POST['valoare_cod_stergere'];

    $ref=$database->getReference('vegan/')->getChild($cod_stergere)->orderByChild($cod_stergere)->getReference();
    $path = $ref->getUri()->getPath();
    $ref->remove();
}



     $factory = (new Factory)->withServiceAccount(__DIR__.'/project-health.json')->withDatabaseUri('https://project-health-325f3-default-rtdb.firebaseio.com/');
     $database = $factory->createDatabase();
     $reference=$database->getReference('vegan/');
     $fetchdata = $reference->getValue();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Project Health</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
.modal .modal-dialog {
	max-width: 700px;
}
</style>

</head>
<body>
<div class="container-xxl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<h2>Gestiune <b>Articole</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Articol nou</span></a>
						<a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Sterge tot</span></a>						
					</div>
				</div>
			</div>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Titlu</th>
						<th>Ingrediente</th>
						<th>Macronutrienti</th>
						<th>Mod Preparare</th>
                        <th>Imagine</th>
                        <th>Actiuni</th>
					</tr>
				</thead>
				<tbody>
                <?php  foreach($fetchdata as $sir => $row){   ?>
					<tr>
						<td><?php echo $row['titlu'];?>  </td>
						<td><?php echo substr($row['ingrediente'], 0, 100)."...";?></td>
						<td><?php echo $row['macronutrienti'];?></td>
						<td><?php echo substr($row['modPreparare'], 0, 100)."..."?></td>
                        <td><?php echo $row['imagine'];?></td>
						<td>
							<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i onClick="incarcareCampuriEdit(this.id)" id="<?php echo $row['titlu']."~".$row['ingrediente']."~".$row['macronutrienti']."~".$row['modPreparare']."~".$row['imagine']."~".$sir;?>"  class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
							<a href="#deleteEmployeeModal"  class="delete" data-toggle="modal"><i onClick="getID(this.id)"  id="<?php echo $row['imagine']."~".$sir;?>" class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
						</td>
					</tr>
			<?php } ?>		
				</tbody>
			</table>
			<div class="clearfix">
				<div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
				<ul class="pagination">
					<li class="page-item disabled"><a href="#">Previous</a></li>
					<li class="page-item"><a href="#" class="page-link">1</a></li>
					<li class="page-item"><a href="#" class="page-link">2</a></li>
					<li class="page-item active"><a href="#" class="page-link">3</a></li>
					<li class="page-item"><a href="#" class="page-link">4</a></li>
					<li class="page-item"><a href="#" class="page-link">5</a></li>
					<li class="page-item"><a href="#" class="page-link">Next</a></li>
				</ul>
			</div>
		</div>
	</div>        
</div>
<!-- Edit Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="index.php">
				<div class="modal-header">						
					<h4 class="modal-title">Adauga Articol</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">	


                <div class="form-group">
                <select class="form-control" name="tipArticol" id="tipArticol" required>
                <label>Tip articol</label>
                             <option value="vegan">Vegan</option>
                             <option value="nutritie">Nutritie</option>
                             <option value="bodyMastery">Body Mastery</option>
                         </select>				
                         </div>
					<div class="form-group">
						<label>Titlu</label>
						<input type="text" name="titlu" id="titlu" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Ingrediente</label>
						<textarea  name="ingrediente" id="ingrediente" class="form-control" required></textarea>
					</div>
					<div class="form-group">
						<label>Metode de preparare</label>
						<textarea class="form-control" name="metodaPreparare" id="metodaPreparare" required></textarea>
					</div>
					<div class="form-group">
						<label>Macronutrienti</label>
						<input type="text" name="macronutrienti" id="macronutrienti" class="form-control" required>
					</div>		
                    <div class="form-group">
						<label>Imagine</label>
                        <input type="file" name="imagine" id="fileButton" value="upload" />
					</div>				
				</div>
				<!-- buton pentru apelare php  -->
                
                <input style="display: none;" type="submit" name="submit1" id="submit1">
               
                <!-- buton pentru apelare php  -->
			</form>

            <div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<button  onClick="incarcareImagine()" class="btn btn-success"> Adauga </button>
				</div>
   

		</div>
	</div>
</div>
<!-- Edit Modal HTML -->
<div id="editEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="index.php" method="POST">
				<div class="modal-header">						
					<h4 class="modal-title">Editeaza Articol</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
				<div class="form-group">
                <select class="form-control" name="edit_tipArticol" id="edit_tipArticol" required>
                <label>Tip articol</label>
                             <option value="vegan">Vegan</option>
                             <option value="nutritie">Nutritie</option>
                             <option value="bodyMastery">Body Mastery</option>
                         </select>				
                         </div>
					<div class="form-group">
						<label>Titlu</label>
						<input type="text" name="edit_titlu" id="edit_titlu" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Ingrediente</label>
						<textarea  name="edit_ingrediente" id="edit_ingrediente" class="form-control" required></textarea>
					</div>
					<div class="form-group">
						<label>Metode de preparare</label>
						<textarea class="form-control" name="edit_metodaPreparare" id="edit_metodaPreparare" required></textarea>
					</div>
					<div class="form-group">
						<label>Macronutrienti</label>
						<input type="text" name="edit_macronutrienti" id="edit_macronutrienti" class="form-control" required>
					</div>		
                    <div class="form-group">
						<label>Imagine</label>
                        <input type="file" name="edit_imagine" id="edit_fileButton" value="upload" />
					</div>		
					<input type="text" 	 style="display: none;" name="poza_initiala" id="poza_initiala" value="s"/>
				</div>
				<input type="text"  style="display: none;" name="id_art" id="id_art" value="gol"/>
				<input style="display: none;" type="submit" name="submit_edit" id="submit_edit">
			</form>

			<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="button" class="btn btn-info" onClick="UploadImagine()" value="Save">
				</div>
		</div>
	</div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="index.php">
				<div class="modal-header">						
					<h4 class="modal-title">Sterge Articol</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<p>Esti sigur ca vrei sa stergi acest articol?</p>
					<p class="text-warning"><small>Modicarile sunt ireversibile.</small></p>
				</div>
				<input type="submit" style="display: none;"  name="stergeArticol"  id="stergeArticol" >
				<input style="display: none;" type="text" name="valoare_cod_stergere" value="none"  id="gol">
                
			</form>
            <div class="modal-footer" >
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<button  onClick="stergereArticol()"  class="btn btn-danger" value="Delete"> Sterge </button>
                    
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

edit_fileButton.addEventListener('change', function(e){
  window.imagineEdit = e.target.files[0];
  window.numeimagineEdit = e.target.files[0].name;
  document.getElementById('poza_initiala').value=e.target.files[0].name;
});

</script>
<script>
function getID(clicked_id){
    var x = clicked_id.split(/~/);
    window.numeImagineStergere=x[0];
    window.cod_articol=x[1];
}

</script>

<script src="https://www.gstatic.com/firebasejs/3.7.4/firebase.js"></script>

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
  var fileButton =  document.getElementById('fileButton');
  var file = window.numeimagine;
  var storageRef = firebase.storage().ref('vegan/'+file.name);
  var task = storageRef.put(file);

  task.on('state_changed', function progress(snapshot) {
    var percentage = (snapshot.bytesTransferred/snapshot.totalBytes)*100;
  }, function error(err) {

  },function complete() {

document.getElementById('submit1').click();
  });
}
</script>






<script type="text/JavaScript">
function stergereArticol(){
    alert('Incepe Stergerea');
    var config = {
    apiKey: "AIzaSyDG3-zpn3p0dMflaZe9u3RceMGoN89BnCY",
    authDomain: "project-health-325f3.firebaseapp.com",
    databaseURL: "https://project-health-325f3-default-rtdb.firebaseio.com",
    storageBucket: "gs://project-health-325f3.appspot.com",
    messagingSenderId: "727969983669"
  };
  firebase.initializeApp(config);
   var storageRef = firebase.storage().ref('vegan/'+window.numeImagineStergere);
   var task=storageRef.delete().then(function() {


document.getElementById('gol').value = window.cod_articol;
document.getElementById('stergeArticol').click();
}).catch(function(error) {
	document.getElementById('gol').value = window.cod_articol;
document.getElementById('stergeArticol').click();
});

}
</script>


<script>
function incarcareCampuriEdit(campuri_firebase){
	var x = campuri_firebase.split(/~/);

    window.nume_edit=x[0];
    window.ingredinete_edit=x[1];
	window.macronutrienti_edit=x[2];
	window.modPreparare_edit=x[3];
	window.imagine_edit=x[4];

	document.getElementById('id_art').value=x[5];
	
	
	document.getElementById('edit_titlu').value=x[0];
	document.getElementById('edit_ingrediente').value=x[1];
	document.getElementById('edit_macronutrienti').value=x[2];
	document.getElementById('edit_metodaPreparare').value=x[3];
	document.getElementById('poza_initiala').value=x[4];
}
</script>

<script>
function UploadImagine(){

	var str1 = window.imagine_edit;
	var str2 = window.numeimagineEdit;
	

	if(window.numeimagineEdit==null)
	  document.getElementById('submit_edit').click();


 if(str1 === str2)
    {
		alert("imaginile coincid");
		  document.getElementById('submit_edit').click();
	}
	else{
	alert("Imaginile nu coincid");
 var config = {
    apiKey: "AIzaSyDG3-zpn3p0dMflaZe9u3RceMGoN89BnCY",
    authDomain: "project-health-325f3.firebaseapp.com",
    databaseURL: "https://project-health-325f3-default-rtdb.firebaseio.com",
    storageBucket: "gs://project-health-325f3.appspot.com",
    messagingSenderId: "727969983669"
  };
  firebase.initializeApp(config);
 
  var file = window.imagineEdit;
  var storageRef = firebase.storage().ref('vegan/'+file.name);
  var task = storageRef.put(file);
 
  task.on('state_changed', function progress(snapshot) {
    var percentage = (snapshot.bytesTransferred/snapshot.totalBytes)*100;
  }, function error(err) {
alert("fisierul exista deja");
  },function complete() {
alert("uploaded");
document.getElementById('submit_edit').click();  
  });
		}
		
	
}

</script>