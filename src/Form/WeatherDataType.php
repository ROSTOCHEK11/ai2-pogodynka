<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\WeatherData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeatherDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
            ->add('temperature', NumberType::class, [
                'label' => 'Temperature (°C)',
                'scale' => 2,
                'html5' => true,
            ])
            ->add('humidity', NumberType::class, [
                'label' => 'Humidity (%)',
                'scale' => 1,
                'html5' => true,
            ])
            ->add('pressure', NumberType::class, [
                'label' => 'Pressure (hPa)',
                'html5' => true,
            ])
            ->add('wind_speed', NumberType::class, [
                'label' => 'Wind Speed (m/s)',
                'scale' => 1,
                'html5' => true,
            ])
            ->add('weather_type', TextType::class, [
                'label' => 'Weather Type',
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'id',
                'label' => 'Location',
                'placeholder' => 'Select location',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WeatherData::class,
        ]);
    }
}
