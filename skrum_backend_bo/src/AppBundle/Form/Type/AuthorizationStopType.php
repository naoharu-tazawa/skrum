<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\Data\AuthorizationStopData;
use AppBundle\Utils\DBConstant;

/**
 * AuthorizationStopフォームタイプクラス
 *
 * @author naoharu.tazawa
 */
class AuthorizationStopType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('authorizationStopFlg', ChoiceType::class, array(
                    'choices' => array(
                        '' => 'empty_value',
                        '利用停止' => DBConstant::FLG_TRUE,
                        '利用停止解除' => DBConstant::FLG_FALSE
                    ),
                    'choices_as_values' => true
                ))
                ->add('submit', SubmitType::class, ['label' => '利用停止/解除する']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AuthorizationStopData::class,
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_authorizationStopData';
    }
}
