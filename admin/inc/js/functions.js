/*
	*	
	* OxyClassifieds.com : PHP Classifieds (http://www.oxyclassifieds.com)
	* version 6.0
	* (c) 2009 OxyClassifieds.com (office@oxyclassifieds.com).
	*
*/
function selectCFType(fname, val, f_type) {

	if(!val) {
	var selected_index = fname.type.selectedIndex;
	var selected_val = fname.type.options[selected_index].value;
	} 
	else selected_val=val;

	if(selected_val!="depending") {

	document.getElementById("div_depending_no").style.display='none';

	// div_error, div_required
	if( selected_val!="checkbox") {

		if( selected_val!="user_email" && selected_val!="username" && selected_val!="password")
			document.getElementById("div_error").style.display='block';
		else document.getElementById("div_error").style.display='none';

		if(selected_val!="user_email" && selected_val!="username" && selected_val!="password" && selected_val!="terms")
			document.getElementById("div_required").style.display='block';
		else document.getElementById("div_required").style.display='none';
	}
	else {
		document.getElementById("div_error").style.display='none';
		document.getElementById("div_required").style.display='none';
	}

	// div_validation
	if(selected_val=="textbox" && selected_val!="price")
		document.getElementById("div_validation").style.display='block';
	else 
		document.getElementById("div_validation").style.display='none';

	// div_other_val
	if(selected_val=="menu")
		document.getElementById("div_other_val").style.display='block';
	else 
		document.getElementById("div_other_val").style.display='none';
	
	// div_is_numeric
	if(selected_val=="textbox" || selected_val=="menu" || selected_val=="price")
		document.getElementById("div_is_numeric").style.display='block';
	else 
		document.getElementById("div_is_numeric").style.display='none';

	//div_min, div_max
	if(selected_val=="textbox" || selected_val=="textarea" || selected_val=="htmlarea") {
		document.getElementById("div_min").style.display='block';
		document.getElementById("div_max").style.display='block';
	}
	else {

		document.getElementById("div_min").style.display='none';
		document.getElementById("div_max").style.display='none';
	}
	
	//div_length
	if(selected_val=="textbox" || selected_val=="textarea" || selected_val=="htmlarea" || selected_val=="youtube" || selected_val=="url" || selected_val=="email" || selected_val=="date" || selected_val=="multiselect" || selected_val=="terms") {
		document.getElementById("div_length").style.display='block';
	}
	else document.getElementById("div_length").style.display='none';

	//div_default_textbox
	if(selected_val=="textbox" || selected_val=="menu" || selected_val=="radio" || selected_val=="radio_group" || selected_val=="url" || selected_val=="email" || selected_val=="date" || selected_val=="price")
		document.getElementById("div_default_textbox").style.display='block';
	else 
		document.getElementById("div_default_textbox").style.display='none';

	//div_default_textarea
	if(selected_val=="textarea" || selected_val=="htmlarea") {
		document.getElementById("div_default_textarea").style.display='block';
	}
	else 
		document.getElementById("div_default_textarea").style.display='none';

	//div_default_checkbox
	if(selected_val=="checkbox") {
		document.getElementById("div_default_checkbox").style.display='block';
	}
	else 
		document.getElementById("div_default_checkbox").style.display='none';

	//div_prefix, div_postfix
	if(selected_val=="textbox" || selected_val=="menu" || selected_val=="radio" || selected_val=="url" || selected_val=="email" || selected_val=="date") {
		document.getElementById("div_prefix").style.display='block';
		document.getElementById("div_postfix").style.display='block';
	}
	else {
		document.getElementById("div_prefix").style.display='none';
		document.getElementById("div_postfix").style.display='none';
	}

	//div_elements
	if(selected_val=="menu" || selected_val=="radio" || selected_val=="radio_group" || selected_val=="checkbox_group" || selected_val=="multiselect")
		document.getElementById("div_elements").style.display='block';
	else
		document.getElementById("div_elements").style.display='none';

	//div_terms
	if(f_type=="uf") {
	if(selected_val=="terms")
		document.getElementById("div_terms").style.display='block';
	else
		document.getElementById("div_terms").style.display='none';
	}

	//div_uploaded
	if(selected_val=="file" || selected_val=="image")
		document.getElementById("div_uploaded").style.display='block';
	else
		document.getElementById("div_uploaded").style.display='none';

	//div_extensions
	if(selected_val=="file")
		document.getElementById("div_extensions").style.display='block';
	else
		document.getElementById("div_extensions").style.display='none';

	//div_resize
	if(selected_val=="image")
		document.getElementById("div_resize").style.display='block';
	else
		document.getElementById("div_resize").style.display='none';

	//div_date_format
	if(selected_val=="date")
		document.getElementById("div_date_format").style.display='block';
	else
		document.getElementById("div_date_format").style.display='none';

	//div_top_str
	if(selected_val=="menu")
		document.getElementById("div_top_str").style.display='block';
	else
		document.getElementById("div_top_str").style.display='none';

	//div_location_fields
	if(selected_val=="google_maps")
		document.getElementById("div_location_fields").style.display='block';
	else
		document.getElementById("div_location_fields").style.display='none';

    } // end if not depending

	if(selected_val=="depending") {

		document.getElementById("div_depending_no").style.display='block';

		document.getElementById("div_error").style.display='none';
		document.getElementById("div_required").style.display='none';
		document.getElementById("div_elements").style.display='none';
		document.getElementById("div_validation").style.display='none';
		document.getElementById("div_min").style.display='none';
		document.getElementById("div_max").style.display='none';
		document.getElementById("div_length").style.display='none';
		document.getElementById("div_default_textbox").style.display='none';
		document.getElementById("div_default_textarea").style.display='none';
		document.getElementById("div_default_checkbox").style.display='none';
		document.getElementById("div_prefix").style.display='none';
		document.getElementById("div_postfix").style.display='none';
		document.getElementById("div_uploaded").style.display='none';
		document.getElementById("div_extensions").style.display='none';
		document.getElementById("div_resize").style.display='none';
		document.getElementById("div_date_format").style.display='none';
		document.getElementById("div_top_str").style.display='none';
		document.getElementById("div_other_val").style.display='block';
		if(f_type=="uf") document.getElementById("div_terms").style.display='none';

		var no_fields = fname.depending_no.value;

		document.getElementById("div_dep1").style.display='block';
		document.getElementById("div_top_str1").style.display='block';
		document.getElementById("div_dep_error1").style.display='block';
		document.getElementById("div_dep_required1").style.display='block';

		document.getElementById("div_dep2").style.display='block';
		document.getElementById("div_top_str2").style.display='block';
		document.getElementById("div_dep_error2").style.display='block';
		document.getElementById("div_dep_required2").style.display='block';


		if(no_fields>=3) {

		document.getElementById("div_dep3").style.display='block';
		document.getElementById("div_top_str3").style.display='block';
		document.getElementById("div_dep_error3").style.display='block';
		document.getElementById("div_dep_required3").style.display='block';

		} else {

		document.getElementById("div_dep3").style.display='none';
		document.getElementById("div_top_str3").style.display='none';
		document.getElementById("div_dep_error3").style.display='none';
		document.getElementById("div_dep_required3").style.display='none';

		}

		if(no_fields>=4) {

		document.getElementById("div_dep4").style.display='block';
		document.getElementById("div_top_str4").style.display='block';
		document.getElementById("div_dep_error4").style.display='block';
		document.getElementById("div_dep_required4").style.display='block';

		} else {

		document.getElementById("div_dep4").style.display='none';
		document.getElementById("div_top_str4").style.display='none';
		document.getElementById("div_dep_error4").style.display='none';
		document.getElementById("div_dep_required4").style.display='none';

		}

	} else {

		document.getElementById("div_dep1").style.display='none';
		document.getElementById("div_dep2").style.display='none';
		document.getElementById("div_dep3").style.display='none';
		document.getElementById("div_dep4").style.display='none';
		document.getElementById("div_top_str1").style.display='none';
		document.getElementById("div_top_str2").style.display='none';
		document.getElementById("div_top_str3").style.display='none';
		document.getElementById("div_top_str4").style.display='none';
		document.getElementById("div_dep_error1").style.display='none';
		document.getElementById("div_dep_error2").style.display='none';
		document.getElementById("div_dep_error3").style.display='none';
		document.getElementById("div_dep_error4").style.display='none';
		document.getElementById("div_dep_required1").style.display='none';
		document.getElementById("div_dep_required2").style.display='none';
		document.getElementById("div_dep_required3").style.display='none';
		document.getElementById("div_dep_required4").style.display='none';

	}

	if(f_type=="cf") {

	//div_advanced_search
	if( selected_val!="image" && selected_val!="file" && selected_val!="google_maps" && selected_val!="youtube" && selected_val!="checkbox_group" &&  selected_val!="radio_group") {
		document.getElementById("div_advanced_search").style.display='block';
		document.getElementById("div_quick_search").style.display='block';

		if(fname.advanced_search.checked || fname.quick_search.checked) { 

			document.getElementById("div_search_type").style.display='block';

			if(((selected_val=="textbox" || selected_val=="menu" || selected_val=="price") && fname.is_numeric.checked) || selected_val=="date") {
				document.getElementById("span_search_interval").style.display='block';
				if(fname.search_type[1].checked && ( selected_val=="textbox" || selected_val=="price"))
					document.getElementById("div_search_interval").style.display='block';
				else document.getElementById("div_search_interval").style.display='none';

			} else {
				document.getElementById("span_search_interval").style.display='none';
				document.getElementById("div_search_interval").style.display='none';
			}
		}
		else { 
			document.getElementById("div_search_type").style.display='none';
			document.getElementById("div_search_interval").style.display='none';
		}

	}
	else {
		document.getElementById("div_advanced_search").style.display='none';
		document.getElementById("div_quick_search").style.display='none';
		document.getElementById("div_search_interval").style.display='none';
	}

	//div_interval
/*	if(selected_val=="textbox" || selected_val=="menu" || selected_val=="radio" || selected_val=="radio_group")
		document.getElementById("div_interval").style.display='block';
	else
		document.getElementById("div_interval").style.display='none';
*/
	//document.getElementById("div_public").style.display='none';

	} // end if cf
	else {

		if( selected_val!="password" && selected_val!="terms") document.getElementById("div_public").style.display='block';
		else document.getElementById("div_public").style.display='none';

	}

	if( selected_val!="user_email" && selected_val!="username" && selected_val!="password")
		document.getElementById("div_active").style.display='block';
	else document.getElementById("div_active").style.display='none';

	//if( selected_val!="user_email" && selected_val!="username" && selected_val!="password" && selected_val!="terms")
	if( selected_val!="user_email" && selected_val!="password" && selected_val!="terms")
		document.getElementById("div_editable").style.display='block';
	else document.getElementById("div_editable").style.display='none';

}

