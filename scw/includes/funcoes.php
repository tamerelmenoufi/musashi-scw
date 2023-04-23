<?php
	function dataBr($d){
		$l = explode(" ",$d);
		$dt = explode("-",$l[0]);
		if($dt[2]*1 and $dt[1]*1 and $dt[0]*1){
			return $dt[2]."/".$dt[1]."/".$dt[0].(($l[1]) ? " ".$l[1] : false);
		}else{
			return false;
		}
	}
	function dataMysql($d){
		$l = explode(" ",$d);
		$dt = explode("/",$l[0]);
		if($dt[2] and $dt[1] and $dt[0]){
			return $dt[2]."-".$dt[1]."-".$dt[0].(($l[1]) ? " ".$l[1] : false);
		}else{
			return false;		
		}
	}
