<?php
# collection of functions to select information about helpers from Database




if(empty($db))
  $db = DB::connectDB();



/*
 * selects helper entry from db without any special parameters
 */
function getHelper($helperId)
{
	global $db;
        if(is_array($helperId))
        {
            // first check the array
            if(empty($helperId))
               return false;

            $helperIdArray = array();

            foreach($helperId as $helperIdEntry)
            {
                // numeric entries only
                if(is_numeric($helperIdEntry))
                  $helperIdArray[] = $helperIdEntry;
            }

            $where = " tl_helper.id in (".implode(', ',$helperIdArray).")";
        }
        else
        {
           //single id
           $where = " tl_helper.id = ".(0+$helperId);
        }



	$query = "
		 SELECT
            tl_helper.*
                 FROM
		    `".$GLOBALS['dataBaseToUse']."`.`tl_helper`

		 WHERE
		    ".$where."

	   ";

	$result = $db -> query($query);

        // initializing returndata
        $return = array();

        while($row = mysqli_fetch_assoc($result))
        {
           $return[$row['id']] = $row;
        }

	 return $return;
}


// get all Organisations
function allHelper() {
  global $db;

  $query = "
    SELECT
      *
    FROM
      `".$GLOBALS['dataBaseToUse']."`.`tl_helper`
    ";

  $result = $db -> query($query);
  // initializing returndata
  $return = array();

  while($row = mysqli_fetch_assoc($result))
  {
    $return[$row['id']] = $row;
  }

  return $return;
}

/*
 * selects multiple Helper entries from db using parameters
 */
function selectHelper($parameters)
{
	global $db;
        // check the parameters and add to where clause
        $where = '';

        // find helper by string
        if(!empty($parameters['searchAll']))
        {
            // there are two possibilities: find string in keyword, file_ending or in context-info


            // check in DB for entries matching
            $query = "
               SELECT
                `id`
               FROM
                 `".$GLOBALS['dataBaseToUse']."`.`tl_helper`
               WHERE
                 (`firstname` like '%".addslashes($parameters['searchAll'])."%'
                   OR `lastname` like '%".addslashes($parameters['searchAll'])."%'
                   OR `street` like '%".addslashes($parameters['searchAll'])."%'
                   OR `city` like '%".addslashes($parameters['searchAll'])."%'
                   OR `postal` like '%".addslashes($parameters['searchAll'])."%')
               ";


           $result = $db -> query($query);

           $helperIdArray = array(0);
           while($row = mysqli_fetch_assoc($result))
           {
             $helperIdArray[] = $row['id'];
           }

           $where .= "
                         `tl_helper`.`id` in (".implode(', ',$helperIdArray).")";

        }

        // paging contains results per block as blocksize and number of block used for the query
        if(!empty($parameters['paging']))
        {
            // blocksize from setting or standard
            if(!empty($parameters['paging']['blocksize']))
              $blocksize = 0+$parameters['paging']['blocksize'];
            else
              $blocksize = 20;

            if(!empty($parameters['paging']['block']))
              $block = 0+$parameters['paging']['block'];
            else
              $block = 1;

            $parameters['limit'] = " LIMIT ".(($block-1) * $blocksize).", ".$blocksize." ";
        }

        if(!empty($parameters['order']))
          $where .= addslashes($parameters['order']);

        if(!empty($parameters['limit']))
          $where .= addslashes($parameters['limit']);

        // select Helper

        // get ids for helper entries
	$query = "
		 SELECT
                    SQL_CALC_FOUND_ROWS
                    `tl_helper`.`id`
                 FROM
		    `".$GLOBALS['dataBaseToUse']."`.`tl_helper`

		 WHERE

                    ".$where."
	   ";

	$result = $db -> query($query);


        $return = array();

        // reports
        $return['parameters'] = $parameters;
        $return['numResults'] = '';

        while($row = mysqli_fetch_assoc($result))
        {
           $return['data'][$row['id']] = array();
        }

        // number of possible results
        $countResult = $db -> query("SELECT FOUND_ROWS()");
        $countArray = $row = mysqli_fetch_assoc($countResult);
        $return['numResults'] =  $countArray['FOUND_ROWS()'];

        // now get the values
        if(!empty($return['data']))
        {
            $helperData = getHelper(array_keys($return['data']));
            #print_r($helperData);
            foreach($helperData as $idHelper => $row)
              $return['data'][$idHelper]  = $row;
        }


        return $return;

}



?>
