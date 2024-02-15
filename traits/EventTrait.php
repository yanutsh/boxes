<?php 
namespace app\traits;
 
trait EventTrait
{
    public $events = [];
 
    public function recordEvent($event): void
    {
        $this->events[] = $event;
    }
 
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}