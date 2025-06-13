<?php 
class validate {

    public function checkEmpty($data, $fields) {
        $msg = "null";
        foreach($fields as $field) {
            if(empty($data[$field])) {
                $msg .= "<p>" . $field . " please enter something</p>";
            }
        }
        return $msg;
    }

    public function validEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public function validGrade($grade) {
        $valid_grades = ['A', 'A+', 'B', 'B+', 'C', 'C+', 'D',  'D+'];
        if (!in_array($grade, $valid_grades)) {
            return "please enter a valid grade";
        }
        return false;
    }

    public function validCourse($course) {
        if (!empty($course)) {
            return true;
        } else {
            return false;
        }
    }
}
?>
