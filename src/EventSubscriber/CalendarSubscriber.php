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
    private $agendaRepository;
    private $router;

    public function __construct(AgendaRepository $agendaRepository, UrlGeneratorInterface $router, EntityManagerInterface $em)
    {
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
        $usuari = $filters['user_id'];

        $agendas = $this->agendaRepository
            ->createQueryBuilder('agenda')
            ->where('(agenda.treballador = :usuari) and (agenda.dataHoraInici BETWEEN :start and :end OR agenda.dataHoraFi BETWEEN :start and :end)  ')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('usuari', $usuari)
            ->getQuery()
            ->getResult();

        foreach ($agendas as $agenda) {
            $event = new Event(
                $agenda->getTitol(),
                $agenda->getDataHoraInici(),
                $agenda->getDataHoraFi(),

            );
            if ($agenda->getallDay()) {
                $event->setAllDay($agenda->getallDay());
            }
            if($agenda->getEstat()==0){
                //Pendent vermell
                $event->setOptions([
                    'backgroundColor'=> '#F08080',
                    'borderColor'=>'#F08080',
                    'textColor'=>'black'
                ]);
            }else if($agenda->getEstat()==1){
                //Completada verd
                $event->setOptions([
                    'backgroundColor'=> '#90EE90',
                    'borderColor'=>'#90EE90',
                    'textColor'=>'black'
                ]);
            }else{
                //rebutjada gris
                $event->setOptions([
                    'backgroundColor'=> '#D3D3D3',
                    'borderColor'=>'#D3D3D3',
                    'textColor'=>'black'
                ]);
            }

            $event->addOption(
                'url',
                $this->router->generate('agenda_show', [
                    'id' => $agenda->getId(),
                    'titol' => urlencode($agenda->getTitol())
                ])
            );

            $calendar->addEvent($event);
        }
    }

}