function onEnableSearch(fname,t) {

	var checked = 0;
	if(fname.advanced_search.checked || fname.quick_search.checked) checked = 1;

	if(t){ 
		selected_val=t;
	} else {
		var selected_index = fname.type.selectedIndex;
		var selected_val = fname.type.options[selected_index].value;
	}

	if(checked==1 && ( selected_val!="file" && selected_val!="image" && selected_val!="checkbox" && selected_val!="checkbox_group" && selected_val!="depending") ) { 
	
		document.getElementById("div_search_type").style.display='block';

		if(((selected_val=="textbox" || selected_val=="menu" || selected_val=="price") && fname.is_numeric.checked) || selected_val=="date") {

			document.getElementById("span_search_interval").style.display='block';
			if(fname.search_type[1].checked && ( selected_val=="textbox" || selected_val=="price" ))
				document.getElementById("div_search_interval").style.display='block';
			else document.getElementById("div_search_interval").style.display='none';

		} else {
			document.getElementById("span_search_interval").style.display='none';
			document.getElementById("div_search_interval").style.display='none';
		}
	}
	else document.getElementById("div_search_interval").style.display='none';

}

function onBadwordsSettings(fname) {

	var checked = fname.badwords_check.checked;
	if(checked==1) document.getElementById("div_badwords").style.display="block";
	else  document.getElementById("div_badwords").style.display="none";

}

