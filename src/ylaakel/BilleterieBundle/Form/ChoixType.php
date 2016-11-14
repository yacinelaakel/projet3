<?php

namespace ylaakel\BilleterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ChoixType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('laDate', DateType::class, array(
                    'label' => 'Date de réservation',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'dd-MM-yyyy')
                    )
                ->add('typeBillet', ChoiceType::class, array(
                    'label' => 'Type de billet',
                    'choices' => array('Journée' => true, 'Demi-journée' => false),
                    'expanded' => true,
                    'multiple' => false,
                    'required' => true
                    ))
                ->add('nbrBillet', ChoiceType::class, array(
                    'label' => 'Nombre de billet(s)',
                    'choices' => array('1' => 1, '2' => 2, '3' => 3)
                    ));
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ylaakel\BilleterieBundle\Entity\Choix'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ylaakel_billeteriebundle_choix_billet';
    }


}
