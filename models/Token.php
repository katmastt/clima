<?php

namespace app\models;


use Yii;

class Token extends \yii\db\ActiveRecord
{
    public static function ProjectRegistered($URL, $headers){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $project_exists = curl_exec($ch);
        curl_close($ch);
        
        if(strpos($project_exists, "No context with name ") == true){
            return 0;
        } else {
            return 1;
        }

    }

    public static function RegisterProject($URL, $headers, $project_post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://62.217.122.242:80/api_auth/contexts");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $project_post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_exec($ch);
        curl_close($ch);
    }

    public static function GetTokens($URL, $headers){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $tokens = curl_exec($ch);
        curl_close($ch);
        //echo "$tokens";
        $tokens_temp = $tokens;
        $strArray = explode(':',$tokens);
        //$tokens = $strArray[0];
        //echo "$tokens";
        $issued_tokens = count($strArray)-1;
        //echo "$tokens";
        $strArray = explode('"',$tokens_temp);
        return [$issued_tokens, $strArray];
    }

    public static function GetTokenDetails($URL, $headers){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $token_details = curl_exec($ch);
        curl_close($ch);
        $strArray_td = explode(',',$token_details);
			foreach ($strArray_td as $detail) {
				$detail_t = $detail;
				if (strpos($detail_t, 'title') == true){
					//echo "$piece". "<br>";
					$strArray_title = explode(':',$detail_t);
					$title = $strArray_title[1];
					$title = str_replace('"', '', $title);
					//echo "$title". "<br>";
				} elseif (strpos($detail_t, 'expiry') == true){
					$strArray_expiry = explode('T',$detail_t);
					$expiry_t = $strArray_expiry[0];
					$strArray_expiry = explode(':',$expiry_t);
					$expiry = $strArray_expiry[1];
					$expiry = str_replace('"', '', $expiry);
					//echo "$expiry". "<br>";
					$today = date("Y-m-d");
					$today_date = new \DateTime(date("Y-m-d"));
					$expiry_date = new \DateTime($expiry);
					if ($expiry > $today) {
						$interval = $expiry_date->diff($today_date);
						//echo "difference " . $interval->days . " days "."<br>";
						$exp_days = $interval->days;
						$active = "Yes";
					} else {
						$exp_days = 0;
						$active = "No";
						
					}
				} elseif (strpos($detail_t, 'uuid') == true){
					$strArray_uuid = explode(':',$detail_t);
					$uuid = $strArray_uuid[1];
					$uuid = str_replace('"', '', $uuid);
					//echo "$uuid"."<br>";
				}
			}
        return [$title, $expiry_date, $exp_days, $active, $uuid];

    }

    public static function EditToken($URL, $headers, $patch){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $patch);
        $out = curl_exec($ch);
        curl_close($ch);

    }

    public static function CreateNewToken($URL, $headers, $pname, $exp_date){

        $time = date('h:i:s');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (empty($pname)){
            $post = '{"expiry":"'.$exp_date->format("Y-m-d")."T".$time.'.000000"}';
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $token = curl_exec($ch);
    
        } 
        else{
            //if the user provided a name then make the api call with that name 
            $temp1 = '{"title":';
            $temp2 = '"';
            $temp4 = $temp2.$pname.$temp2;
            $post = $temp1.$temp4.',"expiry":"'.$exp_date->format("Y-m-d")."T".$time.'.000000"}';
    
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $token = curl_exec($ch);
            curl_close($ch);

        }
        $strArray = explode(':',$token);
        $token = $strArray[1];
        $token = str_replace('"', '', $token);
        $token = str_replace('}', '', $token);
        return $token;

    }

    public static function DeleteToken($URL, $headers){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $out = curl_exec($ch);
        curl_close($ch);

    }
}
?>