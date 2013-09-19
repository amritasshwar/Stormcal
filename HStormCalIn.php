<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hadoop Storm &ndash; Calculator</title>


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

function updateTextInput(scrollBarId){
	var scrollBarValId = scrollBarId+'-val';
	document.getElementById(scrollBarValId).value=document.getElementById(scrollBarId).value;	
}


function addBolt(tableID) {               
	var table = document.getElementById(tableID);               
	var rowCount = table.rows.length;             
	var row = table.insertRow(rowCount);
    var currCount = rowCount;
    var id = rowCount-1;
    var tableName = tableID.toString();
    
                   
    var cell1 = row.insertCell(0);
    cell1.innerHTML = "<b>Bolt # "+id+"</b>";

    
    var cell2 = row.insertCell(1);             
    cell2.innerHTML = "<input id=\""+tableName+"_"+currCount+"_exec\" name=\""+tableName+"_"+currCount+"_exec\" type=\"number\" min=\"1\" required=\"required\">";
    
    var cell3 = row.insertCell(2);
    cell3.innerHTML = "<input id=\""+tableName+"_"+currCount+"_mem\" name=\""+tableName+"_"+currCount+"_mem\" type=\"number\" min=\"1\" required=\"required\">";               
    

    var cell4 = row.insertCell(3);             
    cell4.innerHTML = "<select id=\""+tableName+"_"+currCount+"_compute\" name=\""+tableName+"_"+currCount+"_compute\"><option value=\"33\">Low - 33% of a core</option><option value=\"66\">Medium - 66% of a core</option><option value=\"99\">High -  99% of a core</option></select>";

    
	 
    var cell5 = row.insertCell(4);

    cell5.innerHTML = "<input type=\"button\" value=\"\" onclick=\"deleteRow(this);\"class=\"pure-button-warning\">";
}


function deleteRow(o) {
	   var p=o.parentNode.parentNode;
       p.parentNode.removeChild(p);

}

function addTopology(topologyTbl) {

	var topTable = document.getElementById(topologyTbl);
	var rowCount = topTable.rows.length;
	var topologyCount = rowCount;             
    
	var row = topTable.insertRow(rowCount);

	//Cell to contain the Topology Count
    var cell1 = row.insertCell(0);
    cell1.innerHTML = topologyCount;

    var cell2 = row.insertCell(1);
    var cell2HTML = "<table width=\"100%\" border=\"0\" bgcolor=\"#D3D3D3\">"+
						"<tr>"+
							"<td width=\"70%\" align=\"left\">Total Workers <a href=\"#\""+
								"onclick=\"window.open('http://wiki.corp.yahoo.com/view/Grid/HbaseCal#Table_Size','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">(?)</a>"+
							"</td>"+
							"<td width=\"30%\" align=\"left\"><input id=\"STopologyTbl_"+topologyCount+"_Worker\""+
								"name=\"STopologyTbl_"+topologyCount+"_Worker\" type=\"number\" min=\"1\""+
								"required=\"required\">"+
							"</td>"+
						"</tr>"+
						"<tr>"+
							"<td></td>"+
						"</tr>"+
						"<tr>"+
							"<td></td>"+
						"</tr>"+
						"<tr>"+
							"<td width=\"70%\" align=\"left\">Isolated Topology</td>"+
							"<td width=\"30%\" align=\"left\">"+
							"<input id=\"STopologyTbl_"+topologyCount+"_Isolated\" name=\"STopologyTbl_"+topologyCount+"_Isolated\" type=\"checkbox\" onclick=\"toggleServer('STopologyTbl_"+topologyCount+"_');\">"+
							"</td>"+
						"</tr>"+
						"<tr>"+
							"<td width=\"70%\" align=\"left\">Total Servers <a href=\"#\""+
								"onclick=\"window.open('http://wiki.corp.yahoo.com/view/Grid/HbaseCal#Table_Size','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');\">(?)</a>"+
							"</td>"+
							"<td width=\"30%\" align=\"left\">"+
								"<input id=\"STopologyTbl_"+topologyCount+"_Servers\" name=\"STopologyTbl_"+topologyCount+"_Servers\" type=\"number\" min=\"1\">"+
							"</td>"+
						"</tr>"+
					"</table>";


    cell2.innerHTML = cell2HTML;
    
    var cell3 = row.insertCell(2);
	var cell3HTML = "<table width=\"100%\" border=\"0\" id=\"SToplolgyTask_"+topologyCount+"\">"+
						"<tr>"+
							"<td>"+
								"<button class=\"pure-button pure-button-secondary\" onclick=\"addBolt('SToplolgyTask_"+topologyCount+"');\">Add Bolt</button>"+
							"</td>"+
							"<th># Executors</th>"+
							"<th>Throughput (kb/sec)</th>"+
							"<th>Compute Need</th>"+
							"<td></td>"+
						"</tr>"+
						"<tr>"+
							"<td bgcolor=\"AliceBlue\" valign=\"top\" width=\"30%\"><b>Spout</b>"+
							"</td>"+
							"<td width=\"20%\"><input id=\"SToplolgyTask_"+topologyCount+"_1_exec\" name=\"SToplolgyTask_"+topologyCount+"_1_exec\" type=\"number\" min=\"1\" required=\"required\"></td>"+
							"<td width=\"20%\"><input id=\"SToplolgyTask_"+topologyCount+"_1_mem\" name=\"SToplolgyTask_"+topologyCount+"_1_mem\" type=\"number\" min=\"1\" required=\"required\"></td>"+
							"<td width=\"20%\">"+
								"<select id=\"SToplolgyTask_"+topologyCount+"_1_compute\" name=\"SToplolgyTask_"+topologyCount+"_1_compute\"><option value=\"33\">Low - 33% of a core</option><option value=\"66\">Medium - 66% of a core</option><option value=\"99\">High -  99% of a core</option></select>"+
							"</td>"+
							"<td width=\"5%\"></td>"+
						"</tr>"+
						"<tr>"+
							"<td valign=\"top\" width=\"30%\"><b>Bolt # 1</b>"+
							"</td>"+
							"<td width=\"20%\">"+
								"<input id=\"SToplolgyTask_"+topologyCount+"_2_exec\" name=\"SToplolgyTask_"+topologyCount+"_2_exec\" type=\"number\" min=\"1\" required=\"required\"></td>"+
							"<td width=\"20%\"><input id=\"SToplolgyTask_"+topologyCount+"_2_mem\" name=\"SToplolgyTask_"+topologyCount+"_2_mem\" type=\"number\" min=\"1\" required=\"required\"></td>"+
							"<td width=\"20%\">"+
								"<select id=\"SToplolgyTask_"+topologyCount+"_2_compute\" name=\"SToplolgyTask_"+topologyCount+"_2_compute\"><option value=\"33\">Low - 33% of a core</option><option value=\"66\">Medium - 66% of a core</option><option value=\"99\">High -  99% of a core</option></select>"+
							"</td>"+
							"<td width=\"5%\"></td>"+
						"</tr>"+
					"</table> ";

    
    cell3.innerHTML = cell3HTML;

    var cell4 = row.insertCell(3);

    cell4.innerHTML = "<input type=\"button\" value=\"Delete\" onclick=\"deleteRow(this);\"class=\"pure-button-error\">";
	
	document.getElementById("topologyCount").value = topologyCount;
		
}

