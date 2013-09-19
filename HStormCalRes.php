
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hadoop Strom &ndash; Calculator</title>


<link rel="stylesheet"
	href="http://yui.yahooapis.com/pure/0.2.1/pure-min.css">

<link rel="stylesheet"
	href="http://purecss.io/combo/1.5.4?/css/layouts/pricing.css">


<script src="http://use.typekit.net/ajf8ggy.js"></script>
<script>
    try { Typekit.load(); } catch (e) {}

</script>



<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-41480445-1', 'purecss.io');
ga('send', 'pageview');


</script>


</head>
<body>

<?php 
/*
 * The algorithm is to :
 * 1) Compute the Memory needs
 * 2) Compute the CPU needs
 * 3) Compute the number of machines based on worker vs. available hardware profiles  
 */

//Tota number of servers
$total_isolated_servers = 0;
$total_shared_server = 0;

//Total memory
$total_mem_isolated = 0;
$total_mem_shared = 0;

//Total CPU core
$total_isolated_cores = 0;
$total_shared_cores = 0;

//Total Toplogies

$total_isolated_topologies = 0;
$total_topologies = 0;

//Total Workers
$total_isolated_workers = 0;
$total_shared_workers = 0;

//Machine allocation


//HW Profile
$hw_config_1 = array(
		"Machine_Name" => "HP DL180 G6",
		"Memory_name" => "47.2GB / 48GB 1333MHz DDR3",
		"Memory_value" => 46137344, //44 Gb allocated to Storm
		"CPU_name" => "2 x Xeon E5620 2.40GHz",
		"CPU_cores" => 4,
		"CPU_Threads" => 8,
		"CPU_Threads_Avb" => 7,
		"Storage_name" => "12 x 2TB SATA ",
		"Storage_value" => 24,
		"Cost" => 3800
		);


/*
 * Algorithm: 
 * 1. If the topology is Isolated: Then dedicate the servers as mentioned
 * 2. For Isolated, Also mention the possible utilization of CPU and Memory
 * 3. If the Topology is not Isolated: Then combine the usage for CPU and Memory across tasks and see divide it uniformly 
 * across the 
 * 
 */






$topologies =  $_POST['topology'];

$topology_alloc = array();


//Explode the token
$topology_ids = explode("|",$topologies);