function onEnableNotLoggedIn(fname) {

	var checked = 0;
	if(fname.not_logged_in.checked) checked = 1;
	if(checked) { 
		document.getElementById("div_choose_groups").style.display="none";
		document.getElementById("div_groups").style.display="none";
	}
	else 
		document.getElementById("div_choose_groups").style.display="block";
}

function doSel(obj)
{
     for (i = 0; i < obj.length; i++)
        if (obj[i].selected == true)
           eval(obj[i].value);
}

function activateImage() {
document.getElementById("div_image").style.display="block";
document.getElementById("div_code").style.display="none";
document.getElementById("div_link").style.display="block";
}

function activateCode() {
document.getElementById("div_image").style.display="none";
document.getElementById("div_code").style.display="block";
document.getElementById("div_link").style.display="none";
}

function CPactivateLink() {
document.getElementById("div_link").style.display="block";
document.getElementById("div_title").style.display="none";
document.getElementById("div_meta1").style.display="none";
document.getElementById("div_meta2").style.display="none";
}

function CPactivateCustom() {
document.getElementById("div_link").style.display="none";
document.getElementById("div_title").style.display="block";
document.getElementById("div_meta1").style.display="block";
document.getElementById("div_meta2").style.display="block";
}

function onChooseUsers(fname) {

	if(fname.choose_users[0].checked) document.getElementById("div_users").style.display='none';
	else document.getElementById("div_users").style.display='block';

}

function chooseUsers(fname,usr_str) {

	if(usr_str=="") {
		fname.choose_users[0].checked=true;
		document.getElementById("div_users").style.display='none';
	} else {
		fname.choose_users[1].checked=true;
		document.getElementById("div_users").style.display='block';
		var split_usr=usr_str.split(",");
		var no = split_usr.length;
		var len = fname.users.length;

		for(i=0; i<len; i++) {

			var val = fname.users.options[i].value;
			if (split_usr.toString().indexOf(","+val+",")!==-1 || split_usr[0]==val || split_usr[no-1]==val) 
				fname.users.options[i].selected=true;
		}
	}
}

function onChooseGroup(fname) {

	if(fname.choose_group[0].checked) document.getElementById("div_groups").style.display='none';
	else document.getElementById("div_groups").style.display='block';

}

function chooseGroup(fname,group_str) {

	if(group_str=="") {
		fname.choose_group[0].checked=true;
		document.getElementById("div_groups").style.display='none';
	} else {
		fname.choose_group[1].checked=true;
		document.getElementById("div_groups").style.display='block';
		var split_groups=group_str.split(",");
		var no = split_groups.length;
		var len = fname.groups.length;

		for(i=0; i<len; i++) {

			var val = fname.groups.options[i].value;
			if (split_groups.toString().indexOf(","+val+",")!==-1 || split_groups[0]==val || split_groups[no-1]==val) 
				fname.groups.options[i].selected=true;
		}
	}
}