function prepareData(){
	var table = document.getElementById('STopologyTbl');
	var topologies = '';     
    var r=1;

	//STopologyTbl_ SToplolgyTask_ var thenum = thestring.replace(/^.*(\d+).*$/i,'$1');
    
    while(row=table.rows[r++])
    {    	
    	var cell = row.cells[0];
    	topologies = topologies +'|'+cell.innerHTML+"_";

		var topTabName = 'SToplolgyTask_'+cell.innerHTML ;
    	var toptable = document.getElementById(topTabName);
    	var t=3;
    	var topindices = "1_2";
    	while(toprow=toptable.rows[t++]){
			var topcell = toprow.cells[0];
			var tindex = topcell.innerHTML.replace(/^.*(\d+).*$/i,'$1');
			var tindexnum = parseInt(tindex);
			tindexnum = tindexnum+1;
			topindices = topindices+"_"+tindexnum;
    	}

    	topologies = topologies+topindices;
		
	}

	

    document.getElementById('topology').value= topologies;
}

function toggleServer(checkBoxPrefix){
	//STopologyTbl_1_ = STopologyTbl_1_Isolated
	var checkBoxId = checkBoxPrefix+'Isolated';
	var checkBox = document.getElementById(checkBoxId);

	//e.g STopologyTbl_1_Servers
	var serverFieldId = checkBoxPrefix+'Servers';
	document.getElementById(serverFieldId).required = checkBox.checked;
		
}


</script>
</head>

<body>
	<form name="storminput" class="pure-form pure-form-aligned"
		action="HStormCalRes.php" method="post">


		<h1>Hadoop Storm Calculator</h1>

		<fieldset>
			<legend>Enter Topology Details</legend>

			<div>
				<style scoped>
.pure-button-success,.pure-button-error,.pure-button-warning,.pure-button-secondary
	{
	color: white;
	border-radius: 5px;
	text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
}

.pure-button-success {
	background: rgb(28, 184, 65); /* this is a green */
}

.pure-button-primary{
	border-radius: 25px;
}
.pure-button-error {
	border-radius: 25px;
	background: rgb(202, 60, 60); /* this is a maroon */
	font-size: 70%;
}

