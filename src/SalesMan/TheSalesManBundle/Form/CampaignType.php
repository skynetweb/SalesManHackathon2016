<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SalesMan\TheSalesManBundle\Form;
use SalesMan\TheSalesManBundle\Form\Type\DateTimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CampaignType
 * @package SalesMan\TheSalesManBundle\Form
 */
class CampaignType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('Campaign_Name', ChoiceType::class, array(
                'choices'  => array(
                    'StockBusters' => 'StockBusters',
                    'BlackFriday' => 'Black Friday'
                ),
            ))
            ->add('Category_Name', ChoiceType::class, [
                'attr' => ['autofocus' => true],
                'choices' => array(
                    'Telefoane' => 'Telefoane',
                    'Tablete' => 'Tablete',
                    'Televizoare' => 'Televizoare',
                    'Laptopuri' => 'Laptopuri'

                ),
                'label' => 'label.categoryName',
            ])
            ->add('startDate', DateTimePickerType::class, [
                'label' => 'label.start_at',
            ])
            ->add('endDate', DateTimePickerType::class, [
                'label' => 'label.end_at',
            ])
            ->add('Discount', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.discount',
            ])
            ->add('Budget_Marketing', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.budget',
            ])
            ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'save'),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
