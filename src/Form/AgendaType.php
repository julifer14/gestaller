<?php

namespace App\Form;

use App\Entity\Agenda;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{CollectionType, TextType, NumberType, DateTimeType, ChoiceType, TextareaType};
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AgendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('dataHora', DateTimeType::class, ['label' => 'Data/Hora ','widget' => 'choice',])
            ->add('dataHoraInici', TextType::class, [
                'required' => true,
                'label' => 'Inici',
                //'translation_domain' => 'AppBundle',
                'attr' => [
                    'class' => 'form-control input-inline form_datetime',
                    'data-provide' => 'datetimepicker',
                    'data-format' => 'dd-mm-yyyy HH:ii',
                ],
            ])
            ->add('dataHoraFi', TextType::class, [
                'required' => true,
                'label' => 'Fi',
                //'translation_domain' => 'AppBundle',
                'attr' => [
                    'class' => 'form-control input-inline form_datetime',
                    'data-provide' => 'datetimepicker',
                    'data-format' => 'dd-mm-yyyy HH:ii',
                ],
            ])
            ->add('vehicle')
            ->add('treballador')
            ->add('tasca')
            ->add('estat', ChoiceType::class, [

                'choices' => [
                    'Pendent' => 0,
                    'Completada' => 1,
                    'Anulada' => 2

                ],
                'label' => 'Tasca',

            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $e = $event->getData();
                $dataIni = $e['dataHoraInici'];
                $dataIniOk = new DateTime($dataIni);
                $e['dataHoraInici'] = $dataIniOk;

                $dataFi = $e['dataHoraFi'];
                $dataFiOk = new DateTime($dataFi);
                $e['dataHoraFi'] = $dataFiOk;
                $event->setData($e);
                
            });;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
        ]);
    }
}
