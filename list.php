<?php
	header('Content-Type: application/json');
	///////////////Config//////////////
	$perPage = 10;
	$page = $_GET["page"]>0 ? ($_GET["page"]*10) : 0;
	/////////////////////////////////
	$file = "list.json";
	$handle = new SplFileObject($file,"a+");
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		addEntrie($handle);
	}

	function readEntries ($handle,$page,$lines)
	{
		$data = [];
		if($page)
			$handle->seek($page);
		else
		{
			$handle->rewind();
		}
		for ($i=0;$i<$lines && !$handle->eof();$i++)
		{
			if(count(($str = json_decode($handle->fgets()))))
			{
				array_push ( $data, $str);	
			}
	    	
	    }
		$page = $handle->key();
		$result = ['status' => 'ok','data'=> $data, 'entries'=>countEntries($handle)];
		return $result;
	}

	function addEntrie($handle){
		$newEntry = file_get_contents("php://input");
		$handle->fwrite($newEntry.PHP_EOL);
	}
	function countEntries($handle){
		$handle->rewind();
		while($handle->fgets())
		{
		}
		$entries = $handle->key();
		return $entries-1;
	}
	
	echo json_encode(readEntries($handle,$page,$perPage));

	//echo "Памяти использовано: ".round(memory_get_usage()/1024,2)." кb";
	//echo "В пик использовано: ".round(memory_get_peak_usage()/1024,2)." кb";

