<?php
namespace App\Models;
use CodeIgniter\Model; 

class FullCalendarModel extends Model
{
        protected $table = 'events';

        protected $primaryKey = 'id';
    
        protected $allowedFields = ['id', 'title', 'start_event', 'end_event', 'user_id']; 

        public function fetchAllEvent()
        {
            $this->orderBy('id');
            $this->where('user_id', session()->get('loggedUser')); 
            return $this->findAll();
        }
    
        public function insertEvent($data)
        {
            $this->insert($data);
        }
    
        public function updateEvent($data, $id)
        {
            $this->where('id', $id);
            $this->set($data);
            $this->update();
        }
    
        public function deleteEvent($id)
        {
            $this->where('id', $id);
            $this->delete();
        }
}

?>