function onPlanType(fname, val) {

	if(val=='ad') {
		fname.no_ads.disabled=true;
		fname.subscription_time.disabled=true;
	} else {
		fname.no_ads.disabled=false;
		fname.subscription_time.disabled=false;
	}
	return;

}

function onCPType(tp) {
	if(tp==1) { 
		document.getElementById("div_external").style.display="none";
		document.getElementById("div_internal").style.display="block";
	}
	else { 
		document.getElementById("div_external").style.display="block";
		document.getElementById("div_internal").style.display="none";
	}
}

function onNavlink() {

	var nav = document.getElementById("navlink").value;
	if(nav==1) document.getElementById("div_submenu").style.display = "block";
	else  document.getElementById("div_submenu").style.display = "none";

}

function editTranslation(id) {
	
	var div_str = "div_"+id;
	var disp = document.getElementById(div_str).style.display;
	if(disp=="none") document.getElementById(div_str).style.display="block";
	else document.getElementById(div_str).style.display = "none";
}



function mapStatus(display, hide) {

	var disp = document.getElementById("google_maps_location").style.display;
	if(disp=="none") { 
		document.getElementById("google_maps_location").style.display="block";
		document.getElementById("display_map").innerHTML = '<a href="javascript:;" onClick="mapStatus(\''+display+'\', \''+hide+'\')">'+hide+'</a><br><br>';
		displayMap();
	}
	else { 
		document.getElementById("google_maps_location").style.display = "none";
		document.getElementById("display_map").innerHTML = '<a href="javascript:;" onClick="mapStatus(\''+display+'\', \''+hide+'\')">'+display+'</a><br><br>';
		hideMap();
	}

}

function hideMap() {
	document.getElementById("map").innerHTML = '';
}

//function editSettings(code) {
//
//	var div_name = "div_"+code;
//	$(".div_settings").hide();
//	document.getElementById(div_name).style.display = "block";
//}

function onPaypalCurrency(fname) {

	var selected_index = fname.paypal_currency.selectedIndex;
	if(selected_index==0) document.getElementById("div_currency").style.display="block";	
	else  document.getElementById("div_currency").style.display="none";	

}
function getMultiple(ob, f) { 
	var len=ob.length;
	var str = '';
	for (l = 0; l < len; l++) {
		if(l) str+=',';
		str+=ob[l].value;
	}
	if(str) {
		f.value = str;
		return str;
	}
	else return '';
}

function checkAdsSettings(error_search){

	getMultiple(document.settings.allowed_html_box_right, document.settings.allowed_html);
	getMultiple(document.settings.location_fields_box_right, document.settings.location_fields);
	getMultiple(document.settings.search_location_fields_box_right, document.settings.search_location_fields);
	str = getMultiple(document.settings.search_in_fields_box_right, document.settings.search_in_fields);

	if(!str) { 
		alert(error_search);
		return false;
	}
	return true;

}

function checkSettings(){

	str = getMultiple(document.settings.location_fields_box_right, document.settings.location_fields);
	return true;

}

function checkAddTemplate(error1, error2) {

	if(!document.getElementById("name").value) { 
		alert(error1);
		return false;
	}
	str = getMultiple(document.ie.myselect_right, document.ie.fields);

	if(!str) { 
		alert(error2);
		return false;
	}
	return true;
}

function changeFields() {

	var l = document.ie.type.length;

	var import_ad_fields = document.getElementById("import_ad_fields").value;
	var import_user_fields = document.getElementById("import_user_fields").value;
	var export_ad_fields = document.getElementById("export_ad_fields").value;
	var export_user_fields = document.getElementById("export_user_fields").value;

	var purpose = document.getElementById("purpose").value;

	if(document.ie.type[0].checked && purpose=="import")
		str = import_ad_fields;
	else 
	if(document.ie.type[0].checked && purpose=="export") 
		str = export_ad_fields;

	else 
	if(document.ie.type[1].checked && purpose=="import") 
		str = import_user_fields;

	else 
	if(document.ie.type[1].checked && purpose=="export") 
		str = export_user_fields;


	removeAllOptions(document.ie.myselect_left);
	removeAllOptions(document.ie.myselect_right);

	var index = 0;

	var str_array = str.split(',');
	var len = str_array.length;
	for (var i=0; i<len; i++) {
		if(str_array[i]) document.ie.myselect_left.options[index++] = new Option( str_array[i], str_array[i] );
	}

	return;
}

function removeAllOptions(selectbox)
{

	var i;
	for(i=selectbox.options.length-1;i>=0;i--)
	{
		selectbox.remove(i);
	}

}

