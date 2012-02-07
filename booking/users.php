<?php
include_once("include/session.php");
include_once("include/mailer.php");
?>
<script type="text/javascript">

	$(document).ready( function() {
			$("#userstable").jqGrid({
					url: "include/usersjson.php",
					datatype: "json",
					colNames:['#','First Name', 'Last Name', 'Birth Date', 'Status', 'Privelages', 'Cellphone', 'Email', 'Register Date'],
				   	colModel:[
				  	   	{name:'idusers',index:'a.users_idusers', width:40, align:"right" },
				   		{name:'firstname',index:'firstname', width:90, align:"right", editable: true, editrules: {required: true}},
				   		{name:'lastname',index:'lastname', width:90, align:"right", editable: true, editrules: {required: true}},
				   		{name:'birthdate',index:'birthdate', width:100, classes:'dtPicker', align:"right", formatter: 'date', formatoptions:{srcformat: "Y-m-d", newformat: "Y-m-d"}, editable: true, editoptions: {dataInit : function (elem){
					   		 $(elem).datepicker({changeYear : true,
					   				changeMonth : true,
					   				yearRange : "1900:c",
					   				dateFormat : "yy-mm-dd"
					   		 });
					   	 }}, editrules: {date: true, required: true}},
					   	{name:'status',index:'cellphone', width:90, align:"right", editable: true, editrules: {required: true}},
					   	{name:'privelagelevel_idprivelagelevel',index:'privelagelevel_idprivelagelevel', width:90, align:"right", editable: true, formatter: "select", edittype: "select", editoptions: {value: "2:User;100:Admin"}, editrules: {required: true}},
					   	{name:'cellphone',index:'cellphone', width:90, align:"right", editable: true, editrules: {required: true}},
					   	{name:'email',index:'email', width:90, align:"right", editable: true, formatter: "email", editrules: {required: true}},
				   		{name:'registerdate',index:'registerdate', width:100, classes:'dtPicker', align:"right", formatter: 'date', formatoptions:{srcformat: "Y-m-d H:i:s", newformat: "Y-m-d H:i:s"}, editable: false, editoptions: {dataInit : function (elem){
					   		 $(elem).datepicker({changeYear : true,
					   				changeMonth : true,
					   				yearRange : "1900:c",
					   				dateFormat : "yy-mm-dd"
					   		 });
					   	 }}, editrules: {date: true, required: true}}
				   	],
				   	rowNum:100,		   	
				   	rowList:[10,20,30,50,100,500,1000],
				   	pager: '#userspager',
				   	sortname: 'idusers',
				    viewrecords: true,
				    sortorder: "ASC",
				    caption:"Users",
				    jsonReader: {
						repeatitems : false,
						id: "0",
						cell: ""
					},
					width: 870,   
					height: "100%", 
				    editurl:"include/usersjson.php?nonquery=true"
				});	

				
				$("#userstable").jqGrid('navGrid','#userspager',
							{edit:true,add:false,del:false}, //options
							{}, // edit options
							{}, // add options
							{}, // del options
							{showOnLoad: false,sopt: ['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']} // search options
							);	
	});
</script>
<table id="userstable">
</table>
<div id="userspager"></div>
