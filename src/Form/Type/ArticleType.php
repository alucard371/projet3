<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 30/03/2017
 * Time: 13:53
 */

namespace microCMS\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titre'
            ))
            ->add('content',TextareaType::class, array(
                'required' => false,
                'label'    => 'Contenu'
            ));
    }

    public function getName()
    {
        return 'article';
    }
}