<?php
/**
 * Created by PhpStorm.
 * User: moi
 * Date: 14/04/2017
 * Time: 13:12
 */

namespace MicroCMS\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;


class PublishedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('public', CheckboxType::class, array(
            'label' => 'déclarez ce commentaire ?',
            'required' => false,
        ));
    }

    public function getName()
    {
        return 'checkbox';
    }
}

class UserDAO extends DAO implements UserProviderInterface