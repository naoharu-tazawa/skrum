<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\Data\PlanChangeData;
use AppBundle\Utils\DBConstant;

/**
 * PlanChangeフォームタイプクラス
 *
 * @author naoharu.tazawa
 */
class PlanChangeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('planId', ChoiceType::class, array(
                    'choices' => array(
                        '' => 'empty_value',
                        'スタンダードプラン' => DBConstant::PLAN_ID_STANDARD_PLAN
                    ),
                    'choices_as_values' => true
                ))
                ->add('submit', SubmitType::class, ['label' => 'プラン変更する']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PlanChangeData::class,
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_planChangeData';
    }
}
