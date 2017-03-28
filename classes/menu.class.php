<?php
class MenuBuilder extends general
{
	var $par;
	var $conn;
	var $items;
	var $menu;
	var $html;
	
	function MenuBuilder()
	{
		//$this->conn = mysqli_connect( 'localhost', 'root', '', '2sense' );
		//$this->par = $par;
	}
	
	function fetch_assoc_all( $sql )
	{
		$result = mysql_query($sql) or die(mysql_error());
		
		if ( !$result )
			return false;
		
		$assoc_all = array();
		
		while( $fetch = mysql_fetch_assoc($result) )
			$assoc_all[] = $fetch;
		
		
		mysql_free_result( $result );
		
		//_p($assoc_all);
		
		return $assoc_all;
	}
	
	function get_menu_items($position)
	{
		global $language_id;
		$sql = "SELECT 	t1.id AS id, 
						t1.i_parent_id AS parent_id, 
						t2.v_title AS title,
						t1.v_type AS menutype,
						t1.v_external_link AS externallink ,
						t1.i_menu_id AS menuid
					FROM tbl_menu t1
					LEFT JOIN tbl_menu_details t2
						ON t1.id = t2.i_menu_id
					WHERE FIND_IN_SET('".$position."', t1.v_position)
						AND t1.e_status = 'active'
						AND t2.i_language_id = '".$language_id."'
					ORDER BY t1.i_order";
		return $this->fetch_assoc_all( $sql );
	}
	
	function build_menu_array($position, $wraptitle, $htmltype, $sep, $pageId)
	{
		$this->items = $this->get_menu_items($position);
		
		if ( empty( $this->items ) )
			return '';
		
		foreach ( $this->items as $item )
			$children[$item['parent_id']][] = $item;
		
		$root = array();
		//$root['html_before'] = '<ul id="0">';
		//$root['html_after'] = '</ul>';
		$root['html_before'] = '';
		$root['html_after'] = '';
		
		if($wraptitle) {
			$beforetitle = '<'.$wraptitle.'>';
			$aftertitle = '</'.$wraptitle.'>';
		}
		else {
			//$afterlink = $sep;
		}
		
		if($htmltype == 'li') {
			$beforelink = '<li>';
			$afterlink = '</li>';
		}
		else {
			//$afterlink = $sep;
		}	
			
			
		$parent = $this->par;
		$tree = array();
		$current_tree = &$tree;
		$tree_stack = $parent_stack = array();
		
		if ( empty( $children[$parent] ) )
			return '';
		
		global $gnrl;
		
		while ( ( $option = each( $children[$parent] ) ) || ( $parent > 0 ) )
		{
			if($option['value']['menutype'] == 'external') {
				$hreflink = $option['value']['externallink'];
			}	
			else {
				$halfhreflink = $gnrl->getLinkUsingTableLanguage('tbl_'.$option['value']['menutype'],$option['value']['menuid']);
				$hreflink = SITE_URL.$halfhreflink;
			}
			
			/*
			// selected starts
			$selected = '';
			if($option['value']['menutype'] == 'cms') {
				if(SEO_FRIENDLY===true)	{
				
				//exit;
					if($halfhreflink == 'page/'.$pageId) {
						$selected = 'class="active"';
					}
				}
				else {
					if($halfhreflink == 'page.php?id='.$pageId) {
						$selected = 'class="active"';
					}
				}
			}
			else {
				if($option['value']['menuid'] == $pageId) {
					$selected = 'class="active"';
				}
			}
			// selected ends
			*/
			
			if ( $option === false )
			{
				unset( $current_tree );
				$current_tree = &$tree_stack[count($tree_stack)-1];
				array_pop( $tree_stack );
				$parent = array_pop( $parent_stack );
			}
			elseif ( empty( $children[$option['value']['id']] ) )
			{
				$data = array();
				$data['html'] = str_repeat( "\t", count( $tree_stack ) + 1 ) . $beforelink .'<a href="'.$hreflink.'" '.$selected.'>' . $beforetitle . $option['value']['title'] . $aftertitle . '</a>' . $afterlink . $sep;
				$current_tree[] = $data;
			}
			else
			{
				$data = array();
				$data['html_before'] = str_repeat( "\t", count( $tree_stack ) + 1 );
				$data['html_before'] .= $beforelink . '<a href="'.$hreflink.'" '.$selected.' rel="' . $option['value']['id'] . '">' . $beforetitle . $option['value']['title'] . $aftertitle . '</a>';
				$data['html_before'] .= "\r\n" . str_repeat( "\t", count( $tree_stack ) + 1 );
				//$data['html_before'] .= '<ul id="' . $option['value']['id'] . '"><li>&nbsp;</li>';
				//$data['html_after'] .= str_repeat( "\t", count( $tree_stack ) + 1 ) . '<li class="last">&nbsp;</li></ul></li>';
				
				$data['html_before'] .= $afterlink . $sep .'<ul id="' . $option['value']['id'] . '" class="dropmenudiv_e">';
				$data['html_after'] .= str_repeat( "\t", count( $tree_stack ) + 1 ) . '</ul>';
				
				
				$data_children = array();
				$data['children'] = &$data_children;
				$current_tree[] = $data;
				$tree_stack[] = &$current_tree;
				array_push( $parent_stack, $option['value']['parent_id'] );
				unset( $current_tree );
				$current_tree = &$data_children;
				unset( $data_children );
				$parent = $option['value']['id'];
			}
		}
		
		$root['children'] = $tree;
		
		return array( $root );
	}
	
	function build_item_html( $item )
	{
		foreach ( $item as $element )
			if ( isset( $element['html'] ) )
				$this->html[] = $element['html'];
			else
			{
				$this->html[] = $element['html_before'];
				$this->build_item_html( $element['children'] );
				$this->html[] = $element['html_after'];
			}
	}
	
	function get_menu_html( $position, $wraptitle='', $htmltype='', $sep='' , $pageId='')
	{
		$this->html = array();
		$this->menu = $this->build_menu_array($position, $wraptitle, $htmltype, $sep, $pageId);
		$this->build_item_html( $this->menu );
		$return = '';
		if($sep)
			$return = rtrim(trim(implode( "\r\n", $this->html )),'|');
		else
			$return = implode( "\r\n", $this->html );
			
		$return = str_replace(' | 
	</ul>
','</ul>',$return);
		echo $return;
		
		//exit;
		
		//return $return;
	}
}
?>