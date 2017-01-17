<?php
	include_once 'dbConnect.php';
    
    $callFrom = (isset($_REQUEST["callFrom"])) ? $_REQUEST["callFrom"] : '';
	$sql = (isset($_REQUEST["sql"])) ? $_REQUEST["sql"] : '';
	
    if ($callFrom == 'Grid') {
        prepareGridData($sql);
    } else if ($callFrom == 'Combo') {
        prepareComboData($sql);
    }
	
    /*
     * [Generate xml to data in Grid]
     */
	function prepareGridData($sql){
        $posStart = 0;
        
        $link = connectDatabase();
		$result = $link->query($sql);
		
        ob_clean();
		header("Content-type:text/xml");
		print("<?xml version=\"1.0\"  encoding=\"UTF-8\"?>");
		print("<rows total_count='".$result->num_rows."' pos='".$posStart."'>");
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_row()) {
                print("<row id='".$posStart."'>");

                foreach ($row as $key => $value) {
                    print("<cell><![CDATA[");
                    print($value);
                    print("]]></cell>");
                }

                print("</row>");
                $posStart++;
            
            }
        } 
        print("</rows>");
	}

    /*
     * [Generate xml to data in Combo]
     */
    function prepareComboData($sql) {
        $link = connectDatabase();
		$result = $link->query($sql);

        ob_clean();
        header("Content-type:text/xml");
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<complete>';
        $xml .= '	<option value=""></option>';
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_row()) {
                $xml .= '	<option value="'.$row[0].'"><![CDATA['.$row[1].']]></option>';
            }
        } 
        
        $xml .= '</complete>';

        print_r($xml);
    }
    
?>