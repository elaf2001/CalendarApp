<?php

namespace App\Controllers; 

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
        $data = [];

        foreach ($eventData as $row) {
            $data[] = [
                'id' => $row['id'],
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

}

?>