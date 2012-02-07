<?php
include_once("include/session.php");
include_once("include/mailer.php");
?>
<script type="text/javascript">

	$(document).ready( function() {

		$("#attendeesdialog").dialog({
			autoOpen: true,
			resizable: true, 
			modal: true, 
			closeOnEscape: true,   
			draggable: true,
			minWidth: 0,
			minHeight: 0,
			title: "Attendees",
			width: "800",
			height: "400",
			//close: function () {$("#attendees").html("");},
			open: function() {
				$("#attendeestable").jqGrid({
					url: "include/attendeesjson.php?eventid=<?php echo ($_REQUEST[eventid]) ?>",
					datatype: "json",
					colNames:['#','#', 'First Name', 'Last Name', 'Birth Date', 'Cellphone', 'Email', 'Amount Payed', "Payed in Full", "Service Number"],
				   	colModel:[
				  	   	{name:'users_idusers',index:'a.users_idusers', width:30, align:"right" },
				   		{name:'events_idevents',index:'a.events_idevents', width:55, align:"right", hidden:true, editable: false},
				   		{name:'firstname',index:'c.firstname', width:90, align:"right", editable: false},
				   		{name:'lastname',index:'c.lastname', width:90, align:"right", editable: false},
				   		{name:'birthdate',index:'c.birthdate', width:100, classes:'dtPicker', align:"right", formatter: 'date', formatoptions:{srcformat: "Y-m-d", newformat: "Y-m-d"}, editable: false, editoptions: {dataInit : function (elem){
					   		 $(elem).datepicker({changeYear : true,
					   				changeMonth : true,
					   				yearRange : "1900:c",
					   				dateFormat : "yy-mm-dd"
					   		 });
					   	 }}},
					   	{name:'cellphone',index:'c.cellphone', width:90, align:"right", editable: false},
					   	{name:'email',index:'c.email', width:90, align:"right", editable: false, formatter: "email"},
				   		{name:'amountpayed',index:'a.amountpayed', width:80,align:"right", <?php if(!$session->IsAdministrator()) {echo "hidden: true ,";} ?> editable: true, formatter:'currency', formatoptions:{prefix: "N$ "}, editrules: {required: true}},
				   		{name:'payedinfull',index:'a.payedinfull', width:120, align:"left", <?php if(!$session->IsAdministrator()) {echo "hidden: true ,";} ?> formatter: payedformatter,editable: true, edittype: "checkbox", editoptions: {disabled: false,value:"1:0"}, editrules: {required: true}, unformat: payedinfullunformat},
				   		{name:'servicenumber',index:'servicenumber', width:90, align:"right", editable: false, editrules: {required: true}},
				   	],
				   	rowNum:100,		   	
				   	rowList:[10,20,30,50,100,500,1000],
				   	pager: '#attendeespager',
				   	sortname: 'users_idusers',
				    viewrecords: true,
				    sortorder: "ASC",
				    caption:"Attendees",
				    jsonReader: {
						repeatitems : false,
						id: "0",
						cell: "",
						subgrid: {id: "0", cell: "", repeatitems: false}
					},
					width: 770, 
					height: 280,		

					<?php if($session->IsAdministrator()) {?>	
					subGrid: true,
				    subGridUrl : "include/attendeessubgridjson.php",

				    <?php if($database->GetEventType($_REQUEST[eventid]) == "1") { // KIT?>
				    subGridModel: [ 
				      {
				      name  : ['School', 'Interests', 'Self Description', 'Sport', 'Grade'],
				      mapping : ['school', 'interests', 'selfdescription', 'sport', 'classyear'],
				      width : [200, 200, 200, 200, 200],
				      align : ['left','left', 'left', 'left', 'left'],
				      params: ['users_idusers', 'events_idevents'] 
				      }
				    ],
				    <?php }elseif($database->GetEventType($_REQUEST[eventid]) == "2") { // B2F?>
				    subGridModel: [ 
								      {
								      name  : [ 'Marital Status', 'Interests', 'Self Description', 'Occupation'],
								      mapping : ['maritalstatus', 'interests', 'selfdescription', 'occupation'],
								      width : [ 200, 200, 200, 200],
								      align : ['left','left', 'left', 'left'],
								      params: ['users_idusers', 'events_idevents'] 
								      }
								    ],
				    <?php } ?>
				    <?php } ?>
				    editurl:"include/attendeesjson.php?nonquery=true&eventid=<?php echo ($_REQUEST[eventid])?>"
				});	

					
							
					<?php if(!$session->IsAdministrator()) {?>
					$("#attendeestable").jqGrid('navGrid','#attendeespager',
							{edit:false,add:false,del:false}, //options
							{}, // edit options
							{}, // add options
							{}, // del options
							{showOnLoad: false,sopt: ['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']} // search options
							);
					
					<?php } else{?>
					$("#attendeestable").jqGrid('navGrid','#attendeespager',
							{edit:true,add:false,del:true}, //options
							{}, // edit options
							{}, // add options
							{}, // del options
							{showOnLoad: false,sopt: ['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']} // search options
							);

				/*	
				$("#attendeestable").jqGrid('navButtonAdd','attendeespager',{caption:"View User",
						onClickButton:function(){
							var gsr = $("#attendeestable").jqGrid('getGridParam','selrow');
							if(gsr){
								alert(gsr);
							} else {
								alert("Please select Row");
							}							
						} 
					});
						*/
					<?php }?>
				}
		});
			 
	});

	function payedformatter(cellvalue, options, rowObject)
	{
		if(cellvalue == '1')
		{
			return "<div>Paid <img src=\"images/check.png\" style=\"float: right\"></img></div>";
		}
		else
		{
			return "<div>Not Paid <img src=\"images/forbidden.png\" style=\"float: right\"></img></div>";
		}
	}

	function payedinfullunformat(cellvalue, options, rowObject)
	{
		if(cellvalue.search("Not") != -1)
			return "0";
		else
			return "1";
	}
</script>
<div id="attendeesdialog">
	<table id="attendeestable">
	</table>
	<div id="attendeespager"></div>
</div>
