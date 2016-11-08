<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Edoc_wpet_pagination{
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $limit;
	var $return;
	var $default_ipp;
	var $querystring;
	var $ipp_array;
	var $current_url;
	function Edoc_wpet_pagination()
	{
		$this->current_page = 1;
		$this->mid_range = 5;
		$this->ipp_array = array(10,20,30,40,50,100);
		$this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;
	}

	function paginate()
	{
		if(!isset($this->default_ipp)) $this->default_ipp=10;
		if($this->items_per_page == 'All')
		{
			$this->num_pages = 1;	
		}
		else
		{
			if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
			$this->num_pages = ceil($this->items_total/$this->items_per_page);
		}
		$this->current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;
		if($_GET)
		{
			$args = explode("&",$_SERVER['QUERY_STRING']);
			foreach($args as $arg)
			{
				$keyval = explode("=",$arg);
				if($keyval[0] != "paged" And $keyval[0] != "ipp") $this->querystring .= "&" . $arg;
			}
		}

		if($_POST)
		{
			foreach($_POST as $key=>$val)
			{
				if($key != "paged" And $key != "ipp") $this->querystring .= "&$key=$val";
			}
		}
		if($this->num_pages > 4)
		{
			if(strpos($this->current_url,'?') > 0){
				$link = $this->current_url.'&paged='.$prev_page.'&ipp='.$this->items_per_page;
			}else{
				$link = $this->current_url.'?paged='.$prev_page.'&ipp='.$this->items_per_page.$this->querystring;
			}
			$link = esc_url(wp_nonce_url($link,'doing_pagging','nonce'));
			$this->return = ($this->current_page > 1 And $this->items_total >= 10) ? "<li><a class=\"paginate\" href=\"$link\">&#8592; Previous</a></li> ":"<li><span class=\"inactive\" href=\"#\">&#8592; Previous</span></li>";

			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range,$this->end_range);

			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= "<li> ... </li>";
				// loop through all pages. if first, last, or in range, display
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
				{
					if(strpos($this->current_url,'?') > 0){
						$link = $this->current_url.'&paged='.$i.'&ipp='.$this->items_per_page;
					}else{
						$link = $this->current_url.'?paged='.$i.'&ipp='.$this->items_per_page.$this->querystring;
					}
					$link = esc_url(wp_nonce_url($link,'doing_pagging','nonce'));
					$this->return .= ($i == $this->current_page And $_GET['paged'] != 'All') ? "<li  class=\"active_page\">$i</li> ":"<li><a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$link\">$i</a></li> ";
				}
				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= "<li> ... </li>";
			}
			if(strpos($this->current_url,'?') > 0){
				$nextlink = $this->current_url.'&paged='.$next_page.'&ipp='.$this->items_per_page;
			}else{
				$nextlink = $this->current_url.'?paged='.$next_page.'&ipp='.$this->items_per_page.$this->querystring;
			}
			$nextlink = esc_url(wp_nonce_url($nextlink,'doing_pagging','nonce'));
			$this->return .= (($this->current_page < $this->num_pages And $this->items_total >= 10) And ($_GET['paged'] != 'All') And $this->current_page > 0) ? "<li><a class=\"paginate\" href=\"$nextlink\">Next &rarr;</a></li>\n":"<li><span class=\"inactive\" href=\"#\">Next &rarr;</span></li>\n";
		}
		else
		{
			for($i=1;$i<=$this->num_pages;$i++)
			{
				
				if(strpos($this->current_url,'?') > 0){
					$link = $this->current_url.'&paged='.$i.'&ipp='.$this->items_per_page;
				}else{
					$link = $this->current_url.'?paged='.$i.'&ipp='.$this->items_per_page.$this->querystring;
				}
				$link = esc_url(wp_nonce_url($link,'doing_pagging','nonce'));
				$this->return .= ($i == $this->current_page) ? "<li class=\"active_page\">$i</li> ":"<li><a class=\"paginate\" href=\"$link\">$i</a></li> ";
			}
		}
		$this->low = ($this->current_page <= 0) ? 0:($this->current_page-1) * $this->items_per_page;
		if($this->current_page <= 0) $this->items_per_page = 0;
	}
	function display_items_per_page()
	{
		$items = '';
		if(!isset($_GET['ipp'])) $this->items_per_page = $this->default_ipp;
		foreach($this->ipp_array as $ipp_opt) $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
		return "Records per page <br><select class=\"paginate\" onchange=\"window.location='$this->current_url?paged=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select><br><span class=\"paginate\"></span>";
	}
	function display_jump_menu()
	{
		for($i=1;$i<=$this->num_pages;$i++)
		{
			$option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
		}
		return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$this->current_url?paged='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select>\n";
	}
	function display_pages()
	{
		return $this->return;
	}
}
?>