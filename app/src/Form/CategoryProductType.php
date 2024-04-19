<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'mapped' => true,
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'choice_label' => function (Category $category) {
                    return $category->getName();
                },
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this, 'submit']
        );
    }

    #[NoReturn] public function submit(SubmitEvent $event): void
    {
        $product = $event->getData();
        $categories = clone $product->getCategories();
        $product->setCategories($categories);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
