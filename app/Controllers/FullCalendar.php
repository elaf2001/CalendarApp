<?php

namespace App\Controllers; 
use DateTime;
use HolidayAPI\Client;

class Fullcalendar extends BaseController {

    protected $fullcalendarModel;
    public function __construct()
    {
        $this->fullcalendarModel = new \App\Models\FullCalendarModel(); 
    }

    function index()
    {
        return view('calendar'); 
    }

    public function load()
    {
        $eventData = $this->fullcalendarModel->fetchAllEvent();
        $holidays = $this->getPublicHolidays(); 

        $data = [];
        // add holidays to the data 
        foreach ($holidays as $row) {
            $data[] = [
                'title' => $row['name'],
                'start' => $row['date'],
                'end' => $row['date']
            ];
        }
        

        foreach ($eventData as $row) {
            $data[] = [
                'title' => $row['title'],
                'start' => $row['start_event'],
                'end' => $row['end_event']
            ];
        }

        echo json_encode($data);
    }


    public function insert()
    {
        if ($this->request->getPost('title')) {
            $data = [
                'title' => $this->request->getPost('title'),
                'start_event' => $this->request->getPost('start'),
                'end_event' => $this->request->getPost('end'), 
                'user_id' => session()->get('loggedUser')
            ];
            $this->fullcalendarModel->insertEvent($data);
        }
    }

    public function update()
    {
        if ($this->request->getPost('id')) {
            $data = [
                'title' => $this->request->getPost('title'),
                'start_event' => $this->request->getPost('start'),
                'end_event' => $this->request->getPost('end'),
                'user_id' => session()->get('loggedUser')
            ];

            $this->fullcalendarModel->updateEvent($data, $this->request->getPost('id'));
        }
    }

    public function delete()
    {
        if ($this->request->getPost('id')) {
            $this->fullcalendarModel->deleteEvent($this->request->getPost('id'));
        }
    }

    public function getPublicHolidays(){

        // do the request 

        $key = '6be9802d-8d68-4b72-9153-53ad474be04c';
        $holiday_api = new Client(['key' => $key]);

        //get the current year 
        $dateString = '2023-06-13 00:00:00';
        $date = new DateTime($dateString);
        // should be used instead of 2022, but I am using the free version of the API 
        $year = $date->format('Y');
        $response = $holiday_api->holidays([
            'country' => 'MY',
            'year' => '2022', 
            'public' => true
        ]);

        $holidays = $response['holidays']; 
        return $holidays; 
        
    }

}

?>