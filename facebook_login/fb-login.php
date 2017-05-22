<!DOCTYPE html>
<html>
<head>
    <title>13570340</title>
<meta charset="utf-8">
</head>
<body>

<?php
 
 if(isset($accessToken)){
    if(isset($_SESSION['facebook_access_token'])){
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }else{
        // Put short-lived access token in session
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        
          // OAuth 2.0 client handler helps to manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
        
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        
        // Set default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    
    // Redirect the user back to the same page if url has "code" parameter in query string
    if(isset($_GET['code'])){
        header('Location: ./');
    }
    // Getting user facebook profile info
    try {
        $profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
        $fbUserProfile = $profileRequest->getGraphNode()->asArray();
    } catch(FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
        include_once('connect.php');

        if ($conn->connect_error) {
            die("connection failed : ".$conn -> connect_error);
        }else{

            //variable FB Data
            $facebook_id = $fbUserProfile["id"];
            $facebook_name =  $fbUserProfile["name"];
            $facebook_fname = $fbUserProfile["first_name"];
            $facebook_lname = $fbUserProfile["last_name"];
            $facebook_email = $fbUserProfile["email"];
            $facebook_link = $fbUserProfile["link"];
            $facebook_gender = $fbUserProfile["gender"];
            $facebook_locale = $fbUserProfile["locale"];
            $facebook_img = $fbUserProfile["picture"];
            $facebook_imgURL = $facebook_img["url"];


            $sql = "SELECT * FROM `facebook_login` WHERE facebook_id = '$facebook_id'";
            $result = $conn->query($sql);


            if ($result -> num_rows > 0){
    
            }else{
                $sql = "INSERT INTO `facebook-Login` 
                    (`ID`, `facebook_id`, `facebook_name`, `facebook_fname`, `facebook_lname`, `facebook_link`, `facebook_gender`, `facebook_locale`, `facebook_img`) 
                    VALUES (NULL, '$facebook_id', '$facebook_name', '$facebook_fname', '$facebook_lname', '$facebook_link', '$facebook_gender', '$facebook_locale', '$facebook_imgURL')";
                
                if ($conn->query($sql) === TRUE) {
                    echo "INSERT COMPLETE";
                }else{
                    echo "Error";
                    echo $sql;
                }
                
                $conn->close();
            }
        } 


    
}else{
    $fbloginUrl = $helper->getLoginUrl($fbRedirectURL, $fbPermissions);

    ?>
    
        <div class="box-info">
        <?php

        echo '<a href="'.$fbloginUrl.'" class="myButton"> <button class="box-but"> Login with Facebook  </button>  </a>';
        ?>
        </div>
   
    <?php
    
}
?>
</body>
</html>
