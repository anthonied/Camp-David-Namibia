<?php
include_once("include/session.php");
include_once("include/mailer.php");
?>
<script type="text/javascript">

	$(document).ready( function() {
		$("#myeventstable").jqGrid({
		url: "include/myeventsjson.php",
		datatype: "json",
		colNames:['#','#', 'Camp Type', 'Start Date', 'End Date', 'Max Attendance', 'Cost', 'Amount Paid', "Paid in Full", 'Status', 'Description'],
	   	colModel:[
	   		{name:'events_idevents',index:'a.events_idevents', width:30, align:"right", editable: false},
	   		{name:'users_idusers',index:'a.users_idusers', width:30, align:"right", hidden:true, editable: false},
	   		{name:'name',index:'c.name', width:90, align:"right", editable: false,  edittype: "select", editoptions: {value: "1:Knight in Training;2:Battle to Fight"}, editrules: {required: true}},
	   		{name:'startdate',index:'startdate', width:100, classes:'dtPicker', align:"right", formatter: 'date', formatoptions:{srcformat: "Y-m-d", newformat: "Y-m-d"}, editable: false, editoptions: {dataInit : function (elem){
	   		 $(elem).datepicker({changeYear : true,
	   				changeMonth : true,
	   				yearRange : "1900:c",
	   				dateFormat : "yy-mm-dd"
	   		 });
	   	 }}, editrules: {date: true, required: true}},
	   		{name:'enddate',index:'b.enddate', width:100, classes:'dtPicker', align:"right", formatter: 'date', formatoptions:{srcformat: "Y-m-d", newformat: "Y-m-d"}, editable: false, editoptions: {dataInit : function (elem){
		   		 $(elem).datepicker({changeYear : true,
		   				changeMonth : true,
		   				yearRange : "1900:c",
		   				dateFormat : "yy-mm-dd"
		   		 });
		   	 }}, editrules: {date: true, required: true} },
	   		{name:'maxattendance',index:'b.maxattendance', width:80, align:"right", editable: false, editrules: {integer: true}},		
	   		{name:'cost',index:'cost', width:80,align:"right", editable: false, formatter:'currency', formatoptions:{prefix: "N$ "}, editrules: {required: true}},		
	   		{name:'amountpayed',index:'a.amountpayed', width:80,align:"right", editable: true, formatter:'currency', formatoptions:{prefix: "N$ "}, editrules: {required: true}},
	   		{name:'payedinfull',index:'a.payedinfull', width:50, align:"center", formatter: "checkbox",editable: true, edittype: "checkbox", editoptions: {disabled: false,value:"1:0"}, editrules: {required: true}},
	   		{name:'status',index:'b.status', width:55, align:"right", editable: false, edittype: "select", editoptions: {value: "Inactive:Inactive;Active:Active"}, editrules: {required: true}},
	   		{name:'description',index:'b.description', width:90, align:"right", sortable:false, editable: false, edittype: "textarea", editoptions: {rows: 5, cols: "20"}},
	   	],
	   	rowNum:100,		   	
	   	rowList:[10,20,30,50,100,500,1000],
	   	pager: '#myeventspager',
	   	sortname: 'events_idevents',
	    viewrecords: true,
	    sortorder: "ASC",
	    caption:"My Events",
	    jsonReader: {
			repeatitems : false,
			id: "0",
			cell: ""
		},
		width: 870, 
		height: "100%",   
	    editurl:"include/myeventsjson.php?nonquery=true"
	});	

		
				
		<?php if(!$session->IsAdministrator()) {?>
		$("#myeventstable").jqGrid('navGrid','#myeventspager',
				{edit:false,add:false,del:false}, //options
				{}, // edit options
				{}, // add options
				{}, // del options
				{showOnLoad: false,sopt: ['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']} // search options
				);
		
		<?php } else{?>
		$("#myeventstable").jqGrid('navGrid','#myeventspager',
				{edit:true,add:false,del:true}, //options
				{}, // edit options
				{}, // add options
				{}, // del options
				{showOnLoad: false,sopt: ['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']} // search options
				);
		<?php }?>

		$("#myeventstable").jqGrid('navButtonAdd','#myeventspager',{caption:"View Attendees",
			onClickButton:function(){
				var gsr = $("#myeventstable").jqGrid('getGridParam','selrow');
				if(gsr){
					$('[id^="attendee"]').remove();
					$("#content").prepend('<div id="attendees"></div>');	
					$.get("attendees.php?eventid="+gsr, function(data) { $("#attendees").replaceWith(data);});	
				} else {
					alert("Please select a row");
				}							
			} 
		});
				 
	});
</script>
<table id="myeventstable">
</table>
<div id="myeventspager"></div>
