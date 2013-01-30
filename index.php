<!DOCTYPE html>
<html>
<head><title>costs and stuff of foods</title>
</head>
<body>
<!-- prerequisites and function definitions -->
<?php

ini_set('display_errors',1); 
 error_reporting(E_ALL);

function check_post_all() {
    foreach(func_get_args() as $var) {
        if(!isset($_POST[$var]) || $_POST[$var] === '') return false;
    }
    return true;
}

function check_post_any() {
    foreach(func_get_args() as $var) {
        if(isset($_POST[$var]) && !($_POST[$var] === '')) return true;
    }
    return false;
}

$connection = mysql_connect("localhost","root","{$_GET['pass']}");

if(!$connection){
	die('could not connect to mysql database:'.mysql_error());
}
mysql_select_db("food",$connection);
?>
<!-- FOOD -->
<h1>Foods:</h1><br><br>
	<!-- FOOD SEARCH -->
<h2> Search </h2>
<form method="post">
	name: <input type="text" name="food_name"><br>
	type: <select name="food_type_id">
		<option value=''></option>
	<?php
		$result = mysql_query("SELECT * FROM type ORDER BY type_name");

		while ($row = mysql_fetch_array($result)) {
			echo "<option value= '{$row['type_id']}' > {$row['type_name']} </option>";
		}
	?></select><br>
	cost ($ per ounce): <input type="text" name="food_cost_per_ounce"><br>
	cost ($ per pound): <input type="text" name="food_cost_per_pound"><br>
	<input type="submit" value="Submit">
</form>

<?php
if (check_post_any('food_name','food_type_id','food_cost_per_ounce','food_cost_per_pound')) {
	echo "<table border=\"1\">";
	echo "<tr>";
	echo "<th>Name</th>";
	echo "<th>$ Per Ounce</th>";
	echo "<th>$ Per Pound</th>";
	echo "</tr>";
	$table="names, groceries";
	$where="names.name_id=groceries.name_id AND names.common=1";
	if(isset($_POST['food_type_id'])&& !($_POST['food_type_id'] === '')){
		$table.=", food_type_bridge, type";
		$where.=" AND groceries.name_id=food_type_bridge.food_id AND food_type_bridge.type_id=type.type_id AND type.type_id= '{$_POST['food_type_id']}' ";
	}
	if(isset($_POST['food_name']) && !($_POST['food_name'] === '')){
		$where.=" AND names.name like '{$_POST['food_name']}'";
	}
	if(isset($_POST['food_cost_per_ounce']) && !($_POST['food_cost_per_ounce'] === '')){
		$where.=" AND groceries.price_per_ounce <= {$_POST['food_cost_per_ounce']} AND groceries.price_per_ounce > 0";
	}
	if(isset($_POST['food_cost_per_pound']) && !($_POST['food_cost_per_pound'] === '')){
		$where.=" AND groceries.price_per_pound <= {$_POST['food_cost_per_pound']} AND groceries.price_per_pound > 0";
	}
	$result = mysql_query("SELECT * FROM $table WHERE $where ORDER BY -price_per_ounce");
	while ($row = mysql_fetch_array($result)) {
			echo "<tr>";
			echo "<td>{$row['name']}</td>";
			echo "<td>{$row['price_per_ounce']}</td>";
			echo "<td>{$row['price_per_pound']}</td>";
			echo "</tr>";
		}
	echo "</table>";
}
else{
	echo "<br/> please enter something to search for food";
}
?>

<br><br>
	<!-- FOOD ADD -->
<h2> Add </h2>

<form method="post">
	name: <input type="text" name="add_food_name"><br>
	type: <input type="text" name="add_food_type"><br>
	cost: <input type="text" name="add_food_cost_per_ounce"><br>
	days: <input type="text" name="add_food_cost_per_pound"><br>
	<input type="submit" value="Submit">
</form>

<?php
if (isset($_POST['add_food_name']) && !($_POST['add_food_name'] === '')){
	mysql_query("INSERT INTO groceries (price_per_pound,price_per_ounce) VALUES(0,0) ");
	#if(isset($_POST[add_food_name])&& !($_POST[add_food_name] === '')){
		$result= mysql_query("SELECT * from groceries order by name_id desc limit 1");
		$row = mysql_fetch_array($result);
		$the_id=$row['name_id'];
		echo "{$row['name_id']}";
		echo "{$_POST['add_food_name']}";
		mysql_query("INSERT INTO names (name,name_id,common) VALUES('{$_POST['add_food_name']}',{$the_id},1)");
	#}
	if(isset($_POST['add_food_type']) && !($_POST['add_food_type'] === '')){
		$result= mysql_query("SELECT * FROM type WHERE type_name='{$_POST['add_food_type']}' LIMIT 1");
		$row=mysql_fetch_array($result);
		if($row == false || $row === ''){
			mysql_query("INSERT INTO type (type_name) VALUES('{$_POST['add_food_type']}')");
		}
		$result= mysql_query("SELECT * from type where type_name like '{$_POST['add_food_type']}' order by type_id desc limit 1");
		$row=mysql_fetch_array($result);
		mysql_query("INSERT INTO food_type_bridge (food_id,type_id) VALUES({$the_id},{$row['type_id']})");
	}
	if(isset($_POST['add_food_cost_per_ounce']) && !($_POST['add_food_cost_per_ounce'] === '')){
		mysql_query("UPDATE groceries SET price_per_ounce={$_POST['add_food_cost_per_ounce']} WHERE name_id={$the_id}");
	}
	if(isset($_POST['add_food_cost_per_pound']) && !($_POST['add_food_cost_per_pound'] === '')){
		mysql_query("UPDATE groceries SET price_per_pound={$_POST['add_food_cost_per_pound']} WHERE name_id={$the_id}");
	}
	echo "Food added succesfully.<br><br>";
}
else{
	echo "<br/> please enter something to search for food";
}
?>


<?php

?>
<!-- MEALS -->
<h1>Meals:</h1><br><br>
	<!-- MEAL SEARCH -->
<h2>Search</h2>

<br><br>
	<!-- MEAL ADD -->
<h2>Add</br>

<?php
mysql_close($connection);
?>
</body>
</html>