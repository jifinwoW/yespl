<?php

class Mobile_WS_AttendanceRegularization extends Mobile_WS_Controller
{
    function process(Mobile_API_Request $request)
    {
        global $current_user, $adb;
        $response = new Mobile_API_Response();
        $recordId = $current_user->id;
        $userName = $current_user->user_name;

        $month = $request->get('month');
        $year = $request->get('year');
        $useruniqid = $request->get('useruniqid');

        function getHolidaysForMonth($month, $year)
        {
            $db = PearDatabase::getInstance();
            $query = "SELECT holidaydate FROM vtiger_holidaylist
                      INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_holidaylist.holidaylistid
                      WHERE MONTH(holidaydate) = ? AND YEAR(holidaydate) = ? AND vtiger_crmentity.deleted = 0";
            $result = $db->pquery($query, [$month, $year]);

            $holidays = [];
            while ($row = $db->fetch_array($result)) {
                $holidays[] = $row['holidaydate'];
            }
            return $holidays;
        }

        function getMissingAttendanceDates($month, $year, $useruniqid)
        {
            $firstDay = new DateTime("first day of $year-$month");
            $lastDay = new DateTime("last day of $year-$month");

            $datesInMonth = [];
            for ($date = clone $firstDay; $date <= $lastDay; $date->modify('+1 day')) {
                $datesInMonth[] = $date->format('Y-m-d');
            }

            $db = PearDatabase::getInstance();
            $query = "SELECT attendanceid, checkindate, checkoutdate 
                      FROM vtiger_attendance 
                      INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_attendance.attendanceid
                      WHERE MONTH(checkindate) = ? AND YEAR(checkindate) = ? 
                      AND vtiger_crmentity.smownerid = ? 
                      AND vtiger_crmentity.deleted = 0
                      AND checkoutdate IS NULL";
            $params = [$month, $year, $useruniqid];
            $result = $db->pquery($query, $params);

            $attendanceRecords = [];
            $existingDates = [];
            while ($row = $db->fetch_array($result)) {
                $checkinDate = $row['checkindate'];
                $existingDates[] = $checkinDate;
                $attendanceRecords[$checkinDate] = [
                    'attendanceid' => $row['attendanceid'],
                    'checkindate' => $checkinDate
                ];
            }

            $missingDates = array_diff($datesInMonth, $existingDates);
            return [
                'missingDates' => $missingDates,
                'attendanceRecords' => $attendanceRecords
            ];
        }

        $attendanceData = getMissingAttendanceDates($month, $year, $useruniqid);
        $holidays = getHolidaysForMonth($month, $year);

        $formattedAttendanceData = [];

        // Add holidays
        foreach ($holidays as $holidayDate) {
            $formattedAttendanceData[$holidayDate] = [
                'date' => $holidayDate,
                'attendance_status' => 'Holiday',
                'user_id' => $useruniqid,
                'attendance_id' => null,
                'checkin_date' => null
            ];
        }

        // Add missing dates for regularization (excluding holidays)
        foreach ($attendanceData['missingDates'] as $date) {
            if (!isset($formattedAttendanceData[$date])) {
                $formattedAttendanceData[$date] = [
                    'date' => $date,
                    'attendance_status' => 'Regularization',
                    'user_id' => $useruniqid,
                    'attendance_id' => null,
                    'checkin_date' => null
                ];
            }
        }

        // Add pending check-outs (overwrite if already present)
        foreach ($attendanceData['attendanceRecords'] as $date => $record) {
            $formattedAttendanceData[$date] = [
                'date' => $date,
                'attendance_status' => 'Pending Check Out',
                'user_id' => $useruniqid,
                'attendance_id' => $record['attendanceid'],
                'checkin_date' => $record['checkindate']
            ];
        }

        // Sort by date keys
        ksort($formattedAttendanceData);

        $response->setApiSucessMessage('Attendance records fetched successfully.');
        $response->setResult(array_values($formattedAttendanceData)); // Reset index
        return $response;
    }
}
