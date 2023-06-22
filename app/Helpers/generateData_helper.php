<?php

    /**
     * Generate test data
     *
     * @return file sql
     */
    function generateData()
    {
        //settings
        $rows = 3500;
        $cycle = 50;
        $table = 'bousers';
        $word = 'test';

        //generate data
        $fileContent = '';
        $count = 50;
        for($i=0; $i<$rows; $i++)
        {
            $val = $word.$i;
            if($count == $cycle){
                $fileContent .= "INSERT INTO '".$table."' (id, username, password) VALUES\r('".$i."','".$val."','".$val."'),\r";
                $count = 0;
            }
            else if ($count == $cycle-1)
            {
                $fileContent .= "('".$i."','".$val."','".$val."');\r";
            }
            else
            {
                $fileContent .= "('".$i."','".$val."','".$val."'),\r";
            }
            $count++;
        }

        $data = $this->response->download($rows."rows_".date("Y-m-d")."_".date("H-i-s").'.sql', $fileContent);
        return $data;
    }
?>