foreach ($topology_ids as &$topologi_id) {
	$all_ids = explode("_",$topologi_id);
	
	$count = 0;
	$curr_topology_id=0;
	
	
	//Count for current topologies memory and cpu
	$curr_cpu_needs = 0; // % of CPU thread
	$curr_memory_needs = 0;  // Kb 
	
	
	foreach($all_ids as &$id){
		
		//Start processing the topologies
		
		if(!empty($id)){

			//Process the ids: The first Id is Topology rest is for tasks
			$count = $count+1;
			//Process the topology
			if(($count==1)){
				
				$curr_topology_id = $id;
				
				//Reset the memory and CPU needs
				$curr_cpu_needs = 0;
				$curr_memory_needs = 0;
				
				
				//Get the Worker: STopologyTbl_"+topologyCount+"_Worker
				
				$worker_key = "STopologyTbl_".$curr_topology_id."_Worker" ;
				$num_worker = $_POST[$worker_key];
					
				
				//Get if it is Isolated : STopologyTbl_"+topologyCount+"_Isolated
				$isolated_key = "STopologyTbl_".$curr_topology_id."_Isolated" ;
				$is_isolated = $_POST[$isolated_key];
					
				
				if(!empty($is_isolated)){
					
					//Get the servers: STopologyTbl_"+topologyCount+"_Servers
					$server_key = "STopologyTbl_".$curr_topology_id."_Servers" ;
					$num_server = $_POST[$server_key];
						
					
					$total_isolated_servers = $total_isolated_servers + $num_server;
					$total_isolated_topologies = $total_isolated_topologies +1;
					
				}
				
			}
			
			if($count>1 & empty($is_isolated)){

				//Get the Spout and Bolt details
				$curr_task_id = $id;
				
				
				//Get the # of Executors: SToplolgyTask_"+ topologyCount +"_"+currCount+"_exec
				
				$exec_key = "SToplolgyTask_".$curr_topology_id."_".$curr_task_id."_exec" ;
				$num_executors = $_POST[$exec_key];
				
				
				//Get the Throughput: SToplolgyTask_"+ topologyCount +"_"+currCount+"_mem
				$mem_key = "SToplolgyTask_".$curr_topology_id."_".$curr_task_id."_mem" ;
				$mem_need = $_POST[$mem_key];
				
				$curr_memory_needs = $curr_memory_needs + $mem_need;
				
				
				//Get the Compute Need: SToplolgyTask_"+ topologyCount +"_"+currCount+"_compute
				$compute_key = "SToplolgyTask_".$curr_topology_id."_".$curr_task_id."_compute" ;
				$compute_need = $_POST[$compute_key];
				
				$curr_cpu_needs = $curr_cpu_needs + $compute_need;
				
				
			}
			
			if($count>1 & !empty($is_isolated)){
			
				//Get the Spout and Bolt details
				$curr_task_id = $id;

				
				//Get the Throughput: SToplolgyTask_"+ topologyCount +"_"+currCount+"_mem
				$mem_key = "SToplolgyTask_".$curr_topology_id."_".$curr_task_id."_mem" ;
				$mem_need = $_POST[$mem_key];
			
				$total_mem_isolated = $total_mem_isolated + $mem_need;
			
			
				//Get the Compute Need: SToplolgyTask_"+ topologyCount +"_"+currCount+"_compute
				$compute_key = "SToplolgyTask_".$curr_topology_id."_".$curr_task_id."_compute" ;
				$compute_need = $_POST[$compute_key];
			
				$total_isolated_cores = $total_isolated_cores + $compute_need;
			
			
			}
			
			
	
		}	//
		
		$total_topologies = $count;
		
	}

	
	// Done with the processing of one topology
	
	if(!empty($id)){
	$total_mem_shared = $total_mem_shared + $curr_memory_needs;
	$total_shared_cores = $total_shared_cores + $curr_cpu_needs;
	
	
	$mem_per_worker = ceil($curr_memory_needs/$num_worker);
	$cpu_per_worker = ceil($curr_cpu_needs/$num_worker);
	
	//Mapping in terms of Hardware
	
	$hw_mem_portion = $mem_per_worker/$hw_config_1["Memory_value"];
	$hw_cpu_portion = $cpu_per_worker/100/$hw_config_1["CPU_Threads_Avb"];

	$max_hw_portion = max($hw_mem_portion,$hw_cpu_portion);
	
	//Push the topologies to 
	
	$curr_top_alloc = array(
							"topoplogy_num" => $curr_topology_id,
							"num_workers" => $num_worker,
							"per_worker_part" => ($max_hw_portion*100));

	
	
	array_push($topology_alloc,$curr_top_alloc);
	
	}
	
}




/*
 * Allocate the machines to best fit the worker needs
 */
$curr_machines = array(); 

$machine_1 = array(
					"Name" => "Supervisor 1",
					"Value" => 100
					);

array_push($curr_machines, $machine_1);

$curr_machines_alloc = array();


foreach ($topology_alloc as &$curr_topology) {
	
	$curr_topology_num = $curr_topology["topoplogy_num"];
	$curr_num_worker = $curr_topology["num_workers"];
	$curr_part = $curr_topology["per_worker_part"];
	
	for($count=1; $count<=$curr_num_worker; $count++){

	
	//Check all machines
	$is_allocated = false;
	
	foreach ($curr_machines as &$curr_machine) {

		if(!$is_allocated){
			$value_available = $curr_machine["Value"];
				
			
			//Check if the current machines can be used and then allocate
			if($value_available>=$curr_part){
			
				$allocation_name = "<tr><td>".$curr_machine["Name"]."</td> ".
									"<td>Topology Number # ".$curr_topology_num." </td>".
									"<td>Worker # ".$count."</td>".
									"</tr>";
				array_push($curr_machines_alloc, $allocation_name);
				
				//Decrease the value of supervisor
				$curr_machine["Value"] = $curr_machine["Value"] - $curr_part;
				
				//The worker is allocated
				$is_allocated = true;

			}
		 }
		}
		
	
	//If not allocated - then add a new machine
	if(!$is_allocated){
		$new_machine = array(
						"Name" => "Supervisor ".(count($curr_machines)+1),
						"Value" => 100
						);

		array_push($curr_machines,$new_machine);
		$count = $count - 1;
		

	}


	}



}

