<?php

    // This page contains a variety of functions that can be used to access the Canvas API

    $canvasDomain = 'oregonstate.instructure.com'
    $token = '1002~dBAbJ2p3LCUVQTdnVjYkiB2LLDUNg5PNJTEOUs8CgXXx21j4q8BnQWuGKcIbyLdd';

    // This is the header containing the authorization token from Canvas
    $tokenHeader = array("Authorization: Bearer ".$token);



    // the following functions run the GET and POST calls
        if (!function_exists('http_parse_headers')) {
            function http_parse_headers($raw_headers) {
                $headers = array();
                $key = '';

                foreach(explode("\n", $raw_headers) as $i => $h) {
                    $h = explode(':', $h, 2);

                    if (isset($h[1])) {
                        if (!isset($headers[$h[0]]))
                            $headers[$h[0]] = trim($h[1]);
                        elseif (is_array($headers[$h[0]])) {
                            $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                        }
                        else {
                            $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                        }

                        $key = $h[0];
                    }
                    else { 
                        if (substr($h[0], 0, 1) == "\t")
                            $headers[$key] .= "\r\n\t".trim($h[0]);
                        elseif (!$key) 
                            $headers[0] = trim($h[0]); 
                    }
                }

                return $headers;
            }
        }
        function curlGet($url) {
            global $tokenHeader, $canvasDomain;
            $ch = curl_init($url);
            if (strpos($url, $canvasDomain) !== false) {
                curl_setopt ($ch, CURLOPT_URL, $url);
            } else {
                curl_setopt ($ch, CURLOPT_URL, 'https://'.$canvasDomain.'/api/v1/'.$url);
            }
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $tokenHeader);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // ask for results to be returned
            curl_setopt($ch, CURLOPT_VERBOSE, 1); //Requires to load headers
            curl_setopt($ch, CURLOPT_HEADER, 1);  //Requires to load headers
            $result = curl_exec($ch);
            // var_dump($result);

            #Parse header information from body response
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($result, 0, $header_size);
            $body = substr($result, $header_size);
            $data = json_decode($body);
            curl_close($ch);
                
            #Parse Link Information
            $header_info = http_parse_headers($header);
            if(isset($header_info['Link'])){
                $links = explode(',', $header_info['Link']);
                foreach ($links as $value) {
                    if (preg_match('/^\s*<(.*?)>;\s*rel="(.*?)"/', $value, $match)) {
                        $links[$match[2]] = $match[1];
                    }
                }
            }
            #Check for Pagination
            if(isset($links['next'])){
                // Remove the API url so it is not added again in the get call
                $next_link = str_replace('https://'.$canvasDomain.'/api/v1/', '', $links['next']);
                $next_data = curlGet($next_link);
                $data = array_merge($data,$next_data);
                return $data;
            }else{
                return $data;
            }
        }
        function curlPost($url, $data) {
            global $tokenHeader, $canvasDomain;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://'.$canvasDomain.'/api/v1/'.$url);
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $tokenHeader);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // ask for results to be returned

            // Send to remote and return data to caller.
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }
        function curlPut($url, $data){
            global $tokenHeader, $canvasDomain;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://'.$canvasDomain.'/api/v1/'.$url);
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $tokenHeader);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // ask for results to be returned

            // Send to remote and return data to caller.
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }

        function curlDelete($url, $header) {
            global $tokenHeader, $canvasDomain;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://'.$canvasDomain.'/api/v1/'.$url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $tokenHeader);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            // Send to remote and return data to caller.
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }

    // Canvas API Calls
        function createGenericAssignment($courseID, $assignmentParams){
            $createAssignmentURL = "courses/".$courseID."/assignments";
            $response = curlPost($createAssignmentURL, $assignmentParams);
            $responseData = json_decode($response, true);
            $assignmentID = $responseData['id'];
            // Returns new assignment ID
            return $assignmentID;

        }
        function createGenericDiscussion($courseID, $discussionParams){
            $createDiscussionURL = "courses/".$courseID."/discussion_topics";
            $response = curlPost($createDiscussionURL, $discussionParams);
            $responseData = json_decode($response, true);
            $discussionID = $responseData['id'];
            // Returns new discussion ID
            return $discussionID;
        }
        function createGenericQuiz($courseID, $quizParams){
            $createQuizURL = "courses/".$courseID."/quizzes";
            $response = curlPost($createQuizURL, $quizParams);
            $responseData = json_decode($response, true);
            $quizID = $responseData['id'];
            // Returns new quiz ID
            return $quizID;
        }
        function createModule($courseID, $moduleParams){
            $createModuleUrl = "courses/".$courseID."/modules";
            $response = curlPost($createModuleUrl, $moduleParams);
            $responseData = json_decode($response, true);
            $moduleID = $responseData['id'];
            // Returns new module ID
            return $moduleID;
        }
        function updateModule($courseID, $moduleID, $moduleParams){
            $updateModuleUrl = "courses/".$courseID."/modules/".$moduleID;
            $response = curlPut($updateModuleUrl, $moduleParams);
            return $response;
        }
        function createModuleItem($courseID, $moduleID, $itemParams){
            $createModuleUrl = "courses/".$courseID."/modules/".$moduleID."/items";
            $response = curlPost($createModuleUrl, $itemParams);
            return $response;
        }
        function createPage($courseID, $pageParams){
            $apiUrl = "courses/".$courseID."/pages";
            $response = curlPost($apiUrl, $pageParams);
            return $response;
        }
        function changeFrontPage($courseID, $url){
            $apiUrl = "courses/".$courseID."/pages/".$url;
            $pageParams = 'wiki_page[front_page]=true&wiki_page[published]=true';
            $response = curlPut($apiUrl, $pageParams);
            return $response;
        }
        function getCourse($courseID){
            $apiUrl = "courses/".$courseID."?include[]=terms";
            $response = curlGet($apiUrl);
            return $response;
        }
        function getCourseUnpublishedPages($courseID){
            $apiUrl = "courses/".$courseID."/pages?published=false";
            $response = curlGet($apiUrl);
            return $response;
        }
        function getCoursePages($courseID){
            $apiUrl = "courses/".$courseID."/pages";
            $response = curlGet($apiUrl);
            return $response;
        }
        // Assignments
        function listAssignments($courseID){
            $response = curlGet("courses/".$courseID."/assignments?per_page=50&override_assignment_dates=false");
            return $response;
        }
         function getAssignment($courseID, $assignmentID){
            $response = curlGet("courses/".$courseID."/assignments/".$assignmentID);
            return $response;
        }
        function updateAssignmentDates($courseID, $assignmentID, $dueDate, $unlockDate, $lockDate) {
            $apiURL = "courses/".$courseID."/assignments/".$assignmentID;
            $assignmentParams = "assignment[due_at]=".$dueDate."&assignment[lock_at]=".$lockDate."&assignment[unlock_at]=".$unlockDate;
            $response = curlPut($apiURL, $assignmentParams);
            return $response;
        }
        // List Submissions for a given assignment
        function listAssignmentsSubmissionsByStudent($courseID, $assignmentID, $studentList){
            $studentIDList = explode(',', $studentList);
            $studentParams = '';
            foreach ($studentIDList as $studentID) {
                $studentParams .= '&student_ids[]=' . $studentID;
            }
            $response = curlGet("courses/".$courseID."/students/submissions?per_page=50&assignment_ids[]=".$assignmentID."&grouped=true".$studentParams);
            return $response;
        }
        
        function getPageBody($courseID, $page_url){
            // Get the response
            $page = getPageFromCourse($courseID, $page_url);
            // return only the body
            $body = $page->body;
            return $body;
        }
        function getPageFromCourse($courseID, $page_url){
            $apiUrl = "courses/".$courseID."/pages/".$page_url;
            $response = curlGet($apiUrl);
            return $response;
        }
        function listModules($courseID){
            $apiUrl = "courses/".$courseID."/modules/";
            $response = curlGet($apiUrl);
            return $response;
        }
        function uploadFrontPageBanner($courseID, $fileName){
            $apiUrl = "courses/".$courseID."/files";
            $apiParams = "name=".$fileName.".jpg&content_type=image/jpeg&parent_folder_path=/images&url=".$_SESSION['tool_url']."/image_upload/images/".$_SESSION['inst'].'_'.$courseID."_".$fileName.".jpg&on_duplicate=overwrite";
            $response = curlPost($apiUrl, $apiParams);
            return $response;
        }
        function updateModuleOrder($courseID, $moduleID, $modulePosition){
            $apiUrl = "courses/".$courseID."/modules/".$moduleID;
            $apiParams = "module[position]=".$modulePosition;
            $response = curlPut($apiUrl, $apiParams);
            return $response;
        }
        function listStudentEnrollments($courseID){
            $apiUrl = "courses/".$courseID."/enrollments?type[]=StudentEnrollment";
            $response = curlGet($apiUrl);
            return $response;
        }


?>