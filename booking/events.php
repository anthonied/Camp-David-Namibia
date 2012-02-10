<?php
include_once("include/session.php");
include_once("include/mailer.php");

if(!$session->IsAdministrator())
{
	header("Location: userevents.php");
	exit();
}
?>
<script type="text/javascript">

	$(document).ready( function() {
		$("#eventstable").jqGrid({
		url: "include/eventsjson.php",
		datatype: "json",
		colNames:['#', 'Camp Type', 'Start Date', 'End Date', 'Max Attendance', 'Cost', 'Partial Payments', 'Status', 'Description'],
	   	colModel:[
	   		{name:'idevents',index:'idevents', width:30, align:"right"},
	   		{name:'name',index:'b.name', width:90, align:"right", editable: true,  edittype: "select", editoptions: {value: "1:Knight in Training;2:Battle to Fight;3:Adventure to Live"}, editrules: {required: true}},
	   		{name:'startdate',index:'startdate', width:100, classes:'dtPicker', align:"right", formatter: 'date', formatoptions:{srcformat: "Y-m-d", newformat: "Y-m-d"}, editable: true, editoptions: {dataInit : function (elem){
	   		 $(elem).datepicker({changeYear : true,
	   				changeMonth : true,
	   				yearRange : "1900:c",
	   				dateFormat : "yy-mm-dd"
	   		 });
	   	 }}, editrules: {date: true, required: true}},
	   		{name:'enddate',index:'enddate', width:100, classes:'dtPicker', align:"right", formatter: 'date', formatoptions:{srcformat: "Y-m-d", newformat: "Y-m-d"}, editable: true, editoptions: {dataInit : function (elem){
		   		 $(elem).datepicker({changeYear : true,
		   				changeMonth : true,
		   				yearRange : "1900:c",
		   				dateFormat : "yy-mm-dd"
		   		 });
		   	 }}, editrules: {date: true, required: true} },
	   		{name:'maxattendance',index:'maxattendance', width:80, align:"right", editable: true, editrules: {integer: true}},		
	   		{name:'cost',index:'cost', width:80,align:"right", editable: true, formatter:'currency', formatoptions:{prefix: "N$ "}, editrules: {required: true}},		
	   		{name:'partialpayments',index:'partialpayments', width:50, align:"center", formatter: "checkbox",editable: true, edittype: "checkbox", editoptions: {disabled: false,value:"1:0"}, editrules: {required: true}},
	   		{name:'status',index:'status', width:55, align:"right", editable: true, edittype: "select", editoptions: {value: "Active:Active;Inactive:Inactive"}, editrules: {required: true}},
	   		{name:'description',index:'description', width:90, align:"right", sortable:false, editable: true, edittype: "textarea", editoptions: {rows: 5, cols: "20"}},
	   	],
	   	rowNum:100,		   	
	   	rowList:[10,20,30,50,100,500,1000],
	   	pager: '#eventspager',
	   	sortname: 'idevents',
	    viewrecords: true,
	    sortorder: "ASC",
	    caption:"Available Events",
	    jsonReader: {
			repeatitems : false,
			id: "0",
			cell: ""
		},
		width: 870, 
		height: "100%",   
	    editurl:"include/eventsjson.php?nonquery=true"
	});	

		
				
		<?php if(!$session->IsAdministrator()) {?>
		$("#eventstable").jqGrid('navGrid','#eventspager',
				{edit:false,add:false,del:false}, //options
				{}, // edit options
				{}, // add options
				{}, // del options
				{showOnLoad: false,sopt: ['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']} // search options
				);		
		$("#eventstable").jqGrid('navButtonAdd','#eventspager',{caption:"Register",
			onClickButton:function(){
				var gsr = $("#eventstable").jqGrid('getGridParam','selrow');
				if(gsr){
					$('[id^="eventregister"]').remove();					
					$("#content").prepend('<div id="eventregister"></div>');	
					$.get("eventregister.php?eventid="+gsr, function(data) { $("#eventregister").replaceWith(data);});	
				} else {
					alert("Please select a row");
				}							
			} 
		});
		<?php } else{?>
		$("#eventstable").jqGrid('navGrid','#eventspager',
				{edit:true,add:true,del:false}, //options
				{}, // edit options
				{}, // add options
				{}, // del options
				{showOnLoad: false,sopt: ['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']} // search options
				);
		<?php }?>
		
		$("#eventstable").jqGrid('navButtonAdd','#eventspager',{caption:"View Attendees",
			onClickButton:function(){
				var gsr = $("#eventstable").jqGrid('getGridParam','selrow');
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
<table id="eventstable">
</table>
<div id="eventspager"></div>
