 <?php include_once('functions.php'); ?>

<div class="wrap">
    <h1>Media Categorizer</h1>
    <h3>All you have to do is press a button ! All the media files contained in the upload directory will be
    categorized into subfolders by years and months</h3>
    <h5>Please note that if you have a large number of media files it may take while...</h5>
    <form action="<?php plugin_dir_url( __FILE__ ).'test-folder-plugin-form.php'?>" method="POST">    	
    	<label>Categorize media files</label>
    	<input type="submit" name="test" value="Do Now !!!">
    </form>
    <?php   
        $count = 0 ; // number of files moved
        $baseCount = 0 ; // number of main media files
    	if(isset($_POST['test'])){
    		$uploadDirPath = wp_upload_dir();   		

    		/*calling to database*/
    		$rows = getPostsToMove($uploadDirPath['baseurl']);

            if(count($rows) == 0){
                echo "<h3>No files are available in the upload directory to be moved to subfolders</h3>";
                die();
            }
    		
    		foreach ($rows as $key => $value) {
    			$fileName = str_replace($uploadDirPath['baseurl'].'/', "", $value->guid);    			

    			$postDatestr = $value->post_date ;
    			$postDateTime = strtotime($postDatestr);
    			$postDate = getdate($postDateTime);
    			
    			$month = $postDate['mon'];
    			$year = $postDate['year'];

    			if(intval($month) < 10){
    				$month = '0'.$month ;
    			}
    			

    			$newPartialDirPath = $year.'/'.$month ;

    			$filePattern = substr_replace($fileName, "", strrpos($fileName, "."));
    			$pureFileName = $filePattern ; // for later use
    			$filePattern = '/'.$filePattern.'*.*';  // pattern for the other images but not the base image : '*-*.*'
    			$files = glob($uploadDirPath['basedir'].$filePattern); // finding the all files that preg_match(pattern, subject)

    			$monthFolder = "";                

    			foreach ($files as $file) {    				
    				$yearFolder = $uploadDirPath['basedir'].'/'.$year ; 
    				$monthFolder = $uploadDirPath['basedir'].'/'.$year.'/'.$month ;

    				if(!is_dir($yearFolder)){
    					mkdir($yearFolder);
    					mkdir($monthFolder);
    				}
    				else if(!is_dir($monthFolder)){
    					mkdir($monthFolder);
    				}

    				$newLocation = str_replace($uploadDirPath['basedir'], $monthFolder, $file) ;

    				rename($file, $newLocation);
                    $count++ ;
    			}

    			$monthFolder = str_replace($uploadDirPath['basedir'], $uploadDirPath['baseurl'], $monthFolder);
    			$newPostLocation = str_replace($uploadDirPath['baseurl'], $monthFolder, $value->guid);    			
    			$newPostMetaLocation = $newPartialDirPath.'/'.$fileName;
    			$newPostMetaName =  $year.'/'.$month.'/'.$pureFileName;    			
    			
    			updatePostTable($value->ID,$newPostLocation);
    			updatePostmetaTable($value->ID,$newPostMetaLocation);
    			updateParent($value->post_parent,$uploadDirPath['baseurl'].'/'.$pureFileName,$monthFolder.'/'.$pureFileName);    			
    			restore_image_meta($value->ID,$year.'/'.$month.'/');		
                $baseCount++ ;
    		}

            echo "<h3> Total number of $count Files has been successfully moved </h3>";
            echo "<h4> Total number of $baseCount uploaded Files has been successfully moved </h4>";
    	}
    ?>
</div>