function changeTemplates(str, all_fields, data, type) {

	var index = 0;
	if(type=='csv') {
		document.ie.csv_template.options.length = 0;
		document.ie.csv_template.options[index++] = new Option( all_fields, '' );
		if(data == 'ad') { 
			document.getElementById("csv_ad_additional").style.display = "block";
			document.getElementById("csv_user_additional").style.display = "none";
		} else {
			document.getElementById("csv_ad_additional").style.display = "none";
			document.getElementById("csv_user_additional").style.display = "block";
		}
	}
	else { // xml
		document.ie.xml_template.options.length = 0;
		document.ie.xml_template.options[index++] = new Option( all_fields, '' );
		if(data == 'ad') { 
			document.getElementById("xml_ad_additional").style.display = "block";
			document.getElementById("xml_user_additional").style.display = "none";
		} else {
			document.getElementById("xml_ad_additional").style.display = "none";
			document.getElementById("xml_user_additional").style.display = "block";
		}
	}

	var len;

	if(str.length==0) len=0;
	else {
		var str_array = str.split(',');
		len = str_array.length;
	}

	for (var i=0; i<len; i++) {
		split_t = str_array[i].split(":");
		if(type=='csv')
			document.ie.csv_template.options[index++] = new Option( split_t[1], split_t[0] );
		else document.ie.xml_template.options[index++] = new Option( split_t[1], split_t[0] );
	}
	return;

}
/*
function onEditPriority(id) {

	var div_name = "div_name"+id;
	var div_price = "div_price"+id;

	if(document.getElementById(div_name).style.display=='none') { 
		document.getElementById(div_name).style.display='block'; 
		document.getElementById(div_price).style.display='block'; 
	} else {
		document.getElementById(div_name).style.display='none'; 
		document.getElementById(div_price).style.display='none'; 
	}

}
*/
function onEditProcessorTitle(id, recurring) {

	var div_title = "div_title"+id;
	var div_recurring = "div_recurring"+id;
	var span_title = "span_title"+id;
	var span_recurring = "span_recurring"+id;

	if(document.getElementById(div_title).style.display=='block' || document.getElementById(div_recurring).style.display=='block') { 

		document.getElementById(div_title).style.display='none'; 
		document.getElementById(span_title).style.display='block'; 

		if(recurring>=0) {
		document.getElementById(div_recurring).style.display='none'; 
		document.getElementById(span_recurring).style.display='block'; 
		}

	} else {

		document.getElementById(div_title).style.display='block'; 
		document.getElementById(span_title).style.display='none'; 

		if(recurring>=0) {
		document.getElementById(div_recurring).style.display='block'; 
		document.getElementById(span_recurring).style.display='none'; 
		}

	}

}

function changeCouponType(myForm, curr) {

	if(myForm.type[0].checked) { // fixed
		document.getElementById("postfix").innerHTML=curr;
	} else {
		document.getElementById("postfix").innerHTML="%";
	} // percent

}

function onChooseCateg(fname) {

	if(fname.choose_categ[0].checked) document.getElementById("div_categories").style.display='none';
	else document.getElementById("div_categories").style.display='block';

}

function chooseCateg(fname,cat_str) {

	if(cat_str=="") {
		fname.choose_categ[0].checked=true;
		document.getElementById("div_categories").style.display='none';
	} else {
		fname.choose_categ[1].checked=true;
		document.getElementById("div_categories").style.display='block';
		var split_cats=cat_str.split(",");
		var no = split_cats.length;
		var len = fname.categories.length;
		for(i=0; i<len; i++) {
			var val = fname.categories.options[i].value;
			if (split_cats.toString().indexOf(","+val+",")!==-1 || split_cats[0]==val || split_cats[no-1]==val) fname.categories.options[i].selected=true;
		}
	}
}

function onChooseSection(fname) {

	if(fname.choose_section[0].checked) document.getElementById("div_sections").style.display='none';
	else document.getElementById("div_sections").style.display='block';

}

function chooseSection(fname, section_str) {

	if(section_str=="") {
		fname.choose_section[0].checked=true;
		document.getElementById("div_choose_sections").style.display='none';
		document.getElementById("div_sections").style.display='none';
	} else {

		fname.choose_section[1].checked=true;
		document.getElementById("div_choose_sections").style.display='block';
		document.getElementById("div_sections").style.display='block';
		var split_sections=section_str.split(",");
		var no = split_sections.length;
		var len = fname.sections.length;
		for(i=0; i<len; i++) {
			var val = fname.sections.options[i].value;
			if (split_sections.toString().indexOf(","+val+",")!==-1 || split_sections[0]==val || split_sections[no-1]==val) fname.sections.options[i].selected=true;
		}
	}
}






function onUserBlock(id, path, block, unblock) {

	var url_str="include/actions.php?action=block&object=user&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_block'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onUserUnblock(\''+id+'\',\''+path+'\',\''+block+'\',\''+unblock+'\');" style="text-decoration: none;"> <img src="'+path+'images/unblock.gif" class="tooltip" name="'+unblock+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);
}

function onUserUnblock(id, path , block, unblock) {

	var url_str="include/actions.php?action=unblock&object=user&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_block'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onUserBlock(\''+id+'\',\''+path+'\',\''+block+'\',\''+unblock+'\');" style="text-decoration: none;"><img src="'+path+'images/block.gif" class="tooltip" name="'+block+'" alt=""></a>';
		}
	}
	xmlhttp.send(null);
}

function onListingBlock(id, path, block, unblock) {

	var url_str="include/actions.php?action=block&object=listing&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_block'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onListingUnblock(\''+id+'\',\''+path+'\',\''+block+'\',\''+unblock+'\');" style="text-decoration: none;"> <img src="'+path+'images/unblock.gif" class="tooltip" name="'+unblock+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);
}

