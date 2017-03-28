function checkAll(d) {
    var e = new Array();
    e = d.getElementsByTagName("input");
    for (var b = 0; b < e.length; b++) {
        if (e[b].type == "checkbox") {
            e[b].checked = true
        }
    }
}
function uncheckAll(d) {
    var e = new Array();
    e = d.getElementsByTagName("input");
    for (var b = 0; b < e.length; b++) {
        if (e[b].type == "checkbox") {
            e[b].checked = false
        }
    }
}
function selectcheck(frmObj){
	var flag = 0;
	var strid = "0";
	
	for(i = 0; i < frmObj.elements.length; i++) 
	{		
		elm = frmObj.elements[i]
		//alert(!isNaN(elm.id)+' '+elm.type);
		if (elm.type == "checkbox"/*  && !isNaN(elm.id)*/) 
		{			
			
			if (elm.checked)
			{				
				if (flag==0)
				{					
					flag = 1;					
				}			
			}
		}
	}
	if (flag == 1)
	{
		
		con  =  confirm('Are you sure you want to delete the selected records ?');
		if(con ==false){
			return ;
 		}else{
			frmObj.submit();
			return true;
		}
		
	}
	else
	{
		alert("- Select at least one record.");
		return false;
	}
}
function onInactiveAll(msg,frm){
	
	con  =  confirm(msg);
		if(con ==false){
			return ;
 		}else{
			document.getElementById('mulAction').value  =  'mulInactive';
			selectcheck(frm);
		}
}

function onActiveAll(msg,frm){
	con  =  confirm(msg);
		if(con ==false){
			return ;
 		}else{
			document.getElementById('mulAction').value  =  'mulActive';
			selectcheck(frm);
		}
}
function onDeleteAll(msg,frm){
	/*con  =  confirm(msg);
		if(con ==false){
			return ;
 		}else{*/
			
			selectcheck(frm);
		/*}*/
}
function onDeleteAll1(msg,frm){
	con  =  confirm(msg);
		if(con ==false){
			return ;
 		}else{
			document.getElementById('mulAction').value  =  'mulDelete';
			selectcheck(frm);
		}
}
