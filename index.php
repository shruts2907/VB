<?php
	function __autoload($className) {
		require_once $className . '.class.php';
	}

	if(file_exists("fetchJson.php")){
		include_once('fetchJson.php');

	}else{
		echo "failed to fetch data from vanderbilt fb page ";
		die();
	}

	//array to store the data from fetching the json from fb page
	$arr = array();
	$input = array();
	//$arr gives all infor from id to likes
	$arr = $obj->data;
	//to check likes key
	$count = floor(count($arr));
	$db = Database::getInstance();

	//iterating through the number of facebook photos
	for($i=0; $i<$count; $i++){
		$likes_array = $arr[$i]->likes;
		//no of likes of first like array
		$no_of_likes = count($likes_array->data);
		$paging = $likes_array->paging;
		$t_likes = fetch_likes($paging,$no_of_likes);
		$total_likes = "".$t_likes;
		$id = $arr[$i]->id;
		$name = $arr[$i]->from->name;
		$source = $arr[$i]->source;
		$time = $arr[$i]->created_time;


		$usertable ="uploadTable";

		$types =  "s";
		$vars = array($id);
		$query = "SELECT * FROM uploadTable where id=? ";
		$sql = $db->doSelect($query, $vars,$types);
		if($sql){
			//insert
			//echo "  in if $sql select success "."<br/>";
			$insert = "INSERT INTO uploadTable values (?,?,?,?,?)";
			$array = array($id,$name,$source,$time,$total_likes);
			$types = "sssss";
			$sqlQuery = $db->doInsert($insert, $array,$types);
			if($sqlQuery){
				//success
				//echo "Insert success ";
			}else{
				//failure
				echo " failed to insert ";
				die();
			}
		}else{
			//update
			$update = "UPDATE  uploadTable SET  name=?,source=?,time=?,likes=? WHERE id=?";
			$types = "sssss";
			$args = array($name,$source,$time,$total_likes,$id);
			$sqlUpdateQuery = $db->doInsert($update, $args,$types);
			if($sqlUpdateQuery){
				//success
				//echo " Upate success ";
			}else{
				//failure
				echo " Update Fail ";
				die();
			}
		}


	}

	function fetch_likes($url, $cLikes){
		
		if(property_exists($url, "next")){
			$fetch = file_get_contents($url->next);
			$content = json_decode($fetch);
			$no_likes  =  count($content->data);
			return $cLikes +fetch_likes($content->paging,$no_likes);

		}
		return $cLikes;

	}//end of fetch likes function;

	$info = $db->display();
	$length_info = count($info);
?>
<!DOCTYPE html> 
<html > 
<head> 
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="index.css">

</head> 
<body> 
	<div class="main">
	<?php
        for($i=0; $i<$length_info; $i++){
			$id = $info[$i]['id'];
			$name = trim($info[$i]['name']);
			$src = $info[$i]['source'];
			$timestamp = $info[$i]['time'];
			$date_split = str_split($timestamp,10);
			$date = explode("-",$date_split [0]);
			$year = $date[0];
			$month = $date[1];
			$day = $date[2];
			$time = $month."/".$day;
			$likes = $info[$i]['likes'];

	?>  
	<!-- anchor and image tag to display images fetched from db -->
	<a href="#" onclick="doSomething('<?php echo $name;?>','<?php echo $time;?>',' <?php echo $src;?>','<?php echo $likes;?>');" > 
		<img src="<?php echo $src;?>" alt="<?php echo $name;?>" height="300" width="359">
	</a>

	<?php
    	}
    ?>
    </div>    
	<script type="text/javascript">
		//function to give details when clicked on the image
		function doSomething(name,time,imgSrc,likes){

			//imag stores the image tag for clicked image
			var img ="<div class='imageClass'><img src="+imgSrc+" alt='name' height='400' width='480'><div>";
			//content stores the info of clicked images
			var content ="<div class='name shadow'>"+name+"</div>"+
			"<div class='time shadow'><i class='fa fa-calendar fa-2x'></i>"+"  "+time+"</div>"+
			"<div class='likes shadow'><i class='fa fa-thumbs-o-up fa-2x'></i>"+"  "+likes+"</div>";

			//appending cover id tag to body to blur out action in background
			$("body").append('<div id="cover"><div class="popScreen"><a href="#" class="cancel">&times;</a> </div> </div> ');

			//attaching image tag of clikced immage to element with class pop screen
			$('.popScreen').append(img);
			$('.popScreen').append(content);

			//removing the enlarged image and returning to main screen
			$('.cancel').on('click',function(){

				$('#cover').remove();
			});

		}
	</script>
</body> 
</html>