.pure-button-warning {
	border-radius: 25px;
	background: rgb(202, 60, 60); /* this is an maroon */
	width: 1em;
	height: 1.5em
}

.pure-button-secondary {
	background: rgb(66, 184, 221); /* this is a light blue */
}
</style>

				<button class="pure-button pure-button-primary"
					onclick="addTopology('STopologyTbl');">Add Topology</button>
			</div>
			<br>

			<fieldset>
				<table width="95%" class="pure-table pure-table-horizontal"
					id="STopologyTbl">
					<thead>
						<tr>
							<th bgcolor="#42B8DD" width="5%">#</th>
							<th bgcolor="#42B8DD" width="30%">Topology Level Details</th>
							<th bgcolor="#42B8DD" width="60%">Topology Tasks</th>
							<th bgcolor="#42B8DD"width="2%"></th>
						</tr>
					</thead>

					<tbody>
						<tr valign="top">
							<td>1</td>

							<!-- The table for Topology Level starts here -->

							<td>
								<table width="100%" border="0" bgcolor="#D3D3D3">
									<tr>
										<td width="70%" align="left">Total Workers <a href="#"
											onclick="window.open('http://wiki.corp.yahoo.com/view/Grid/HbaseCal#Table_Size','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">(?)</a>
										</td>
										<td width="30%" align="left"><input id="STopologyTbl_1_Worker"
											name="STopologyTbl_1_Worker" type="number" min="1"
											required="required"></td>
									</tr>
									<tr>
										<td></td>
									</tr>
									<tr>
										<td></td>
									</tr>
									<tr>
										<td width="70%" align="left">Isolated Topology</td>
										<td width="30%" align="left"><input
											id="STopologyTbl_1_Isolated" name="STopologyTbl_1_Isolated"
											type="checkbox" onclick="toggleServer('STopologyTbl_1_');"></td>
									</tr>
									<tr>
										<td width="70%" align="left">Total Servers <a href="#"
											onclick="window.open('http://wiki.corp.yahoo.com/view/Grid/HbaseCal#Table_Size','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');">(?)</a>
										</td>
										<td width="30%" align="left"><input
											id="STopologyTbl_1_Servers" name="STopologyTbl_1_Servers"
											type="number" min="1"></td>
									</tr>
								</table>
							</td>
							<!-- The table for Topology Level ends here -->


							<!-- The table for Topology Workers starts here -->
							<td valign="top">
								<table width="100%" border="0" id="SToplolgyTask_1">
									<tr>
										<td>
											<button class="pure-button pure-button-secondary"
												onclick="addBolt('SToplolgyTask_1');">Add Bolt</button>

										</td>
										<th># Executors</th>
										<th>Throughput(kb/sec)</th>
										<th>Compute Need</th>
										<td></td>
									</tr>
									<tr>
										<td bgcolor="AliceBlue" valign="top" width="30%"><b>Spout</b>
										</td>
										<td width="20%"><input id="SToplolgyTask_1_1_exec"
											name="SToplolgyTask_1_1_exec" type="number" min="1"
											required="required"></td>
										<td width="20%"><input id="SToplolgyTask_1_1_mem"
											name="SToplolgyTask_1_1_mem" type="number" min="1"
											required="required"></td>
										<td width="20%">
										        <select id="SToplolgyTask_1_1_compute" name="SToplolgyTask_1_1_compute">
										            <option value="33">Low - 33% of a core</option>
										            <option value="66">Medium - 66% of a core</option>
										            <option value="99">High -  99% of a core</option>
        										</select>
										</td>
										<td width="5%"></td>
									</tr>
									<tr>
										<td valign="top" width="30%"><b>Bolt # 1</b></td>
										<td width="20%"><input id="SToplolgyTask_1_2_exec"
											name="SToplolgyTask_1_2_exec" type="number" min="1"
											required="required"></td>
										<td width="20%"><input id="SToplolgyTask_1_2_mem"
											name="SToplolgyTask_1_2_mem" type="number" min="1"
											required="required"></td>
										<td width="20%">
												<select id="SToplolgyTask_1_2_compute" name="SToplolgyTask_1_2_compute">
										            <option value="33">Low - 33% of a core</option>
										            <option value="66">Medium - 66% of a core</option>
										            <option value="99">High -  99% of a core</option>
        										</select>
										</td>
										<td width="5%"></td>
									</tr>

								</table>


							</td>
							<!-- The table for Topology Workers ends here -->
							<td></td>
						</tr>

						<!-- Second topology -->

					</tbody>

				</table>

			</fieldset>

			<br> <br> <input type="hidden" id="topologyCount"
				name="topologyCount" value="1">
				
<button type="submit" onclick="prepareData()"  class="pure-button pure-button-primary"> Compute Capacity</button>               	

 <input type="hidden" id="topology" name="topology" >



</form>

</body>

</html>
