<?php

namespace ylaakel\BilleterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;



class InfoBilletType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class)
                ->add('prenom', TextType::class)
                //Liste des pays
                ->add('pays', CountryType::class, array(
                    'placeholder' => '',
                     ))
                ->add('dateNaissance', DateType::class, array(
                    'label' => 'Date de naissance',
                    'widget' => 'choice',
                    //Restriction des années sur la date de naissance
                    'years' => range(date('Y'), date('Y') - 100),
                    'placeholder' => array('day' => 'Jour', 'month' => 'Mois', 'year' => 'Année'),
                    'input' => 'datetime',
                    'format' => 'dd-MM-yyyy'))
                ->add('tarifReduit', CheckboxType::class, array(
                    'label' => 'Tarif réduit?',
                    'required' => false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ylaakel\BilleterieBundle\Entity\InfoBillet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ylaakel_billeteriebundle_infobillet';
    }


}
