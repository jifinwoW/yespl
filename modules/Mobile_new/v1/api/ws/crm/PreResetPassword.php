<?php

require_once 'includes/main/WebUI.php';
require_once 'include/utils/utils.php';
require_once 'include/utils/VtlibUtils.php';
require_once 'modules/Vtiger/helpers/ShortURL.php';

class Mobile_WS_PreResetPassword extends Mobile_WS_Controller {

    function requireLogin() {
        return false;
    }

    function process(Mobile_API_Request $request) {
        $response = new Mobile_API_Response();
        $badgeNo = $request->get('badgeNo');
        if (empty($badgeNo)) {
            $response->setError(100, 'Badge Number Is Required');
            return $response;
        }
        $emailFromRequest = $request->get('email');
        if (empty($emailFromRequest)) {
            $response->setError(100, 'Email Is Required');
            return $response;
        }
        global $adb;
        $IpAddress = $this->getClientIp() . date("YmdH");
        $sql = " select count(*) as 'count' from vtiger_shorturls "
            . " where ip_address = ?";
        $result = $adb->pquery($sql, array($IpAddress));
        $dataRow = $adb->fetchByAssoc($result, 0);
        $numberOfattempts = (int) $dataRow['count'];
        if ($numberOfattempts > 10) {
            $response->setError(100, 'Number of Password Reset Attempt is Exceeded');
            return $response;
        }
        if (!empty($badgeNo)) {
            $usercreatedid = '';
            $email = '';
            $useruniqeid = '';
            $id = '';
            $mobile = '';
            $badgeNo = vtlib_purify($badgeNo);
            $sql = 'select serviceengineerid,vtiger_serviceengineer.email,vtiger_users.id,vtiger_serviceengineer.phone,vtiger_users.accesskey from vtiger_serviceengineer ' .
                ' inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_serviceengineer.serviceengineerid ' .
                ' inner join vtiger_users on vtiger_users.user_name=vtiger_serviceengineer.badge_no ' .
                ' where vtiger_serviceengineer.badge_no = ? AND vtiger_crmentity.deleted = 0 ORDER BY serviceengineerid DESC LIMIT 1';
            $result = $adb->pquery($sql, array($badgeNo));
            if ($adb->num_rows($result) == 1 ) {
                $email = $adb->query_result($result, 0, 'email');
                $id = $adb->query_result($result, 0, 'id');
                $mobile = $adb->query_result($result, 0, 'phone');
                $useruniqeid =  $adb->query_result($result, 0, 'accesskey');
                $usercreatedid =  $adb->query_result($result, 0, 'serviceengineerid');
            } else {
                $response->setError(100, 'Unable To Find User');
                return $response;
            }
            if (!empty($email)) {
                if($email != $emailFromRequest){
                    $response->setError(100, 'Email Associated With Badge Number Is Not Matching');
                    return $response;
                }
                $time = time();
                $otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                $options = array(
                    'handler_path' => 'modules/Users/handlers/ForgotPassword.php',
                    'handler_class' => 'Users_ForgotPassword_Handler',
                    'handler_function' => 'changePassword',
                    'onetime' => 0
                );
                $handler_data['time'] = strtotime("+15 Minute");
                $handler_data['hash'] = md5($badgeNo . $time);
                $handler_data['otp'] = $otp;
                $handler_data['badgeNo'] = $badgeNo;
                $handler_data['email'] = $emailFromRequest;
                $handler_data['mobile'] = $mobile;
                $handler_data['id'] = $id;
                $options['handler_data'] = $handler_data;
                $trackURL = Vtiger_ShortURL_Helper::generateURLMobile($options);
                $content = 'Dear User,<br><br> 
                You recently requested a password reset for your CRM Account.<br> 
                To create a new password, Here is your OTP ' . $otp . '
                <br><br> 
                This request was made on ' . date("d/m/Y h:i:s a")  . ' and will expire in next 15 Minutes.<br><br> 
                Regards,<br> 
                CRM Support Team.<br>';

                $subject = 'CRM: Password Reset';
                vimport('~~/modules/Emails/mail.php');
                global $HELPDESK_SUPPORT_EMAIL_ID, $HELPDESK_SUPPORT_NAME;
                $status = send_mail('Users', $email, $HELPDESK_SUPPORT_NAME, $HELPDESK_SUPPORT_EMAIL_ID, $subject, $content, '', '', '', '', '', true);
                $reusultOfCUrl = '';
                global $smsEndPoint;
                if (!empty($mobile)) {
                    $text = urlencode("Dear BEML CRM User,OTP for Forgot Password on BEML CRM Application for User Id, Badge No $badgeNo, OTP - $otp has been sent to your registered Email id and Mobile No. Do not share the OTP with anyone.BEML CRM Project");
                    $url = "$smsEndPoint?loginID=beml_htuser&mobile=$mobile&text=$text&senderid=BEMLHQ"
                    . "&DLT_TM_ID=1001096933494158&DLT_TM_ID=1001096933494158&DLT_CT_ID=1007149874152170556"
                    . "&DLT_PE_ID=1001209734454178165&route_id=DLT_SERVICE_IMPLICT&Unicode=0&camp_name=beml_htuser&password=beml@123";
                    $header = array('Content-Type:multipart/form-data');
                    $resource = curl_init();
                    curl_setopt($resource, CURLOPT_URL, $url);
                    curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($resource, CURLOPT_POST, 1);
                    curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($resource, CURLOPT_POSTFIELDS, array());
                    $reusultOfCUrl = trim(curl_exec($resource));
                    // global $log;
                    // $log->debug("<<<<<<<<<<<<<<<<<<<<<<bgggggggggggggggggggg>>>>>>>>>>>>>>>>>>>>>");
                    // $log->debug(json_encode($reusultOfCUrl));
                    // $log->debug("<<<<<<<<<<<<<<<<<<<<<<bgggggggggggggggggggg>>>>>>>>>>>>>>>>>>>>>");
                }
                if ($status === 1 || $status === true || $reusultOfCUrl == 'SUCCESS') {
                    $responseObject['uid'] = $trackURL;
                    $responseObject['usermobilenumber'] = $mobile;
                    $date = new DateTime();
                    $responseObject['usercreatedid'] = $usercreatedid;
                    $responseObject['useruniqeid'] = $id;
                    $responseObject['timestamp'] = $date->getTimestamp();
                    $responseObject['usertype'] = 'BEMLUSER';
                    $responseObject['message'] = 'OTP Has Sent To Registered Email';
                    $response->setApiSucessMessage('OTP Has Sent To Registered Email');
                    $response->setResult($responseObject);
                    $result = $adb->pquery('update vtiger_shorturls set ip_address = ? where uid= ? ', array($IpAddress, $trackURL));
                    return $response;
                } else {
                    $response->setError(100, 'Not Able To Send Email');
                    return $response;
                }
            } else {
                $response->setError(100, 'Unable To Find Email Of The User');
                return $response;
            }
        } else {
            $products['result'] = false;
            $products['message'] = "Email Id Is Required To Send OTP";
            $response->setResult($products);
            return $response;
        }
    }

    function getClientIp() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}