function onListingUnblock(id, path , block, unblock) {

	var url_str="include/actions.php?action=unblock&object=listing&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_block'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onListingBlock(\''+id+'\',\''+path+'\',\''+block+'\',\''+unblock+'\');" style="text-decoration: none;"><img src="'+path+'images/block.gif" class="tooltip" name="'+block+'" alt=""></a>';
		}
	}
	xmlhttp.send(null);
}


function onMsgBlock(id, path, block, unblock) {

	var url_str="include/actions.php?action=block&object=msg&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_block'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onMsgUnblock(\''+id+'\',\''+path+'\',\''+block+'\',\''+unblock+'\');" style="text-decoration: none;"> <img src="'+path+'images/unblock.gif" class="tooltip" name="'+unblock+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);
}

function onMsgUnblock(id, path , block, unblock) {

	var url_str="include/actions.php?action=unblock&object=msg&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_block'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onMsgBlock(\''+id+'\',\''+path+'\',\''+block+'\',\''+unblock+'\');" style="text-decoration: none;"><img src="'+path+'images/block.gif" class="tooltip" name="'+block+'" alt=""></a>';
		}
	}
	xmlhttp.send(null);
}


function onUserDelete(id, path) {

	var url_str="include/actions.php?action=delete&object=user&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}
/*
function onUserEnable(id, path, enable, disable) {

	var url_str="include/actions.php?action=enable&object=user&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_active'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onUserDisable(\''+id+'\',\''+path+'\',\''+enable+'\',\''+disable+'\');" style="text-decoration: none;"> <img src="'+path+'images/deactivate.gif" class="tooltip" name="'+disable+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);

}*/

function onUserAccept(id) {

	var url_str="include/actions.php?action=enable&object=user&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}
/*
function onUserDisable(id, path, enable, disable) {

	var url_str="include/actions.php?action=disable&object=user&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_active'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onUserEnable(\''+id+'\',\''+path+'\',\''+enable+'\',\''+disable+'\');" style="text-decoration: none;"> <img src="'+path+'images/activate.gif" class="tooltip" name="'+enable+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);
}
*/
function onGroupDelete(id, path) {

	var url_str="include/actions.php?action=delete&object=group&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onGroupEnable(id, path, enable, disable) {

	var url_str="include/actions.php?action=enable&object=group&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_active'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onGroupDisable(\''+id+'\',\''+path+'\',\''+enable+'\',\''+disable+'\');" style="text-decoration: none;"><img src="'+path+'images/deactivate.gif" class="tooltip" name="'+disable+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);
}

function onGroupDisable(id, path, enable, disable) {

	var url_str="include/actions.php?action=disable&object=group&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_active'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onGroupEnable(\''+id+'\',\''+path+'\',\''+enable+'\',\''+disable+'\');" style="text-decoration: none;"> <img src="'+path+'images/activate.gif" class="tooltip" name="'+enable+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);
}

function onEnable(id, obj_type, confirm_str) {

	if(confirm_str != undefined && confirm_str != '' && myConfirm(confirm_str)==false) return false;

	var url_str="include/actions.php?action=enable&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onDisable(id, obj_type, confirm_str) {

	if(confirm_str != undefined && confirm_str != '' && myConfirm(confirm_str)==false) return false;

	var url_str="include/actions.php?action=disable&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onUninstall(id, confirm_str) {

	if(confirm_str != undefined && confirm_str != '' && myConfirm(confirm_str)==false) return false;

	var url_str="include/actions.php?object=module&action=uninstall&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onInstall(id) {

	var url_str="include/actions.php?object=module&action=install&id="+id;

	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			//location.reload(true);
			window.location="modules/"+id+"/index.php";
		}
	}
	xmlhttp.send(null);
}

