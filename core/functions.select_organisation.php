<?php
# collection of functions to select information about organisation from Database



if(empty($db))
  $db = DB::connectDB();



/*
 * selects organisation entry from db without any special parameters
 */
function getOrganisation($organisationId)
{
	global $db;
        if(is_array($organisationId))
        {
            // first check the array
            if(empty($organisationId))
               return false;

            $organisationIdArray = array();

            foreach($organisationId as $organisationIdEntry)
            {
                // numeric entries only
                if(is_numeric($organisationIdEntry))
                  $organisationIdArray[] = $organisationIdEntry;
            }

            $where = " tl_organisations.id in (".implode(', ',$organisationIdArray).")";
        }
        else
        {
           //single id
           $where = " tl_organisations.id = ".(0+$organisationId);
        }



	$query = "
		 SELECT
            tl_organisations.*
                 FROM
		    `".$GLOBALS['dataBaseToUse']."`.`tl_organisations`

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
function allOrganisation() {
  global $db;

  $query = "
    SELECT
      *
    FROM
      `".$GLOBALS['dataBaseToUse']."`.`tl_organisations`
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
 * selects multiple Organisation entries from db using parameters
 */
function selectOrganisation($parameters)
{
	global $db;
        // check the parameters and add to where clause
        $where = '';







        // find Organisation by string
        if(!empty($parameters['searchAll']))
        {
            // there are two possibilities: find string in keyword, file_ending or in context-info


            // check in DB for entries matching
            $query = "
               SELECT
                `id`
               FROM
                 `".$GLOBALS['dataBaseToUse']."`.`tl_organisations`
               WHERE
                 (`contact_firstname` like '%".addslashes($parameters['searchAll'])."%'
                   OR `contact_lastname` like '%".addslashes($parameters['searchAll'])."%'
                   OR `street` like '%".addslashes($parameters['searchAll'])."%'
                   OR `city` like '%".addslashes($parameters['searchAll'])."%'
                   OR `postal` like '%".addslashes($parameters['searchAll'])."%')
               ";


           $result = $db -> query($query);

           $organisationIdArray = array(0);
           while($row = mysqli_fetch_assoc($result))
           {
             $organisationIdArray[] = $row['id'];
           }

           $where .= "
                         `tl_organisations`.`id` in (".implode(', ',$organisationIdArray).")";

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

        // select organisation

        // get ids for organisation entries
	$query = "
		 SELECT
                    SQL_CALC_FOUND_ROWS
                    `tl_organisations`.`id`
                 FROM
		    `".$GLOBALS['dataBaseToUse']."`.`tl_organisations`

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
            $organisationData = getOrganisation(array_keys($return['data']));
            #print_r($organisationData);
            foreach($organisationData as $idOrganisation => $row)
              $return['data'][$idOrganisation]  = $row;
        }


        return $return;

}



?>