$total_shared_server = count($curr_machines);


?>

	<form class="pure-form pure-form-stacked">
		<h1>Hadoop Storm : Recommended Capacity</h1>
		<fieldset>
			<legend> Calculations are based on performance runs conducted on the hardware configuration displayed below. </legend>
			<h3>Hardware Configuration</h3>
			<table width="95%" border="0" class="pure-table pure-table-horizontal" >
				<thead>
					<th width="20%">Machine Name</th>
					<th width="20%"> Memeroy</th>
					<th width="20%"> Storage</th>
					<th width="20%"> CPU</th>
					<th width="20%"> Cost</th>
				</thead>
				<tr>
				<td><?php echo($hw_config_1["Machine_Name"]);?></td>
				<td><?php echo($hw_config_1["Memory_name"]);?></td>
				<td><?php echo($hw_config_1["Storage_name"]);?></td>
				<td><?php echo($hw_config_1["CPU_name"]);?></td>
				<td>$ <?php echo($hw_config_1["Cost"]);?></td>
				</tr>
			</table>
			
			<br>
			<br>
			<h3>Topology Details</h3>
			<table width="95%" border="0" class="pure-table pure-table-horizontal" >
				<thead>
					<th width="16%"> Topology Type</th>
					<th width="16%"> Total Topologies</th>
					<th width="16%"> Total Servers</th>
					<th width="16%"> Total Memory Needs</th>
					<th width="16%"> Total CPU Needs</th>
					<th width="16%"> Cost</th>
				</thead>
				<tr>
					<td> Shared </td>
					<td><?php echo($total_isolated_topologies);?></td>
					<td><?php echo($total_isolated_servers);?></td>
					<td><?php echo(ceil($total_mem_isolated));?> Kb</td>
					<td><?php echo(ceil($total_isolated_cores/100));?> Core Threads</td>
					<td>$ <?php echo(($total_isolated_servers)*$hw_config_1["Cost"]);?></td>
				</tr>
				<tr>
					<td> Isolated </td>
					<td><?php echo($total_topologies-$total_isolated_topologies);?></td>
					<td><?php echo($total_shared_server);?></td>
					<td><?php echo(ceil($total_mem_shared));?> Kb</td>
					<td><?php echo(ceil($total_shared_cores/100));?> Core Threads</td>
					<td>$ <?php echo(($total_shared_server)*$hw_config_1["Cost"]);?></td>
				</tr>
				<tr>
					<td>  </td>
					<td></td>
					<td></td>
					<td></td>
					<td><b>Total Cost</b></td>
					<td>$ <?php echo(($total_shared_server+$total_isolated_servers)*$hw_config_1["Cost"]);?></td>
				</tr>
				
			</table>
			
	<br>
	<br>
			<h3>Sample Topology Allocation</h3>
			<table width="95%" border="0" class="pure-table pure-table-horizontal" >
				<thead>
				<th  width="30%"> Supervisor</th>
				<th  width="30%"> Topology</th>
				<th  width="30%"> Worker</th>
			</thead>
			<tbody>
			<?php 
			
			foreach ($curr_machines_alloc as &$curr_alloc) {

				echo($curr_alloc);
			}
					
			
			?>
			
			</tbody>
			
			</table>
			


		</fieldset>

		<br> <br>
	</form>

</body>
</html>
