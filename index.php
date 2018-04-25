<?php
session_start();
if (!isset($_SESSION["board"])) $_SESSION["board"] = "";
if (!isset($_SESSION["list"])) $_SESSION["list"] = "";
if (!isset($_SESSION["check"])) $_SESSION["check"] = "false";

if (isset($_GET["deletecookie"]))
{
	setcookie("trellocookie", "", time()-3600);
	unset($_COOKIE["trellocookie"]);
	session_destroy();
}

if (!isset($_COOKIE["trellocookie"]))
{
	echo "<script>window.location='trellotodo_cookie.php';</script>";
	exit;
}

if (isset($_GET["list"]))
{
	$_SESSION["list"] = $_GET["list"];
	$_SESSION["board"] = $_GET["board"];
}

if (isset($_GET["check"]))
{
	$_SESSION["check"] = $_GET["check"];
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Loading...</title>
<style type='text/css'>
@media (min-device-width: 8cm) and (max-device-width: 12cm)
{
	*
	{
		font-size:60pt;
	}
	
	.task
	{
		font-size:60pt;
		margin-bottom:3px;
		width:100%;
		border:1px solid black;
	}
	
	.new
	{
		font-size:60pt;
		background-color:lightblue;
		margin-bottom:3px;
		width:100%;
		border:1px solid black;
	}
	
	.new:hover
	{
		font-size:60pt;
		background-color:lightblue;
		margin-bottom:3px;
		width:100%;
		border:1px solid black;
	}
	.task:hover
	{
		font-size:60pt;
		background-color:yellow;
		margin-bottom:3px;
		width:100%;
		border:1px solid black;
	}
	
	.check
	{
		font-size:60pt;
		margin-bottom:3px;
		border:1px solid black;
		background-color:gray;
	}
	
	input[type=checkbox]
	{
		padding:30px;
		width:50px;
		height:50px;
		-webkit-transform: scale(8);
		
	}
}

@media (min-device-width: 38cm)
{
	*
	{
		font-size:9pt;
	}
	
	.task
	{
		font-size:18pt;
		margin-bottom:3px;
		width:100%;
		border:1px solid black;
	}
	
	.new
	{
		font-size:18pt;
		background-color:lightblue;
		margin-bottom:3px;
		width:100%;
		border:1px solid black;
	}
	
	.new:hover
	{
		font-size:18pt;
		background-color:lightblue;
		margin-bottom:3px;
		width:100%;
		border:1px solid black;
	}
	.task:hover
	{
		font-size:18pt;
		background-color:yellow;
		margin-bottom:3px;
		width:100%;
		border:1px solid black;
	}
	
	.check
	{
		font-size:18pt;
		margin-bottom:3px;
		border:1px solid black;
		background-color:gray;
	}
	
	input[type=checkbox]
	{
		padding:30px;
		-webkit-transform: scale(2);
	}
}

input[type=checkbox]
{
	padding:30px;
	-webkit-transform: scale(1.5);
	min-height:10px;
	min-width:10px;
}

html,body
{
	width:100%;
	height:100%;
}

#main
{
	height:100%;
	overflow:auto;
}

</style>
<script type='text/javascript' src='jquery-1.11.1.min.js'></script>
<script type='text/javascript'>

var sessionBoard = "<?php echo $_SESSION["board"]; ?>";
var sessionList = "<?php echo $_SESSION["list"]; ?>";
var startcheck = <?php echo $_SESSION["check"]; ?>

function deleteTasks(all)
{
	$("#main").find("div").each(function()
	{
		var task = $(this)[0].id.substring(4);
		if ($(this).hasClass("check") || all)
		{
			$.ajax({type:"POST",url:"ajax_trellotodo.php",data:"id="+task}).done(function()
			{
				$("#task"+task).css('display','none');
			}).fail(function(jqXHR, textStatus) {console.log("error "+textStatus); });
		}
	});
	
	
}

function check(i)
{
	if ($("#check")[0].checked)
	{
	
	if ($("#task"+i).hasClass("check"))
	{
		$("#task"+i).removeClass("check");
		$("#task"+i).addClass("task");
	}
	else
	{
		$("#task"+i).removeClass("new");
		$("#task"+i).removeClass("task");
		$("#task"+i).addClass("check");
	}
		
	var elem = $("#main").find('div').sort(function(a,b) {
			if (a.className == "check" && b.className != "check")
			{
				return 1;
			}
			else
			{
				return -1;
			}
		});	
	$("#main").append(elem); 
	}
	else
	{
		$("#task"+i).css('color','lightgrey');
		$.ajax({type:"POST",url:"ajax_trellotodo.php",data:"id="+i}).done(function()
			{
				$("#task"+i).css('display','none');
			}).fail(function(jqXHR, textStatus) {console.log("error "+textStatus); });
		
	}
}

$(document).ready(function () {
	$("#new").focus();
	$("#new").keyup(function(event) {
		if (event.keyCode==13)
		{	
			var text = $("#new").val();
			$("#new").val("")
			$.ajax({type:"POST",url:"ajax_trellotodo.php",dataType:"json",data:"val="+text}).done(function(data)
			{
				$("#main").html($("#main").html()+"\n<div id='task"+data.id+"' class='new' onclick='Javascript:check(\""+data.id+"\");'>"+text+"</div>");
			}).fail(function(jqXHR, textStatus) {console.log("error "+textStatus); });
			
			
		}
	});
	$("#lists").change(function(event) { reload(); });
	$("#boards").change(function(event) { loadlists(); });
	
	$("#check")[0].checked = startcheck;
	$("#check").change(function(event) { 
		$("#permalink").attr("href","?board="+$("#boards").val()+"&list="+$("#lists").val()+"&check="+($("#check")[0].checked?"true":"false"));
	});
	
	var options = [];
	$.ajax({type:"GET",url:"ajax_trellotodo.php",dataType:"json",data:"fetchboards=1"}).done(function(data)
			{
				for (var i= 0; i <data.length;i++)
				{
					options.push("<option value='"+data[i].id+"'"+(data[i].id==sessionBoard?"selected":"")+">"+data[i].name+"</option>");
				}
				$("#boards").html(options.join(''));
				loadlists();
			}).fail(function(jqXHR, textStatus) {console.log("error "+textStatus); });

	window.setTimeout(function() { reloadcron(); },600000);
	
});

function loadlists()
{
	var options = [];
	$.ajax({type:"GET",url:"ajax_trellotodo.php",dataType:"json",data:"fetchlists=1&board="+$("#boards").val()}).done(function(data)
		{
			for (var i= 0; i <data.length;i++)
			{
				options.push("<option value='"+data[i].id+"'"+(data[i].id==sessionList?"selected":"")+">"+data[i].name+"</option>");
			}
			$("#lists").html(options.join(''));
			reload();
		}).fail(function(jqXHR, textStatus) {console.log("error "+textStatus); });
}

function reloadcron()
{
	if (!$("#check")[0].checked)
	{
		reload();
	}
	window.setTimeout(function() { reloadcron(); },600000);
}

function reload()
{
	$("#permalink").attr("href","?board="+$("#boards").val()+"&list="+$("#lists").val()+"&check="+($("#check")[0].checked?"true":"false"));
	document.title = $('#lists option:selected').text();
		$("#main").html("Loading...");
		$.ajax({type:"GET",url:"ajax_trellotodo.php",data:"fetch="+1+"&list="+$("#lists").val(),dataType:"json"})
		.done(function(data)
		{
			str = "";
			for (i=0;i<data.length;i++)
			{
				str +="<div id='task"+data[i].id+"' class='task' onclick='Javascript:check(\""+data[i].id+"\");'>"+data[i].name+"</div>\n";
			}
			$("#main").html(str);
		}).fail(function(jqXHR, textStatus) {console.log("error "+textStatus); });
	
	
}

</script>
</head>
<body>
<select id='boards' name='boards'>
</select><br/>
<select id='lists' name='lists'>
</select><a id='permalink' href='?board=<?php echo $_SESSION["board"]; ?>&list=<?php echo $_SESSION["list"]; ?>'>Link</a><br/>
<input type='text' id='new' /></br/>
<input type='button' value='X All' onclick='Javascript:deleteTasks(true)'/>
<input type='button' value='X Checked' onclick='Javascript:deleteTasks(false)'/>
<input type='button' value='[R]' onclick='Javascript:reload()'/>
<input type='checkbox' id="check" />
<br/><br/>
<div id='main'>
</div>
</body>
</html>