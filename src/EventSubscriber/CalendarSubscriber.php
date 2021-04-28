<?php

namespace App\EventSubscriber;

use App\Repository\AgendaRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    protected $em;
    private $bookingRepository;
    private $router;

    public function __construct(
        AgendaRepository $agendaRepository,
        UrlGeneratorInterface $router,
        EntityManagerInterface $em
    ) {
        $this->em = $em;
        $this->agendaRepository = $agendaRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        
        $filters = $calendar->getFilters();
        $start = $calendar->getStart();
        $end = $calendar->getEnd();

        if(!empty($start) and !empty($end)){
            $agendas = $this->em->getRepository(Agenda::class)->findBetweenDates($start, $end);

        }else{
            $agendas = $this->em->getRepository(Agenda::class)->findAll();
        }

        foreach ($agendas as $agenda) {
            $event = new Event(
                $agenda->getTitol(),
                $agenda->getDataHoraInici(),
                $agenda->getDataHoraFi(),
            );
            
            /*$event->addOption(
                'url',
                $this->router->generate('agenda.show', [
                    'id' => $agenda->getId(),
                    'titol' => urlencode($agenda->getTitol())
                ])
            );*/

            $calendar->addEvent($event);
        }

    }

   /* public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $agendas = $this->agendaRepository
            ->createQueryBuilder('agenda')
            ->where('agenda.beginAt BETWEEN :start and :end OR booking.endAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($bookings as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $booking->getTitle(),
                $booking->getBeginAt(),
                $booking->getEndAt() // If the end date is null or not defined, a all day event is created.
            );

       

            $bookingEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $bookingEvent->addOption(
                'url',
                $this->router->generate('booking_show', [
                    'id' => $booking->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }*/
    
}