<?php
# collection of functions to select information about responsible from Database




if(empty($db))
  $db = DB::connectDB();



/*
 * selects responsible entry from db without any special parameters
 */
function getResponsible($responsibleId)
{
	global $db;
        if(is_array($responsibleId))
        {
            // first check the array
            if(empty($responsibleId))
               return false;

            $responsibleIdArray = array();

            foreach($responsibleId as $responsibleIdEntry)
            {
                // numeric entries only
                if(is_numeric($responsibleIdEntry))
                  $responsibleIdArray[] = $responsibleIdEntry;
            }

            $where = " tl_members.id in (".implode(', ',$responsibleIdArray).")";
        }
        else
        {
           //single id
           $where = " tl_members.id = ".(0+$responsibleId);
        }



	$query = "
		 SELECT
            tl_members.*
                 FROM
		    `".$GLOBALS['dataBaseToUse']."`.`tl_members`

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


// get all Members
function allResponsible() {
  global $db;

  $query = "
    SELECT
      *
    FROM
      `".$GLOBALS['dataBaseToUse']."`.`tl_members`
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
 * selects multiple responsible entries from db using parameters
 */
function selectResponsible($parameters)
{
	global $db;
        // check the parameters and add to where clause
        $where = '';







        // find responsible by string
        if(!empty($parameters['searchAll']))
        {
            // there are two possibilities: find string in keyword, file_ending or in context-info


            // check in DB for entries matching
            $query = "
               SELECT
                `id`
               FROM
                 `".$GLOBALS['dataBaseToUse']."`.`tl_members`
               WHERE
                 (`firstname` like '%".addslashes($parameters['searchAll'])."%'
                   OR `lastname` like '%".addslashes($parameters['searchAll'])."%'
                   OR `street` like '%".addslashes($parameters['searchAll'])."%'
                   OR `city` like '%".addslashes($parameters['searchAll'])."%'
                   OR `postal` like '%".addslashes($parameters['searchAll'])."%')
               ";


           $result = $db -> query($query);

           $responsibleIdArray = array(0);
           while($row = mysqli_fetch_assoc($result))
           {
             $responsibleIdArray[] = $row['id'];
           }

           $where .= "
                         `tl_members`.`id` in (".implode(', ',$responsibleIdArray).")";

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

        // select responsible

        // get ids for responsible entries
	$query = "
		 SELECT
                    SQL_CALC_FOUND_ROWS
                    `tl_members`.`id`
                 FROM
		    `".$GLOBALS['dataBaseToUse']."`.`tl_members`

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
            $responsibleData = getResponsible(array_keys($return['data']));
            #print_r($responsibleData);
            foreach($responsibleData as $idResponsible => $row)
              $return['data'][$idResponsible]  = $row;
        }


        return $return;

}



?>
