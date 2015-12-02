<?php
# collection of functions to store media information to media,
# media_context, media_cluster and media_cluster_2_media




if(empty($db))
  $db = DB::connectDB();



/*
 * selects media entry from db without any special parameters
 */
function getMedia($mediaId)
{
	global $db;
        if(is_array($mediaId))
        {
            // first check the array
            if(empty($mediaId))
               return false;

            $mediaIdArray = array();

            foreach($mediaId as $mediaIdEntry)
            {
                // numeric entries only
                if(is_numeric($mediaIdEntry))
                  $mediaIdArray[] = $mediaIdEntry;
            }

            $where = " media.id in (".implode(', ',$mediaIdArray).")";
        }
        else
        {
           //single id
           $where = " media.id = ".(0+$mediaId);
        }



	$query = "
		 SELECT
                    media.*,
                    media_type.content_type
                 FROM
		    `".$GLOBALS['dataBaseToUse']."`.`media`
                    LEFT JOIN `".$GLOBALS['dataBaseToUse']."`.`media_type` ON media.id_media_type = media_type.id
		 WHERE
		    ".$where."
                    AND media.deleted = 0
	   ";

	$result = $db -> query($query);

        // initializing returndata
        $return = array();

        while($row = mysqli_fetch_assoc($result))
        {
           $return[$row['id']] = $row;
        }

        if(!empty($return))
        {
        //get context information for media entry

          $query = "
            SELECT
             media_context.*
            FROM
             `".$GLOBALS['dataBaseToUse']."`.`media_context`
            WHERE
             id_media in (".implode(', ',array_keys($return)).")
             AND id_media_cluster = 0
             AND deleted = 0
            ";

           $result = $db -> query($query);
           while($row = mysqli_fetch_assoc($result))
           {
             $return[$row['id_media']]['contextInformation'][$row['id_language']] = $row;
           }
	 }

	 return $return;
}

/*
 * selects multiple media entries from db using parameters
 */
function selectMedia($parameters)
{
	global $db;
        // check the parameters and add to where clause
        $where = '';

        // id_project
        if(!empty($parameters['id_project']))
        {
            if(is_array($parameters['id_project']))
            {
                $createdByArray = array();
                foreach($parameters['id_project'] as $id_project)
                {
                    if(is_numeric($id_project))
                        $createdByArray[] = $id_project;
                }
                $where .= " AND id_project in (".implode(', ',$createdByArray).") ";
            }
            else
               $where .= " AND id_project = ".(0+$parameters['id_project'])." ";
        }

        //updated since
        if(!empty($parameters['updatedSince']))
        {
           // has to be unix timestamp
           if(is_numeric($parameters['updatedSince']))
              $where .= " AND ( created_at > '".date('Y-m-d H:i:s',$parameters['updatedSince'])."' or updated_at > '".date('Y-m-d H:i:s',$parameters['updatedSince'])."' )";
        }

        //id media
        if(!empty($parameters['idMedia']))
        {
            if(is_array($parameters['idMedia']))
            {
                $mediaIdArray = array();

                foreach($parameters['idMedia'] as $mediaIdEntry)
                {
                    // numeric entries only
                    if(is_numeric($mediaIdEntry))
                      $mediaIdArray[] = $mediaIdEntry;
                }

                $where .= " AND media.id in (".implode(', ',$mediaIdArray).")";
            }
            else
            {
               //single id
               $where .= " AND media.id = ".(0+$parameters['idMedia']);
            }

        }

        // media type
        if(!empty($parameters['mediaType']))
        {
           $mediaType = addslashes($parameters['mediaType']);

           // check in DB for entries matching the mediatype
           $query = "
               SELECT
                `id`
               FROM
                 `".$GLOBALS['dataBaseToUse']."`.`media_type`
               WHERE
                 `deleted` = 0
                 AND (`content_type` like '".$mediaType."' or `file_ending` = '".$mediaType."')
               ";

           $result = $db -> query($query);

           $mediaTypeIds = array();
           while($row = mysqli_fetch_assoc($result))
           {
             $mediaTypeIds[] = $row['id'];
           }

           // if we have results use id_media_type else try to find the given media_type as file ending
           if(!empty($mediaTypeIds))
              $where .= " AND `media`.`id_media_type` in (".implode(', ',$mediaTypeIds).") ";
           elseif($mediaType != 'all')
              $where .= " AND `media`.`file_ending` = '".$mediaType."' ";



        }

        // find media by string
        if(!empty($parameters['searchAll']))
        {
            // there are two possibilities: find string in keyword, file_ending or in context-info
            // first media_context

            // check in DB for entries matching the mediatype
            $query = "
               SELECT
                `id_media`
               FROM
                 `".$GLOBALS['dataBaseToUse']."`.`media_context`
               WHERE
                 `deleted` = 0
                 AND (`title` like '%".addslashes($parameters['searchAll'])."%'
                   OR `subtitle` like '%".addslashes($parameters['searchAll'])."%'
                   OR `description` like '%".addslashes($parameters['searchAll'])."%'
                   OR `copyright` like '%".addslashes($parameters['searchAll'])."%')
               ";


           $result = $db -> query($query);

           $mediaIdArray = array(0);
           while($row = mysqli_fetch_assoc($result))
           {
             $mediaIdArray[] = $row['id_media'];
           }

           $where .= " AND (
                         `media`.`id` in (".implode(', ',$mediaIdArray).")
                         OR
                         `keywords` like '%".addslashes($parameters['searchAll'])."%'
                         OR `file_ending` like '".addslashes($parameters['searchAll'])."')";

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

        // select media

        // get ids for media entries
	$query = "
		 SELECT
                    SQL_CALC_FOUND_ROWS
                    `media`.`id`
                 FROM
		    `".$GLOBALS['dataBaseToUse']."`.`media`

		 WHERE
		    `media`.`deleted` = 0
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
            $mediaData = getMedia(array_keys($return['data']));
            #print_r($mediaData);
            foreach($mediaData as $idMedia => $row)
              $return['data'][$idMedia]  = $row;
        }


        return $return;

}



?>
