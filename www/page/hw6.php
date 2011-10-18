<?

/*
	Task 3 [100 points] Based on the database schema given above and the tables you 
	created, write a Java program to perform the following tasks in order:
	(1) Raise managerÕs salary by 3% and non-manager employeeÕs salary by 9%. 
	(2) List each departmentÕs id, average employee age, range of employee salary, and total 
	number of employee as a table. Note that, each line in the table lists data for one 
	department, indicated by the department id.  All lines should be sorted by department 
	id. Also, please present the range of salary in terms of (minimum salary Ð maximum 
	salary).
*/

/*---------------- Database Connection ----------------*/
	$dbHost = 'localhost';
	$dbUser = 'svision_rsn';
	$dbPass = 'a2l0dGVuTWl0dGVueg==';
	
	$db = mysqli_connect($dbHost,$dbUser,base64_decode($dbPass),'svision_hw6');
	
	if(!$db)
		die("Unable to connect.  Error: ".mysqli_connect_error());

/*---------------- (1) Database Query ----------------*/

/*
    	Emp(eid,  ename,  age,  salary)
       	Works(eid,  did,  pct_time)
       	Dept(did,  budget,  managerid)
*/
	
	$upManagerSarary	= 'UPDATE Emp SET Salary=Salary*1.03 WHERE eid IN(SELECT manager_id FROM Dept)';
	$upEmployeeSalary	= 'UPDATE Emp SET Salary=Salary*1.09 WHERE eid NOT IN(SELECT manager_id FROM Dept)';
	
	$stmt = $db->prepare($upManagerSarary);
	$flag1 = $stmt->execute();
	
	$stmt = $db->prepare($upEmployeeSalary);
	$flag2 = $stmt->execute();
	
	if($flag1 && $flag2)
		echo("Done giving employees 9% raise and managers 3%.");
		
	else
		echo("Error updating salaries");
		
/*---------------- (2) Database Query ----------------*/

	$deptQry = 'SELECT W.did,AVG( E.age ),MIN(E.salary),MAX(E.salary),COUNT(*) 
				FROM Emp E
				INNER JOIN Works W ON E.eid=W.did
				GROUP BY W.did';
		
	$stmt = $db->prepare($deptQry);
	$stmt->execute();
	
	$stmt->bind_result($did,$age,$minSalary,$maxSalary,$empCount);
	
	echo('<table><tr><td>ID</td><td>Average Age</td><td>Min Salary</td><td>Max Salary</td><td>Employees</td></tr>');
	while($stmt->fetch())
	{
		echo('	<tr>
					<td>'.$did			.'</td>
					<td>'.$age			.'</td>
					<td>'.$minSalary	.'</td>
					<td>'.$maxSalary	.'</td>
					<td>'.$empCount		.'</td>
				</tr>
			');
	}	
	
	echo('</table>');
				
/*---------------- Cleanup Duty ----------------*/
	$stmt->close();
	$db->close();

die("<br />horray! done !");

?>