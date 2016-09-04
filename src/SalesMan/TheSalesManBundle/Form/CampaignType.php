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
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use SalesMan\TheSalesManBundle\Form\Type\DateTimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to create and manipulate blog posts.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class CampaignType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // For the full reference of options defined by each form field type
        // see http://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        //
        //     $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('Category_Name', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.categoryName',
            ])
            ->add('Campaign_Name', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.campaingName',
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
            ->add('save', ButtonType::class, array(
                'attr' => array('class' => 'save'),
            ));
//            ->add('summary', TextareaType::class, [
//                'label' => 'label.summary',
//            ])
//            ->add('content', null, [
//                'attr' => ['rows' => 20],
//                'label' => 'label.content',
//            ])
//            ->add('authorEmail', null, [
//                'label' => 'label.author_email',
//            ])

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Post::class,
        ]);
    }
}