function onEnablePayment(id, obj_type) {

	var url_str="include/actions.php?action=enable&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var response=xmlhttp.responseText;
			if(response) {
				alert(response);
			}
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onPaymentPending(id) {

	var url_str="include/actions.php?action=pending&object=payment&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onPaymentNotPending(id) {

	var url_str="include/actions.php?action=not_pending&object=payment&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onSold(id, obj_type) {

	var url_str="include/actions.php?action=sold&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onUnsold(id, obj_type) {

	var url_str="include/actions.php?action=unsold&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onRented(id, obj_type) {

	var url_str="include/actions.php?action=rented&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onUnrented(id, obj_type) {

	var url_str="include/actions.php?action=unrented&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onAccept(id) {

	var url_str="include/actions.php?action=accept&object=listing&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onMoveUp(id,obj_type) {

	var url_str="include/actions.php?action=move_up&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onMoveDown(id,obj_type) {

	var url_str="include/actions.php?action=move_down&object="+obj_type+"&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onCategMove( order_no, move_to) {

//	if(way=="up") action = "move_up"; else action = "move_down";

	$.get("include/actions.php", { action: "move", object: "categ", order_no: order_no, move_to: move_to },
  	function(data){
		location.reload(true);
/*		if(!data) return;
		if(data<0) {
			location.reload(true);
			return;
		}
		var ar = new Array("checkbox", "description","id", "pic", "name", "ads", "parent", "fset", "action");
		no = ar.length;

		for(i=0; i<no; i++) {
			var crt = document.getElementById(ar[i]+order_no).innerHTML
			var sw_to = document.getElementById(ar[i]+data).innerHTML
			document.getElementById(ar[i]+order_no).innerHTML = sw_to;
			document.getElementById(ar[i]+data).innerHTML = crt;
		}*/
  	});
}

function onFieldMove( order_no, way, objtype, fieldset) {

	if(way=="up") action = "move_up"; else action = "move_down";
	$.get("include/actions.php", { action : action, object : objtype, order_no : order_no, fieldset : fieldset },
  	function(data){

		if(!data) return;
		if(data<0) {
			location.reload(true);
			return;
		}
		if(objtype=="cf")
			var ar = new Array("checkbox", "description","id", "name", "fieldset", "type", "caption", "action");
		else var ar = new Array("checkbox", "description","id", "name", "type", "caption", "action");
		no = ar.length;

		for(i=0; i<no; i++) {
				
			var crt = document.getElementById(ar[i]+order_no).innerHTML
			var sw_to = document.getElementById(ar[i]+data).innerHTML
			document.getElementById(ar[i]+order_no).innerHTML = sw_to;
			document.getElementById(ar[i]+data).innerHTML = crt;

		}
		// change row style to keep read only fields visible
		var old_class = document.getElementById("row"+order_no).getAttribute("class");
		var sw_to_class = document.getElementById("row"+data).getAttribute("class");

		if(old_class!="nicetablerow_pending" && sw_to_class!="nicetablerow_pending") return;

		var old_over_class = document.getElementById("row"+order_no).getAttribute("onmouseover");
		var old_out_class = document.getElementById("row"+order_no).getAttribute("onmouseout");

		var sw_to_over_class = document.getElementById("row"+data).getAttribute("onmouseover");
		var sw_to_out_class = document.getElementById("row"+data).getAttribute("onmouseout");

		document.getElementById("row"+order_no).setAttribute("class", sw_to_class);
		document.getElementById("row"+order_no).setAttribute("onmouseover", sw_to_over_class);
		document.getElementById("row"+order_no).setAttribute("onmouseout", sw_to_out_class);

		document.getElementById("row"+data).setAttribute("class", old_class);
		document.getElementById("row"+data).setAttribute("onmouseover", old_over_class);
		document.getElementById("row"+data).setAttribute("onmouseout", old_out_class);

  	});
}


function onClearHits(id,str,obj_type)
{
	if (myConfirm(str)==false) return;
	var url_str="include/actions.php?action=clear_hits&object="+obj_type+"&id="+id;
	xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {

		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}


function onRenew(id, str) {

	if (myConfirm(str)==false) return;
	url_str="include/actions.php?object=listing&action=renew&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {

		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function selDep(n, max, id, caption1, caption2) {
	var sel1 = "sel_"+caption1;
	var list1 = caption2+"_list";
	if(n<max) var sel2 = "sel_"+caption2; else sel2='';

	var selected_index = document.getElementById(sel1).selectedIndex;
	var selDep = document.getElementById(sel1).options[selected_index].value;

	var response;
	var url_str="include/get_info.php?type=dep&id="+selDep+"&dep_id="+id+"&table="+n;
	var xmlhttp=getxmlhttp();

	// Synchronous AJAX
	xmlhttp.open("GET",url_str,false);
	xmlhttp.send(null);
	var response = xmlhttp.responseText;
	document.getElementById(list1).options.length=0;
	if(sel2) document.getElementById(sel2).options.length = 0;
	if(response) {
		var split_arr=response.split(":");
		var no=split_arr.length;
		var i;
					
		for(i=0; i<no; i++) {
			split_dep=split_arr[i].split(',');
			var dep_id=split_dep[0];
			var dep_name=split_dep[1];
			document.getElementById(list1).options[i]=new Option (dep_name,dep_id);
			if(sel2) { 
				document.getElementById(sel2).options[i]=new Option (dep_name,dep_id);
			}
		}
	}

}

function chooseUserGroup(myForm) {

var selected_index = myForm.group.selectedIndex;
var selected_val = myForm.group.options[selected_index].value;
url_str="include/get_info.php?type=group&id="+selected_val;
var xmlhttp=getxmlhttp();
xmlhttp.open("GET",url_str);
xmlhttp.onreadystatechange = function () {
	if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		var group_str=xmlhttp.responseText;//div_str
		if(group_str!='0') {

		var split_div=group_str.split(",");
		var no = split_div.length;

		for(f=0; f< no; f++) {
			div_spec=split_div[f];
			var split_spec=div_spec.split("=");
			var div_name=split_spec[0];
			if(split_spec[1]==1)
				document.getElementById(div_name).style.display='block';
			else document.getElementById(div_name).style.display='none';
		}
		}
	}
}	
	xmlhttp.send(null);
}

function onDeleteLanguage(str, id) {

	if (myConfirm(str)==false) return;

	var url_str="include/actions.php?action=delete&object=language&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onLanguageEnable(id, path, enable, disable) {

	var url_str="include/actions.php?action=enable&object=language&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_active'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onLanguageDisable(\''+id+'\',\''+path+'\',\''+enable+'\',\''+disable+'\');" style="text-decoration: none;"> <img src="'+path+'images/deactivate.gif" class="tooltip" name="'+disable+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);
}

function onLanguageDisable(id, path, enable, disable) {

	var url_str="include/actions.php?action=disable&object=language&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var div='div_active'+id;
			document.getElementById(div).innerHTML='<a href="javascript:;" onclick="onLanguageEnable(\''+id+'\',\''+path+'\',\''+enable+'\',\''+disable+'\');" style="text-decoration: none;"> <img src="'+path+'images/activate.gif" class="tooltip" name="'+enable+'" alt=""> </a>';
		}
	}
	xmlhttp.send(null);
}

