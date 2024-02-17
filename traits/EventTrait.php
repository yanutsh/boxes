<?php 
namespace app\traits;
 
trait EventTrait
{
    public $events = [];
 
    public function recordEvent($event): void
    {
        $this->events[] = $event;
        //debug($this->events);
    }
 
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}