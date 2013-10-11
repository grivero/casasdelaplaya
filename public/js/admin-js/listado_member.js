var Ajax = new Object();

Ajax.Request = function(url,id, callbackMethod)
{
	Page.getPageCenterX();

	Ajax.request = Ajax.createRequestObject();
	Ajax.request.onreadystatechange = callbackMethod;
	Ajax.request.open("POST", url+id, true);
	Ajax.request.send(url);
}

Ajax.Response = function ()
{
	if(Ajax.CheckReadyState(Ajax.request))
	{			
		var	response = Ajax.request.responseXML.documentElement;
		var _data = response.getElementsByTagName('member');
		if(response.getElementsByTagName('letra')[0]!='0'){
			var inside = '<h3>No hay Apellidos que comiencen con la letra '+response.getElementsByTagName('letra')[0].firstChild.data+'.</h3>';
		} else {
			var inside = '<h3>No hay Apellidos ingresados.</h3>';
		}
		if(_data.length == 0)
		{
			document.getElementById('box').innerHTML = inside;	
		} else {
			var anterior = parseInt(response.getElementsByTagName('page')[0].firstChild.data) - 1;
			var siguiente = parseInt(response.getElementsByTagName('page')[0].firstChild.data) + 1;
			var i
			inside = '<h3>Listado de Miembros</h3><table width="100%"><thead><tr><th><a href="#">Nombre</a></th><th><a href="#">Email</a></th><th width="250px"></th></tr></thead><tbody>';
			for ( i = 0 ; i < _data.length ; i ++ )
			{
				inside += '<tr><td>'+response.getElementsByTagName('nombre')[i].firstChild.data+'</td><td>';
				inside += response.getElementsByTagName('email')[i].firstChild.data+'</td><td align="center">';
				inside +='<a href="edit_member.php?id='+response.getElementsByTagName('id_miembro')[i].firstChild.data+'">';
				inside +='<img src="img/icons2/edit.png" title="Editar artículo" width="16" height="16" /></a><a href="#"  onclick="if(confirm(\'\xBFEst&aacute; seguro que desea borrar este miembro?\')){Ajax.Request(\'../php_scripts/call_xml_member.php?letra='+response.getElementsByTagName('letra')[0].firstChild.data+'&borrar='+response.getElementsByTagName('id_miembro')[i].firstChild.data+'&method=getXML&page=\',1, Ajax.Response);};"><img src="img/icons2/trash_closed.png" title="Borrar miembro" width="16" height="16" /></a></td></tr>';
			}
			inside +='</tbody></table><div id="pager"><div class="pagination">';
			if(response.getElementsByTagName('rowini')[0].firstChild.data > 1){
				inside +=' <a href="#" onclick="Ajax.Request(\'../php_scripts/call_xml_member.php?letra='+response.getElementsByTagName('letra')[0].firstChild.data+'&borrar=0&method=getXML&page=\','+anterior+', Ajax.Response);">anterior</a> ';
			}
			inside +='<span class="current">'+response.getElementsByTagName('rowini')[0].firstChild.data+' - '+response.getElementsByTagName('rowend')[0].firstChild.data+' de '+response.getElementsByTagName('nummembers')[0].firstChild.data+'</span>';	
			//response.getElementsByTagName('lastpage')[0].firstChild.data+'</div>';
			if(response.getElementsByTagName('rowend')[0].firstChild.data < response.getElementsByTagName('nummembers')[0].firstChild.data){
				inside +=' <a href="#" onclick="Ajax.Request(\'../php_scripts/call_xml_member.php?letra='+response.getElementsByTagName('letra')[0].firstChild.data+'&borrar=0&method=getXML&page=\','+siguiente+', Ajax.Response);">siguiente</a> ';
			}
			inside +='</div></div>';
			document.getElementById('box').innerHTML = inside;
		}
	}
}

Ajax.createRequestObject = function()
{
	var obj;
	if(window.XMLHttpRequest)
	{
		obj = new XMLHttpRequest();
	}
	else if(window.ActiveXObject)
	{
		obj = new ActiveXObject("MSXML2.XMLHTTP");
	}
	return obj;
}

Ajax.CheckReadyState = function(obj)
{
	if(obj.readyState == 4)
	{
		if(obj.status == 200)
		{
			return true;
		}
	}
}

var Page = new Object();
Page.width;
Page.height;
Page.top;

Page.getPageCenterX = function ()
{
		var fWidth;
		var fHeight;		
		//For old IE browsers 
		if(document.all) 
		{ 
		fWidth = document.body.clientWidth; 
		fHeight = document.body.clientHeight; 
		} 
		//For DOM1 browsers 
		else if(document.getElementById &&!document.all)
		{ 
		fWidth = innerWidth; 
		fHeight = innerHeight; 
		} 
		else if(document.getElementById) 
		{ 
		fWidth = innerWidth; 
		fHeight = innerHeight; 		
		} 
		//For Opera 
		else if (is.op) 
		{ 
		fWidth = innerWidth; 
		fHeight = innerHeight; 		
		} 
		//For old Netscape 
		else if (document.layers) 
		{ 
		fWidth = window.innerWidth; 
		fHeight = window.innerHeight; 		
		}
	Page.width = fWidth;
	Page.height = fHeight;
	Page.top = window.document.body.scrollTop;
}