function onLanguageMoveUp(id) {

	var url_str="include/actions.php?action=move_up&object=language&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onLanguageMoveDown(id) {

	var url_str="include/actions.php?action=move_down&object=language&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function onLanguageDefault(id) {

	var url_str="include/actions.php?action=default&object=language&id="+id;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			location.reload(true);
		}
	}
	xmlhttp.send(null);
}

function savePriority(id) {

	var name_field = "name"+id;
	var price_field = "price"+id;
	var name_value = document.getElementById(name_field).value;
	var price_value = document.getElementById(price_field).value;
	var url_str="include/actions.php?action=edit&object=pri&id="+id+"&name="+name_value+"&price="+price_value;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str);
	xmlhttp.onreadystatechange = function () {
	if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		var response=xmlhttp.responseText;//div_str
		if(response==1) {
			var name_span = "span_name"+id;
			var price_span = "span_price"+id;
			var name_div = "div_name"+id;
			var price_div = "div_price"+id;
			document.getElementById(name_span).innerHTML = name_value;
			document.getElementById(price_span).innerHTML = price_value;

			document.getElementById(name_div).style.display = "none";
			document.getElementById(price_div).style.display = "none";
		
		} else alert(response);
	}
	}	
	xmlhttp.send(null);
}

function saveProcessorTitle(id) {

	var title_field = "title"+id;
	var title_value = document.getElementById(title_field).value;
	var url_str="include/actions.php?action=edit&object=processor_title&id="+id+"&title="+title_value;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str,true);
	xmlhttp.onreadystatechange = function () {
	if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		var response=xmlhttp.responseText;//div_str
		if(response==1) {
			var title_span = "span_title"+id;
			var title_div = "div_title"+id;
			document.getElementById(title_span).innerHTML = title_value;

			document.getElementById(title_div).style.display = "none";
		
		} else alert(response);
	}
	}	
	xmlhttp.send(null);
}

function saveRecurring(id) {

	var recurring_field = "recurring"+id;
	var recurring_value;
	if(document.getElementById(recurring_field+"_2").checked) recurring_value = 2;
	else if(document.getElementById(recurring_field+"_1").checked) recurring_value = 1;
	else recurring_value = 0;

	var url_str="include/actions.php?action=edit&object=processor_recurring&id="+id+"&recurring="+recurring_value;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str,true);
	xmlhttp.onreadystatechange = function () {
	if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		var response=xmlhttp.responseText;//div_str
		if(response==1) {
			//var recurring_span = "span_recurring"+id;
			var recurring_div = "div_recurring"+id;
			//document.getElementById(recurring_span).innerHTML = recurring_value;
			document.getElementById(recurring_div).style.display = "none";
		
		} else alert(response);

		location.reload(true);
	}
	}	
	xmlhttp.send(null);
}

function chooseBannerPosition(myForm) {

	var pos = myForm.position.value;

	var url_str="include/get_info.php?type=positions&pos="+pos;
	var xmlhttp=getxmlhttp();
	xmlhttp.open("GET",url_str,true);
	xmlhttp.onreadystatechange = function () {
	if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {

		var response=xmlhttp.responseText;
		if(response==1) {
			document.getElementById("div_choose_sections").style.display='none';
			document.getElementById("div_sections").style.display='none';
		} else { 
			document.getElementById("div_choose_sections").style.display='block';
			document.getElementById("div_sections").style.display='none';
		}

	}
	}	
	xmlhttp.send(null);

}

function onChooseRuleType(fname) {

	var val = document.getElementById('type').value;

	if(val=="allowed") {
		document.getElementById("div_allow").style.display = "block";
		document.getElementById("div_required").style.display = "none";
		document.getElementById("div_required_gr").style.display = "none";
	}
	else if(val=="required") {
		document.getElementById("div_allow").style.display = "none";
		document.getElementById("div_required").style.display = "block";
		document.getElementById("div_required_gr").style.display = "none";
	}
	else if(val=="required_gr") {
		document.getElementById("div_allow").style.display = "none";
		document.getElementById("div_required").style.display = "none";
		document.getElementById("div_required_gr").style.display = "block";
	}

}

